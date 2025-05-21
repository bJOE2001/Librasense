<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->email !== 'admin@librasense.com') {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $loans = Loan::with(['book', 'user'])
            ->latest()
            ->get();
        $users = User::where('email', '!=', 'admin@librasense.com')->get();
        $books = Book::all();
        return view('admin.loans.index', compact('loans', 'users', 'books'));
    }

    public function create()
    {
        $books = Book::where('quantity', '>', 0)->get();
        $users = User::where('email', '!=', 'admin@librasense.com')->get();
        return view('admin.loans.create', compact('books', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $book = Book::findOrFail($validated['book_id']);
        $user = User::findOrFail($validated['user_id']);
        
        if ($book->quantity <= 0) {
            return back()->with('error', 'This book is not available for loan.');
        }

        // Check if user already has this book on loan or reserved (do not block on declined/returned)
        $existingLoan = Loan::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['reserved', 'active'])
            ->first();

        if ($existingLoan) {
            return back()->with('error', 'This user already has this book on loan or reserved.');
        }

        // Create loan and set due date to 14 days from now
        $loan = Loan::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'loan_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => 'active',
        ]);

        $book->quantity -= 1;
        $book->save();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Book loan created successfully. Due date: ' . $loan->due_date->format('M d, Y'));
    }

    public function show(Loan $loan)
    {
        $loan->load(['book', 'user']);
        return view('admin.loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $books = Book::where('quantity', '>', 0)->get();
        $users = User::where('email', '!=', 'admin@librasense.com')->get();
        return view('admin.loans.edit', compact('loan', 'books', 'users'));
    }

    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after:loan_date',
        ]);

        if ($loan->book_id != $validated['book_id']) {
            $oldBook = Book::findOrFail($loan->book_id);
            $oldBook->quantity += 1;
            $oldBook->save();
            
            $newBook = Book::findOrFail($validated['book_id']);
            if ($newBook->quantity <= 0) {
                return back()->with('error', 'This book is not available for loan.');
            }
            $newBook->quantity -= 1;
            $newBook->save();
        }

        $loan->update($validated);

        return redirect()->route('admin.loans.index')
            ->with('success', 'Loan updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        $book = Book::findOrFail($loan->book_id);

        // Only increase quantity if the book is still on loan (not yet returned) and not declined
        if (is_null($loan->return_date) && $loan->status !== 'declined') {
            $book->quantity += 1;
            $book->save();
        }

        $loan->delete();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Loan deleted successfully.');
    }

    public function returnBook(Loan $loan)
    {
        if ($loan->return_date) {
            return back()->with('error', 'This book has already been returned.');
        }

        $loan->update([
            'return_date' => Carbon::now(),
            'is_overdue' => Carbon::now()->greaterThan($loan->due_date),
            'status' => 'returned'
        ]);

        $book = Book::findOrFail($loan->book_id);
        $book->quantity += 1;
        $book->save();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Book returned successfully.');
    }

    public function approve(Loan $loan)
    {
        if ($loan->status !== 'reserved') {
            return back()->with('error', 'This loan is not in reserved status.');
        }

        $loan->update([
            'status' => 'active',
            'loan_date' => now(),
            'due_date' => now()->addDays(14)
        ]);

        $book = Book::findOrFail($loan->book_id);
        $book->quantity -= 1;
        $book->save();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Reservation approved and converted to active loan.');
    }

    public function decline(Loan $loan)
    {
        if ($loan->status !== 'reserved') {
            return back()->with('error', 'This loan is not in reserved status.');
        }

        $loan->update([
            'status' => 'declined'
        ]);

        // Do NOT increase book quantity when declining
        // $book = Book::findOrFail($loan->book_id);
        // $book->quantity += 1;
        // $book->save();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Reservation declined.');
    }

    public function approveByQr(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);
        $user = \App\Models\User::where('qr_code', $request->qr_code)->firstOrFail();

        $reservedLoans = Loan::where('user_id', $user->id)
            ->where('status', 'reserved')
            ->get();

        $approvedBooks = [];
        foreach ($reservedLoans as $loan) {
            $loan->update([
                'status' => 'active',
                'loan_date' => now(),
                'due_date' => now()->addDays(14)
            ]);
            $loan->book->decrement('quantity');
            $approvedBooks[] = $loan->book->title;
        }

        if (count($approvedBooks)) {
            return back()->with('success', 'Approved reserved books for this user.')->with('approved_books', $approvedBooks);
        } else {
            return back()->with('error', 'No reserved books found for this user.');
        }
    }

    public function returnByQr(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);
        $user = \App\Models\User::where('qr_code', $request->qr_code)->firstOrFail();

        $activeLoans = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->whereNull('return_date')
            ->get();

        $returnedBooks = [];
        foreach ($activeLoans as $loan) {
            $loan->update([
                'return_date' => now(),
                'status' => 'returned'
            ]);
            $loan->book->increment('quantity');
            $returnedBooks[] = $loan->book->title;
        }

        if (count($returnedBooks)) {
            return back()->with('success', 'Returned books for this user.')->with('returned_books', $returnedBooks);
        } else {
            return back()->with('error', 'No active loans found for this user.');
        }
    }

    public function scanQr(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);
        $user = \App\Models\User::where('qr_code', $request->qr_code)->firstOrFail();

        // Approve reserved loans if any
        $reservedLoans = Loan::where('user_id', $user->id)
            ->where('status', 'reserved')
            ->get();
        $approvedBooks = [];
        if ($reservedLoans->count() > 0) {
            foreach ($reservedLoans as $loan) {
                $loan->update([
                    'status' => 'active',
                    'loan_date' => now(),
                    'due_date' => now()->addDays(14)
                ]);
                $loan->book->decrement('quantity');
                $approvedBooks[] = $loan->book->title;
            }
            return back()
                ->with('approved_books', $approvedBooks)
                ->with('scanned_user_name', $user->name);
        }

        // If no reserved, return active loans if any
        $activeLoans = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->whereNull('return_date')
            ->get();
        $returnedBooks = [];
        if ($activeLoans->count() > 0) {
            foreach ($activeLoans as $loan) {
                $loan->update([
                    'return_date' => now(),
                    'status' => 'returned'
                ]);
                $loan->book->increment('quantity');
                $returnedBooks[] = $loan->book->title;
            }
            return back()
                ->with('returned_books', $returnedBooks)
                ->with('scanned_user_name', $user->name);
        }

        // If neither, show error
        return back()->with('error', 'No reserved or active loans found for this user.');
    }
} 