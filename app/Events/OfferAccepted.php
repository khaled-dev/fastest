<?php

namespace App\Events;

use App\Models\Offer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class OfferAccepted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The offer instance.
     *
     * @var \App\Models\Offer
     */
    public $offer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }
}
