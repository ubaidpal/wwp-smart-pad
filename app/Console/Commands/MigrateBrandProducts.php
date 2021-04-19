<?php

namespace App\Console\Commands;

use App\Classes\ProductOptionJson;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Self_;

class MigrateBrandProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:brandproducts {brandId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*Remove Reset Tables Once One Brands Verify.*/
        //Reset Tables
        $this->info('Reset Tables.');
//        DB::statement("SET foreign_key_checks=0");
//        DB::table('products')->truncate();
//        DB::table('price_grids')->truncate();
//        DB::table('product_options')->truncate();
//        DB::table('product_option_selects')->truncate();
//        DB::table('product_option_constraints')->truncate();
//        DB::table('product_option_constraint_actions')->truncate();
//        DB::statement("SET foreign_key_checks=1");
        $this->info('------------------------');


        $this->info('Brand Products Importing Started...');
        //
        $brandId = $this->argument('brandId');

        DB::connection('mysql2')->table('ProductDetails')->where('BrandID',$brandId)->orderBy('ProductID')->chunk(100, function ($products) {

            foreach ($products as $product) {
                if($product->CatID > 0){

                    $brand_count = DB::table('brands')->where('id', $product->BrandID)->count();
                    $cat_count = DB::table('categories')->where('id', $product->CatID)->count();

                    if($brand_count > 0 && $cat_count > 0){

                        self::compileProduct($product);

                    }
                }
            }

        });

