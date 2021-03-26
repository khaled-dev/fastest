<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name_ar', 'name_en'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function territory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    /**
     * Get all Cities with the given territory-id
     *
     * @param $query
     * @param $territory_id
     * @return mixed
     */
    public function scopeForTerritoryId($query, $territory_id)
    {
        return $query->where('territory_id', $territory_id);
    }
}
