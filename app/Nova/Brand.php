<?php

namespace App\Nova;

use App\Nova\Filters\ActiveInactive;
use Davidpiesse\NovaToggle\Toggle;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Session;

class Brand extends Resource
{
    use HasDependencies;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Brand';

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
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable(),
//            Select::make('Type')->options([
//                '1' => 'Exclusive',
//                '0' => 'Non Exclusive',
//            ])->displayUsingLabels()->sortable()->rules('required'),
            BelongsTo::make('countries')->withMeta([
                'belongsToId' => session('belongsToId')
            ]),
            Boolean::make('Active' ,'is_active')->sortable(),
            Boolean::make('Fabric','is_fabric')->sortable(),
            Textarea::make('Comments','comment'),
            Select::make('Price List Type','price_type')->options([
                'wholesale' => 'Wholesale',
                'retail_excluding' => 'Retail Excluding tax',
                'retail_including' => 'Retail Including Tax',
            ])->displayUsingLabels()->sortable()->rules('required'),

//            Toggle::make('Tax','tax')->trueValue(1)->falseValue(0)->showLabels()->trueLabel('Inc Tax')->falseLabel('Ex Tax')->width(80),
            NovaDependencyContainer::make([
                Text::make('Tax amount %','tax_amount')->help(
                    'The tax rate will be in percent'
                )
            ])->dependsOn('price_type','retail_including'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new ActiveInactive(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
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
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->where('country_id',  Session::get('belongsToId'));
        return $query;
    }
}
