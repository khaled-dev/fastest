<?php


namespace App\Nova\Fields;

use Laravel\Nova\Http\Requests\NovaRequest;
use Manmohanjit\BelongsToDependency\BelongsToDependency AS BaseBelongsToDependency;

class BelongsToDependency extends BaseBelongsToDependency
{
    /**
     * Build an associatable query for the field.
     * Here is where we add the depends on value and filter results
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  bool  $withTrashed
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buildAssociatableQuery(NovaRequest $request, $withTrashed = false)
    {
        $query = parent::buildAssociatableQuery($request, $withTrashed);

        if($request->has('dependsOnValue')) {
            $query->toBase()->where($this->meta['dependsOnKey'], $request->dependsOnValue);
        }

        return $query;
    }
}
