<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

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

            Images::make( 'Profile Picture', 'profile_picture'),

            Text::make('National ID', 'national_number')->sortable(),

            Text::make('Mobile Number', 'mobile')->sortable(),

            Select::make('Gender', 'gender')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                ])
                ->displayUsingLabels()
                ->sortable(),

            Select::make('Is Active', 'is_active')
                ->options([
                    '0' => 'Not Active',
                    '1' => 'Active',
                ])
                ->displayUsingLabels()
                ->sortable(),

            Select::make('Has Admin Approved', 'has_admin_approved')
                ->options([
                    '0' => 'Not Approved',
                    '1' => 'Approved',
                ])
                ->displayUsingLabels()
                ->sortable(),

            Text::make('IBAN Nnumber', 'iban_number')->hideFromIndex(),

            Text::make('Car Number', 'car_number')->hideFromIndex(),

            BelongsTo::make('Nationality', 'nationality')->hideFromIndex(),

            BelongsTo::make('Bank', 'bank')->hideFromIndex(),

            BelongsTo::make('Territory', 'territory')->hideFromIndex(),

            BelongsTo::make('City', 'city')->hideFromIndex(),

            BelongsTo::make('Car Type', 'car_type')->hideFromIndex(),

            Images::make('National Card Picture', 'national_card_picture')
                ->hideFromIndex(),

            Images::make('Car License Picture', 'car_license_picture')
                ->hideFromIndex(),

            Images::make('Driving License Picture', 'driving_license_picture')
                ->hideFromIndex(),
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
