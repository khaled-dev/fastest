<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Admin types
     */
    const NORMAL_ADMIN = 'admin';
    const SUPER_ADMIN  = 'super-admin';


    /**
     * Check if this user is super-admin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->type === self::SUPER_ADMIN;
    }


    /**
     * Check if this user is admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array($this->type, [self::NORMAL_ADMIN, self::SUPER_ADMIN]);
    }

    /**
     * Check if this user is the same as the given user.
     *
     * @param User $user
     * @return bool
     */
    public function isTheSame(User $user): bool
    {
        return $this->id === $user->id;
    }


}
