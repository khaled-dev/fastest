<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourierUpdateRequest extends Model
{
    use HasFactory;

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
        'national_number',
        'gender',
        'car_number',
        'iban',
    ];

    /**
     * Get the courier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    /**
     * Get the territory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function territory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    /**
     * Get the city
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Get the nationality
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nationality(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    /**
     * Get the city_type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CarType::class);
    }
}
