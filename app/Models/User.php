<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'role',
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
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function attendances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Consistent-per-user avatar gradient, hashed from the user id so the
     * same person always gets the same colour combination in the UI.
     */
    public function avatarGradient(): string
    {
        $palette = [
            'from-sky-600 to-indigo-600',
            'from-blue-600 to-cyan-600',
            'from-amber-600 to-orange-500',
            'from-emerald-600 to-teal-600',
            'from-fuchsia-600 to-purple-600',
            'from-rose-600 to-pink-600',
            'from-violet-600 to-blue-600',
            'from-teal-600 to-emerald-500',
        ];

        return $palette[$this->id % count($palette)];
    }
}
