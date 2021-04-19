<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Arsenaltech\NovaTab\NovaTab;
use Laravel\Nova\Panel;
use Illuminate\Support\Facades\Session;
class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Product';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name'
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
            ID::make()->sortable(),
            Text::make('Name')->sortable()->rules('required', 'max:255'),
            BelongsTo::make('Brand', 'brands', 'App\Nova\Brand')->sortable(),
            BelongsTo::make('Category')->sortable(),
            BelongsTo::make('Country', 'countries', 'App\Nova\Country')->withMeta([
                'belongsToId' => session('belongsToId')
            ]),
            Boolean::make('Active' , 'is_active')
        ];
    }

    /**
     * Get decesion the selected country on .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function countryFields()
    {
        return [
            BelongsTo::make('Country'),
            BelongsTo::make('Brand'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public static function relatableCountries(NovaRequest $request, $query)
    {
        return $query->where('active',1);
    }

    public static function relatableBrands(NovaRequest $request, $query)
    {
        return $query->where('is_fabric' , 0);
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->whereHas('brands', function ($query) {
            $query->where('country_id', Session::get('belongsToId'));
        });
        return $query;
    }

}
