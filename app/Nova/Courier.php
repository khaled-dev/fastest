<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Courier extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Courier::class;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Users';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'mobile'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Name', 'name')->sortable(),

            Text::make('National ID', 'national_id')->sortable(),

            Text::make('Mobile Number', 'mobile')->sortable(),

            Select::make('Gender', 'gender')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                ])
                ->sortable(),

            Select::make('Is Active', 'is_active')
                ->options([
                    'false' => 'Not Active',
                    'true' => 'Active',
                ])
                ->sortable(),

            Select::make('Has Admin Approved', 'has_admin_approved')
                ->options([
                    'false' => 'Not Approved',
                    'true' => 'Approved',
                ])
                ->sortable(),

            Text::make('IBAN Nnumber', 'iban_number')->hideFromIndex(),

            Text::make('Car Number', 'car_number')->hideFromIndex(),

            BelongsTo::make('Nationality', 'nationality')->hideFromIndex(),

            BelongsTo::make('Bank', 'bank')->hideFromIndex(),

            BelongsTo::make('Territory', 'territory')->hideFromIndex(),

            BelongsTo::make('City', 'city')->hideFromIndex(),

            BelongsTo::make('Car Type', 'car_type')->hideFromIndex(),

        ];
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request): bool
    {
       return false;
    }
}