        /**/
//        DB::connection('mysql2')->table('Brand')->where('country_code','AU')->orderBy('BrandID')->chunk(100, function ($brands) {
//          foreach ($brands as $brand) {
//              $this->info('Brand ID'.$brand->BrandID);
//              $this->info('Brand Name'.$brand->Name);
//
//          }
//        });
        /**/
        //self::UpdateGridType();
        $this->info('Brand Products Importing End.');
    }

    public function country($country_code){

        if($country_code == 'UK'){
            $country_code = 'GB';
        }

        $country = DB::table('countries')->where('iso2_code', $country_code)->first();
        return $country;

    }

    public function compileProduct($product){

        //Product Basic Info
        $product_id = self::ProductBaseInfo($product,self::country($product->country_code));
        $this->info('Product ID '.$product->ProductID.' Basic Info Added. Started Price Grid...');

        //Product Price Grid
        self::ProductPriceGrid($product->ProductID,$product_id); //Old Id -- New Id
        $this->info('Product ID '.$product->ProductID.' Price Gird Added. Started Product Options...');

        //Product Options
        $IdsData = self::ProductOptions($product->ProductID,$product_id); //Old Id -- New Id

        //Sub Option Price Grid
        self::ProductSubOptionPriceGrid($IdsData['SubIds'],$product_id);

        //Product Option Constraints
        self::ProductOptionConstraints($IdsData);

        $jsonObj = ProductOptionJson::getOptionJson($product_id);
        DB::table('products')->where('id', $product_id)->update(['options' => json_encode($jsonObj) ]);

        $this->info('Ended Product Options.');

    }

    public function ProductBaseInfo($product,$country)
    {
        /**/
        $price_structure = '';
        if($product->price_grid_type == 'width'){
            $price_structure = 'width_based_chart_mt';
        }else if($product->price_grid_type == 'height'){
            $price_structure = 'height_based_chart_mt';
        }else if($product->price_grid_type == 'square_metre'){
            $price_structure = 'square_meter_rate';
        }else if($product->price_grid_type == 'width_height'){
            $price_structure = 'width_and_height_based_chart_mt';
        }
        /**/

        $product_id = DB::table('products')->insertGetId([
            'name' => stripslashes($product->Name),
            'brand_id' => $product->BrandID,
            'country_id' => $country->id,
            'category_id' => $product->CatID,
            'price_structure' => $price_structure,
            'square_meter_rate' => $product->square_metre_price,
            'is_workroom' => $product->is_workroom
        ]);

        return $product_id;
    }

    public function ProductPriceGrid($ProductID,$product_id)
    {
        $grid_json = array();
        DB::connection('mysql2')->table('BookCharts')->orderBy('BookChartsID')->where('ProductID',$ProductID)->chunk(100, function ($price_grid) use ($product_id,&$grid_json) {
            $data    = [];
            foreach ($price_grid as $grid){
                $data[] = [
                    'width' => $grid->Width,
                    'height' => $grid->Height,
                    'price' => $grid->Price,
                    'product_id' => $product_id
                ];
                $grid_json[] = (Object)[
                    'width' => $grid->Width,
                    'height' => $grid->Height,
                    'price' => $grid->Price,
                ];
            }
            DB::table('price_grids')->insert($data);

        });
        DB::table('products')->where('id', $product_id)->update(['price_grid' => json_encode($grid_json) ]);
        return true;
    }

    public function ProductOptions($ProductID,$product_id)
    {
        $Ids = Collect();
        $SubIds = Collect();
        //product_page_show
        DB::connection('mysql2')->table('OptionLabel')->orderBy('OptionLabelID')->where('ProductID',$ProductID)->where('parent_id',0)->chunk(100, function ($options) use ($product_id,&$Ids,&$SubIds) {
            foreach ($options as $option){

                if($option->Name == 'Mount'){
                    continue;
                }

                $hide = array();
                if($option->install_sheet_hide_details == 1){
                    $hide[] = (object) array('name' => 'Hide On Install & Measure','id' => 'I');
                }else if ($option->completely_hide == 1){
                    $hide[] = (object) array('name' => 'Hide On Screen','id' => 'S');
                }else if($option->hide_on_order == 1){
                    $hide[] = (object) array('name' => 'Hide On Order','id' => 'O');
                }else if($option->show_in_quote == 1){
                    $hide[] = (object) array('name' => 'Hide On Quote','id' => 'Q');
                }

                $option_id = DB::table('product_options')->insertGetId([
                    'name' => $option->Name,
                    'type' => $option->type,
                    'hide_on' => json_encode($hide),
                    'is_fabric_cuts' => $option->is_fabric_cuts,
                    'is_quantity' => $option->is_quantity,
                    'is_dollar_value' => $option->is_price_value,
                    'connected_to' => $option->connected_to,
                    'calculation' => $option->calculation,
                    'order' => $option->ColorSeq,
                    'product_id' => $product_id
                ]);
                $OptionLabelID = $option->OptionLabelID;
                $Ids->push((object) array('old_id' => $OptionLabelID, 'new_id' => $option_id));
                if($option->type == 'group'){
                    $data = self::ProductOptionGroup($OptionLabelID,$product_id,$option_id,$Ids,$SubIds);
                    $Ids = $data['Ids'];
                    $SubIds = $data['SubIds'];
                } else {
                    $SubIds = self::ProductSubOptions($OptionLabelID,$option_id,$SubIds,$product_id);
                }

            }
        });
        $data['Ids'] = $Ids;
        $data['SubIds'] = $SubIds;
        return $data;
    }

    public function ProductOptionGroup($old_parent_id,$product_id,$new_parent_id,$Ids,$SubIds)
    {
        DB::connection('mysql2')->table('OptionLabel')->orderBy('OptionLabelID')->where('parent_id',$old_parent_id)->chunk(100, function ($options) use ($product_id,$new_parent_id,&$Ids,&$SubIds) {
            foreach ($options as $option){

                $hide = array();
                if($option->install_sheet_hide_details == 1){
                    $hide[] = (object) array('name' => 'Hide On Install & Measure','id' => 'I');
                }else if ($option->completely_hide == 1){
                    $hide[] = (object) array('name' => 'Hide On Screen','id' => 'S');
                }else if($option->hide_on_order == 1){
                    $hide[] = (object) array('name' => 'Hide On Order','id' => 'O');
                }else if($option->show_in_quote == 1){
                    $hide[] = (object) array('name' => 'Hide On Quote','id' => 'Q');
                }

                $option_id = DB::table('product_options')->insertGetId([
                    'name' => $option->Name,
                    'type' => $option->type,
                    'hide_on' => json_encode($hide),
                    'is_fabric_cuts' => $option->is_fabric_cuts,
                    'is_quantity' => $option->is_quantity,
                    'is_dollar_value' => $option->is_price_value,
                    'connected_to' => $option->connected_to,
                    'calculation' => $option->calculation,
                    'order' => $option->ColorSeq,
                    'product_option_id' => $new_parent_id,
                    'product_id' => $product_id
                ]);
                $OptionLabelID = $option->OptionLabelID;
                $Ids->push((object) array('old_id' => $OptionLabelID, 'new_id' => $option_id));
                $SubIds = self::ProductSubOptions($OptionLabelID,$option_id,$SubIds,$product_id);

            }
        });
        $data['Ids'] = $Ids;
        $data['SubIds'] = $SubIds;
        return $data;
    }

    public function ProductSubOptions($OptionLabelID,$option_id,$SubIds,$product_id)
    {
        DB::connection('mysql2')->table('OptionPicklist')->orderBy('OptionPicklistID')->where('OptionLabelID',$OptionLabelID)->chunk(100, function ($suboptions) use ($option_id,&$SubIds,$product_id) {
            //
            foreach ($suboptions as $sub){

                $price_structure = '';
                if($sub->PriceStructure == 'Free'){
                    $price_structure = 'free';
                } else if($sub->PriceStructure == 'Flat' || $sub->PriceStructure == 'FlatList' || $sub->PriceStructure == 'FlatNet'){
                    $price_structure = 'flat_price';
                } else if ($sub->PriceStructure == 'per_metre'){
                    $price_structure = 'per_metre_width';
                } else if ($sub->PriceStructure == 'grid_option_price'){
                    $price_structure = 'width_and_height_based_chart_mt';
                } else if ($sub->PriceStructure == 'Percent'){
                    $price_structure = 'percent_added_to_base';
                } else if ($sub->PriceStructure == 'Width'){
                    $price_structure = 'width_based_chart_mt';
                } else if ($sub->PriceStructure == 'frame_add_sm'){
                    $price_structure = 'square_meter_rate';
                }



                /**/
                $value = null;
                if($sub->price_value){
                    $value = $sub->price_value;
                }
                if($sub->price_percent){
                    $value = $sub->price_percent;
                }
                /**/

                $sub_option_id = DB::table('product_option_selects')->insertGetId([
                    'name' => addslashes($sub->Name),
                    'price_structure' => $price_structure,
                    'has_quantity' => $sub->QuantityStatus,
                    'is_discountable' => $sub->applyDiscount,
                    'value' => $value,
                    'is_default' => $sub->set_default,
                    'product_option_id' => $option_id
                ]);

                if($sub->PriceStructure == 'Width'){
                    self::ProductSubOptionWidthPriceGrid($sub->OptionPicklistID,$sub_option_id,$product_id);
                }

                $SubIds->push((object) array('old_id' => $sub->OptionPicklistID, 'new_id' => $sub_option_id));
            }

        });
        return $SubIds;
    }

    public function UpdateGridType()
    {
        DB::table('products')->where('price_structure','square_metre')->update(['price_structure' => 'square_meter_rate']);
        DB::table('products')->where('price_structure', 'width_height')->update(['price_structure' => 'width_and_height_based_chart_mt']);
        DB::table('products')->where('price_structure', 'width')->update(['price_structure' => 'width_based_chart_mt']);
        DB::table('products')->where('price_structure', 'height')->update(['price_structure' => 'height_based_chart_mt']);
        return true;

    }

    public function ProductOptionConstraints($IdsData)
    {
        $this->info('Product Option Constraints');
        foreach ($IdsData['Ids'] as $option){

            $counter = DB::connection('mysql2')->table('actions')->where('option_id',$option->old_id)->count();

            if($counter > 0){

                DB::connection('mysql2')->table('actions')->orderBy('id')->where('option_id',$option->old_id)->chunk(100, function ($actions) use ($option,$IdsData){
                    foreach ($actions as $action){
                        //dd($action->id.'---'.$action->event_type.'***'.$action->action_type);
                        $event_type = 1;
                        $action_type = 1;
                        $constraint_id = DB::table('product_option_constraints')->insertGetId([
                            'event_type' => $event_type,
                            'action_type' => $action_type,
                            'product_option_id' => $option->new_id
                        ]);
                        $this->info('constraint id '.$constraint_id);
                        if($constraint_id > 0){
                            self::ProductOptionConstraintDetail($action->id,$IdsData,$constraint_id);
                        }

                    }
                });
            }
        }
    }

    public function ProductOptionConstraintDetail($old_id,$IdsData,$constraint_id)
    {
        $this->info('Product Option Constraint Detail');
        DB::connection('mysql2')->table('actions_details')->orderBy('id')->where('action_id',$old_id)->chunk(100, function ($actions) use ($constraint_id,$IdsData){

            foreach ($actions as $event){
                //$this->info('event data '.\GuzzleHttp\json_encode($event));
//                if($event->event_value_type == 'Sub Option' && $event->action_value_type == 'Option Group'){
                $optionInfo = $IdsData['Ids']->where('old_id',$event->action_value)->toArray();
                //$this->info('option data '.\GuzzleHttp\json_encode($optionInfo));
                $subOptionInfo = $IdsData['SubIds']->where('old_id',$event->event_value)->toArray();
                //$this->info('sub option data '.\GuzzleHttp\json_encode($subOptionInfo));
                if(isset($subOptionInfo[key($subOptionInfo)]) && isset($optionInfo[key($optionInfo)])){
                    DB::table('product_option_constraint_actions')->insertGetId([
                        'sub_option_id' => $subOptionInfo[key($subOptionInfo)]->new_id,
                        'option_id' => $optionInfo[key($optionInfo)]->new_id,
                        'option_constraint_id' => $constraint_id
                    ]);
                }

//                }

            }

        });

    }

    public function ProductSubOptionPriceGrid($subOptions,$product_id)
    {
        foreach ($subOptions as $sub){

            $data = DB::connection('mysql2')->table('OptionPicklist')->where('OptionLabelID',$sub->old_id)->get();
            if($data->count() > 0 && $data->OptionLabelID != 'Width'){
                $price_grid = DB::connection('mysql2')->table('option_price_grid')->where('color_id',$sub->old_id)->get();
                if($price_grid->count() > 0){
                    $data    = [];
                    foreach ($price_grid as $grid){
                        $data[] = [
                            'width' => $grid->width,
                            'height' => $grid->height,
                            'price' => $grid->price,
                            'product_id' => $product_id,
                            'product_sub_option_id' => $sub->new_id
                        ];
                    }
                    DB::table('price_grids')->insert($data);
                }
            }
        }
        return true;
    }

    public function ProductSubOptionWidthPriceGrid($old_id,$new_id,$product_id)
    {
        $price_grid = DB::connection('mysql2')->table('OptionPrice')
        ->join('WidthSlots', 'OptionPrice.WidthSlotsID', '=', 'WidthSlots.WidthSlotsID')
        ->where('OptionPrice.OptionPicklistID',$old_id)
        ->get();
        if($price_grid->count() > 0){
            $data    = [];
            foreach ($price_grid as $grid){
                $data[] = [
                    'width' => $grid->Slot,
                    'price' => $grid->Price,
                    'product_id' => $product_id,
                    'product_sub_option_id' => $new_id
                ];
            }
            DB::table('price_grids')->insert($data);
        }
        return true;

    }

}
