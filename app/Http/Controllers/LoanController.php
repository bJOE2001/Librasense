<?php

namespace App\Http\Controllers;

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
        $this->middleware('auth');
    }

    public function index()
    {
        $loans = Loan::with(['book', 'user'])
            ->latest()
            ->get();
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $books = Book::where('quantity', '>', 0)->get();
        $users = User::where('email', '!=', 'admin@librasense.com')->get();
        return view('loans.create', compact('books', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after:loan_date',
        ]);

        $book = Book::findOrFail($request->book_id);
        $user = auth()->user();

        // Check if user already has a reservation or active loan for this book
        $existingLoan = Loan::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['reserved', 'active'])
            ->first();

        if ($existingLoan) {
            return back()->with('error', 'You already have a reservation or active loan for this book.');
        }

        // Check if book is available (quantity > 0)
        if ($book->quantity <= 0) {
            return back()->with('error', 'This book is currently unavailable.');
        }

        // Check if the book is already fully reserved
        $activeLoansCount = Loan::where('book_id', $book->id)
            ->whereIn('status', ['reserved', 'active'])
            ->count();

        if ($activeLoansCount >= $book->quantity) {
            $reservedBy = Loan::where('book_id', $book->id)
                ->whereIn('status', ['reserved', 'active'])
                ->with('user')
                ->get()
                ->map(function($loan) {
                    return $loan->user->name;
                })
                ->join(', ');

            return back()->with([
                'error' => 'This book is currently fully reserved.',
                'book_title' => $book->title,
                'reserved_by' => $reservedBy
            ]);
        }

        // Create the loan
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'reserved',
            'loan_date' => $request->loan_date,
            'due_date' => $request->due_date,
        ]);

        return back()->with('success', 'Book reserved successfully. Please visit the library to collect your book.');
    }

    public function show(Loan $loan)
    {
        $loan->load(['book', 'user']);
        return view('loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $books = Book::where('quantity', '>', 0)->get();
        $users = User::where('email', '!=', 'admin@librasense.com')->get();
        return view('loans.edit', compact('loan', 'books', 'users'));
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

        return redirect()->route('loans.index')
            ->with('success', 'Loan updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        $book = Book::findOrFail($loan->book_id);
        $book->quantity += 1;
        $book->save();
        
        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Loan deleted successfully.');
    }

    public function returnBook(Loan $loan)
    {
        if ($loan->return_date) {
            return back()->with('error', 'This book has already been returned.');
        }

        $loan->update([
            'return_date' => Carbon::now(),
            'is_overdue' => Carbon::now()->greaterThan($loan->due_date)
        ]);

        $book = Book::findOrFail($loan->book_id);
        $book->quantity += 1;
        $book->save();

        return redirect()->route('loans.index')
            ->with('success', 'Book returned successfully.');
    }
} 