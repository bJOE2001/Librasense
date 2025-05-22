<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'school',
        'phone',
        'address',
        'qr_code',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Generate a simple unique identifier for all users
            $user->qr_code = 'QR_' . uniqid() . '_' . time();
        });
    }

    public function libraryVisits()
    {
        return $this->hasMany(LibraryVisit::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function getCurrentVisitAttribute()
    {
        return $this->libraryVisits()->whereNull('exit_time')->latest('entry_time')->first();
    }

    public function getIsInsideAttribute()
    {
        return (bool) $this->current_visit;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role?->name === 'student';
    }

    public function isNonStudent(): bool
    {
        return $this->role?->name === 'non-student';
    }

    public function getIdentifierAttribute()
    {
        return $this->isStudent() ? $this->student_id : $this->qr_code;
    }
}
