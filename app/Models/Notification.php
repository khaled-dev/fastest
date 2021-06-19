<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'body',
        'image_url',
    ];

    /**
     * mark this notification object as read.
     *
     * @return $this
     */
    public function markAsRead(): Notification
    {
        $this->read_at = now();
        $this->save();

        return $this;
    }

    /**
     * Get the notifiable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function notifiable()
    {
        return $this->morphTo();
    }
}
