<?php

namespace App\Nova;

use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outhebox\NovaHiddenField\HiddenField;
use Timothyasp\Color\Color;
class NotificationSettings extends Resource
{
    use HasDependencies;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Notification';

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
            Text::make('Name', 'name')->rules('required')->creationRules('unique:notifications,name'),
            Trix::make('Content', 'content')->rules('required'),
            Color::make("Text Color","text_color")->rules('required'),
            Color::make("Background Color" , "background_color")->rules('required'),
//            Select::make('Choose to notify','notify_type')->options([
//                'all_dealers' => 'All Dealers',
//                'specific_dealers' => 'Specific Dealers',
//                'specific_country' => 'Dealers for  specific country',
//            ])->displayUsingLabels()->sortable()->rules('required'),
//
//            NovaDependencyContainer::make([
//                BelongsTo::make('Country', 'countries', 'App\Nova\Country')->withMeta([
//                    'belongsToId' => session('belongsToId')
//                ]),
//            ])->dependsOn('notify_type','specific_country'),
//
//            NovaDependencyContainer::make([
//                BelongsTo::make('Dealer', 'dealers', 'App\Nova\Dealer')
//            ])->dependsOn('notify_type','specific_dealers'),

            Boolean::make('Active'),


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
}
