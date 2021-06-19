<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationToken extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['token'];

    /**
     * Get thee owner of this token.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function resource()
    {
        return $this->morphTo();
    }
}
