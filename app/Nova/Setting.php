<?php

namespace App\Nova;

use NovaItemsField\Items;;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Currency;
use Laraning\NovaTimeField\TimeField;
use Laravel\Nova\Http\Requests\NovaRequest;
use DanielDeWit\NovaSingleRecordResource\Contracts\SingleRecordResourceInterface;
use DanielDeWit\NovaSingleRecordResource\Traits\SupportSingleRecordNavigationLinks;

class Setting extends Resource implements SingleRecordResourceInterface
{
    use SupportSingleRecordNavigationLinks;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Setting::class;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Other';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = '';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Trix::make('Terms And Conditions', 'terms_and_conditions')->nullable(),

            Currency::make('Minimum Offer Price', 'min_offer_price')
                ->currency('SAR')
                ->step(0.50)
                ->required(),

            Currency::make('Maximum Offer Price', 'max_offer_price')
                ->currency('SAR')
                ->step(0.50)
                ->required(),

            TimeField::make('Cancellation Time', 'cancellation_time'),

            Items::make('Delivery Time', 'delivery_time')
                ->inputType('text')
                ->placeholder('00:00')
                ->rules([
                     'delivery_time.*' => 'string|date_format:H:i',
                ])
                ->draggable(),
        ];
    }

    /**
     * Mark this resource as a single resource
     *
     * @return bool
     */
    public static function singleRecord(): bool
    {
        return true;
    }

    /**
     * Set the default record byID
     *
     * @return string|int
     */
    public static function singleRecordId()
    {
        return 1;
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
    }
}
