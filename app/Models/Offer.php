<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'price',
        'delivery_time',
    ];

    /**
     * Offer states
     */
    const UNDER_NEGOTIATION = 'under_negotiation';
    const ACCEPTED = 'accepted';
    const COMPLETED = 'completed';
    const CANCELED = 'canceled';

}
