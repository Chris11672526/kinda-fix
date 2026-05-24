<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User model
 * ─────────────────────────────────────────────────────────────────────────────
 * Extends Authenticatable so Laravel's Auth system works (login, sessions).
 * Authenticatable is NOT the same as Eloquent Model for query purposes —
 * it only provides password hashing, remember tokens, and session binding.
 *
 * IMPORTANT: We do NOT define any Eloquent relationships (hasOne, belongsTo etc.)
 * All data fetching is done via DB::table() in the controllers.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'branch_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── NO relationships defined here ──────────────────────────────────────
    // Do NOT add: role(), customer(), branch() Eloquent relationships.
    // Use DB::table() joins in controllers instead.
}
