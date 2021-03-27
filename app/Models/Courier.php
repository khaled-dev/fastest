<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Courier extends Model
{
    use HasFactory, HasApiTokens, Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'territory_id',
        'city_id',
        'car_type_id',
        'nationality_id',
        'bank_id',
        'name',
        'mobile',
        'national_number',
        'gender',
        'car_number',
        'iban_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'bool',
        'has_admin_approved' => 'bool',
        'created_at' => 'datetime',
    ];
}
