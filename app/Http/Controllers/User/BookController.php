<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->email === 'admin@librasense.com') {
                abort(403, 'This section is for regular users only.');
            }
            return $next($request);
        });
    }

    /**
     * Search for books based on title, author, or ISBN.
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $books = Book::where('title', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->orWhere('isbn', 'like', "%{$query}%")
            ->get();

        return view('user.books.search', compact('books', 'query'));
    }

    /**
     * Display details of a specific book.
     */
    public function show(Book $book)
    {
        return view('user.books.show', compact('book'));
    }

    public function borrow(Book $book)
    {
        // Book borrowing logic
    }
} 