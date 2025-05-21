<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'entry_time',
        'exit_time',
        'duration_minutes'
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateDuration()
    {
        if ($this->entry_time && $this->exit_time) {
            $duration = $this->exit_time->diffInMinutes($this->entry_time);
            $this->duration_minutes = $duration;
            $this->save();
            return $duration;
        }
        return null;
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return 'N/A';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $minutes);
        }
        return sprintf('%dm', $minutes);
    }

    public function getCurrentDurationAttribute()
    {
        if (!$this->entry_time || $this->exit_time) {
            return null;
        }

        $duration = now()->diffInMinutes($this->entry_time);
        $hours = floor($duration / 60);
        $minutes = $duration % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $minutes);
        }
        return sprintf('%dm', $minutes);
    }
}
