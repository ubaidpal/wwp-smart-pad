<?php

namespace App\Nova;

use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outhebox\NovaHiddenField\HiddenField;
use Illuminate\Support\Facades\Session;
class Help extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Help';

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
        'id','name'
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
            Text::make('Name', 'name')->rules('required')->creationRules('unique:help,name'),
            Trix::make('Content', 'content')->rules('required'),
            Number::make('Order', 'order')->min(1),
            HiddenField::make('help_group_id')->withMeta(['value'=> session('help_group_id')]),
            /*BelongsTo::make('Help', 'help', 'App\Nova\DashboardSettings')->withMeta([
                'belongsToId' => session('help_group_id')
            ]),*/

        ];
    }

 /*   public static function singularLabel()
    {
        return __('title');

    }*/
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
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->where('help_group_id',  Session::get('help_group_id'));
        return $query;
    }
}
