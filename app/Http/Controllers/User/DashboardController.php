<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Feedback;
use App\Models\VisitorAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get current loans
        $current_loans = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->whereNull('return_date')
            ->with('book')
            ->latest()
            ->get();

        // Get overdue loans
        $overdue_loans = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->whereNull('return_date')
            ->where('is_overdue', true)
            ->with('book')
            ->latest()
            ->get();

        // Get past loans
        $past_loans = Loan::where('user_id', $user->id)
            ->whereNotNull('return_date')
            ->with('book')
            ->latest()
            ->take(5)
            ->get();

        // Get book recommendations based on user's loan history
        $recommended_books = $this->getBookRecommendations($user);

        // Stats for user dashboard
        $stats = [
            'total_loans' => Loan::where('user_id', $user->id)->count(),
            'active_loans' => $current_loans->count(),
            'overdue_loans' => $overdue_loans->count(),
            'reserved_loans' => Loan::where('user_id', $user->id)->where('status', 'reserved')->count(),
            'past_loans' => Loan::where('user_id', $user->id)->whereNotNull('return_date')->count(),
            'feedback_count' => \App\Models\Feedback::where('user_id', $user->id)->count(),
        ];

        // Recent feedback by user
        $recent_feedback = \App\Models\Feedback::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('current_loans', 'overdue_loans', 'past_loans', 'stats', 'recent_feedback', 'recommended_books'));
    }

    /**
     * Get book recommendations based on user's loan history
     */
    private function getBookRecommendations($user)
    {
        // Get user's most borrowed categories from past loans
        $user_categories = Loan::where('user_id', $user->id)
            ->whereNotNull('return_date')
            ->with('book')
            ->get()
            ->pluck('book.category')
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(3);

        // Get books from user's favorite categories that they haven't borrowed yet
        $recommended_books = Book::whereIn('category', $user_categories)
            ->where('is_available', true)
            ->whereDoesntHave('loans', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->inRandomOrder()
            ->take(5)
            ->get();

        // If we don't have enough recommendations, add some popular books from other categories
        if ($recommended_books->count() < 5) {
            $additional_books = Book::whereNotIn('category', $user_categories)
                ->where('is_available', true)
                ->whereDoesntHave('loans', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->inRandomOrder()
                ->take(5 - $recommended_books->count())
                ->get();

            $recommended_books = $recommended_books->concat($additional_books);
        }

        return $recommended_books;
    }

    public function searchBooks(Request $request)
    {
        $query = $request->input('query');
        
        $books = Book::where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('author', 'like', "%{$query}%")
              ->orWhere('category', 'like', "%{$query}%");
        })
        ->where('is_available', true)
        ->paginate(10);

        return view('user.book-search', compact('books', 'query'));
    }

    public function submitFeedback(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
            'feedback_type' => 'required|string|in:service,resource,facility',
            'is_anonymous' => 'boolean'
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'feedback_type' => $request->feedback_type,
            'is_anonymous' => $request->is_anonymous ?? false
        ]);

        return back()->with('success', 'Thank you for your feedback!');
    }

    public function generateQRCode()
    {
        $user = Auth::user();
        
        if (!$user->qr_code) {
            $user->qr_code = uniqid('QR_');
            $user->save();
        }

        return view('user.qr-code', compact('user'));
    }
} 