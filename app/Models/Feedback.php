<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category',
        'rating',
        'subject',
        'message',
        'sentiment',
        'topics',
        'frequent_patterns',
        'is_anomaly',
        'user_segment',
        'trend_data',
    ];

    protected $casts = [
        'rating' => 'integer',
        'topics' => 'array',
        'frequent_patterns' => 'array',
        'is_anomaly' => 'boolean',
        'user_segment' => 'array',
        'trend_data' => 'array',
    ];

    /**
     * Get the user that owns the feedback.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getCategoryStats()
    {
        return self::selectRaw('category, COUNT(*) as count, AVG(rating) as avg_rating')
            ->groupBy('category')
            ->get();
    }

    public static function getRatingDistribution()
    {
        return self::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();
    }

    public static function getRecentTrends($days = 30)
    {
        return self::selectRaw('DATE(created_at) as date, COUNT(*) as count, AVG(rating) as avg_rating')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public static function getSentimentStats()
    {
        return self::selectRaw('sentiment, COUNT(*) as count')
            ->groupBy('sentiment')
            ->get();
    }

    public static function getTopicDistribution()
    {
        return self::selectRaw('jsonb_array_elements(topics::jsonb) as topic, COUNT(*) as count')
            ->whereNotNull('topics')
            ->whereRaw('jsonb_typeof(topics::jsonb) = \'array\'')
            ->groupBy('topic')
            ->get();
    }

    public static function getAnomalyStats()
    {
        return self::selectRaw('is_anomaly, COUNT(*) as count')
            ->groupBy('is_anomaly')
            ->get();
    }

    public static function getUserSegmentStats()
    {
        return self::selectRaw('jsonb_array_elements(user_segment::jsonb) as segment, COUNT(*) as count')
            ->whereNotNull('user_segment')
            ->whereRaw('jsonb_typeof(user_segment::jsonb) = \'array\'')
            ->groupBy('segment')
            ->get();
    }
} 