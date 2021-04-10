<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Actions\ApproveCourierUpdate;

class CourierUpdateRequest extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\CourierUpdateRequest::class;

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
        'id',
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
            BelongsTo::make('Courier', 'courier')
                ->showOnIndex()
                ->sortable(),

            Text::make('Name', 'name')->sortable(),

            Text::make('National ID', 'national_number')->sortable(),

            Select::make('Gender', 'gender')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                ])
                ->displayUsingLabels()
                ->sortable(),

            Text::make('IBAN Nnumber', 'iban')->hideFromIndex(),

            Text::make('Car Number', 'car_number')->hideFromIndex(),

            BelongsTo::make('Nationality', 'nationality')->hideFromIndex(),

            BelongsTo::make('Bank', 'bank')->hideFromIndex(),

            BelongsTo::make('Territory', 'territory')->hideFromIndex(),

            BelongsTo::make('City', 'city'),

            BelongsTo::make('Car Type', 'car_type')->hideFromIndex(),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new ApproveCourierUpdate())->canRun(function ($request, $model) {
                return true;
            }),
        ];
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

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }
}
