<?php


namespace App\Models\Concerns;


trait General
{
    /**
     * Order Desc.
     *
     * @param $qeury
     * @return mixed
     */
    public function scopeDesc($qeury)
    {
        return $qeury->orderBy('id', 'DESC');
    }
}
