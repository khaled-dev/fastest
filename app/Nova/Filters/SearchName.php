<?php


namespace App\Nova\Filters;


trait SearchName
{
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name_ar',
        'name_en',
    ];
}
