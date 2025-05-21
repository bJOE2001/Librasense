<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'publication_year',
        'publisher',
        'quantity',
        'location',
        'is_available',
        'category'
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'quantity' => 'integer',
        'is_available' => 'boolean',
    ];

    /**
     * Get all loans for this book.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Check if the book is available for loan.
     */
    public function isAvailable()
    {
        return $this->quantity > 0 && $this->is_available;
    }

    /**
     * Get the status of the book.
     */
    public function getStatusAttribute()
    {
        return $this->isAvailable() ? 'available' : 'unavailable';
    }

    /**
     * Get the number of active loans for this book.
     */
    public function activeLoans()
    {
        return $this->loans()->whereNull('return_date')->count();
    }

    /**
     * Get the number of overdue loans for this book.
     */
    public function overdueLoans()
    {
        return $this->loans()
            ->where('due_date', '<', now())
            ->whereNull('return_date')
            ->count();
    }

    protected static function booted()
    {
        static::saving(function ($book) {
            // Use 'quantity' or 'copies_available' as appropriate
            $qty = $book->quantity ?? $book->copies_available ?? 0;
            $book->is_available = $qty > 0;
        });
    }
} 