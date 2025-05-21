<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'visitor_type',
        'entry_time',
        'exit_time',
        'location',
        'purpose',
        'qr_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 