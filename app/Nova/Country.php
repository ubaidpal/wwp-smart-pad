<?php

namespace App\Nova;

use App\Nova\Filters\ActiveInactive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use philperusse\NovaTooltipField\Tooltip;
use Laravel\Nova\Panel;
class Country extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Country';

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
     * Order by Criteria for Index Query
     *
     * @var array
     */
    public static $indexDefaultOrder = [
        'active' => 'desc',
        'id' => 'desc',
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
            ID::make()->hideFromIndex(),
            Text::make('Code', 'iso2_code')
                ->sortable()
                ->withMeta(['extraAttributes' => [
                    'readonly' => true
                ]]),
            Avatar::make('Flag', 'flag')->preview(function () {
                return $this->flag ?? null;
            })->thumbnail(function () {
                return $this->flag ?? null;
            })->exceptOnForms(),
            Text::make('Name' )->displayUsing(function ($value) {
                return str_limit($value, '8', '...');
            }),
            BelongsTo::make('Currency'),
            Select::make('Measure System', 'measure_system')
                ->options(Country::getMeasureSystemOptions())
                ->sortable(),
            Select::make('Currency Symbol', 'currency_symbol')
                  ->options(Country::getCurrencySymbol())
                  ->sortable(),
            Select::make('Date Format', 'date_format')
                  ->options(Country::getCountryDateFormat())
                  ->sortable(),
            Text::make('Tax')->help(
                'The tax rate to be applied to the sale'
            ),

            Boolean::make('Active')->sortable()

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
            new ActiveInactive()
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
}
