<?php
/**
 * Created by   :  Ubaid UllAh
 * Project Name : SmartPad
 * Product Name : PhpStorm
 * Date         : 05/28/2019 2:32 PM
 * File Name    : ProductOptionJson.php
 */

namespace App\Classes;

use App\Models\OptionConstraintActions;
use App\Models\PriceGrid;
use App\Models\ProductOptionConstraints;
use App\Models\ProductOptions;
use App\Models\ProductOptionSelects;

class ProductOptionJson
{
    // types that can have a grid based chart
    public static $gridBasedPriceStructures = [
        'width_based_chart_mt',
        'height_based_chart_mt',
        'width_and_height_based_chart_mt',
        'square_meter_rate',
        'width_based_chart_ft',
        'height_based_chart_ft',
        'width_and_height_based_chart_ft',
        'square_feet_rate',
        'width_based_chart_in',
        'height_based_chart_in',
        'width_and_height_based_chart_in',
        'square_inche_rate',
    ];

    /*
     * Get main options Json
     * If Product Id is present then it will get options of a product
     * If Option Id is present then it will get sub options of a group option
     * @param product id
     * @param option id
     * @top parent id ( for the case of group)
     * */
    public static function getOptionJson($product_id, $option_id = 0, $top_parent_id = 0) {

        try {
            $options = [];
            // check whether it is for product or for options
            if($option_id > 0) {
                $options = ProductOptions::where('product_option_id', $option_id)->orderBy('order', 'ASC')->get();
            } else if($product_id > 0) {
                $options = ProductOptions::where('product_id', $product_id)->where('product_option_id', '0')->orderBy('order', 'ASC')->get();
            }
            $optionsJson = [];
            foreach ($options as $option) {
                $data         = [];
                $data[ 'id' ] = $option->id;
                //if it is for sub options of group then they must have parent and top parent
                if($option_id > 0) {
                    $data[ 'parent_id' ]     = $option_id;
                    $data[ 'top_parent_id' ] = $top_parent_id;
                }

                $data[ 'options_name' ]     = $option->name;
                $data[ 'type' ]             = $option->type;
                $data[ 'secret' ]           = $option->hide_on;
                $data[ 'connectedTo' ]      = $option->connected_to;
                $data[ 'isDollarValue' ]    = $option->is_dollar_value;
                $data[ 'isFabricCuts' ]     = $option->is_fabric_cuts;
                $data[ 'isQuantity' ]       = $option->is_quantity;
                $data[ 'calculation' ]      = $option->calculation ?: '';
                $data[ 'is_selected' ]      = FALSE;
                $data[ 'linked_sub_count' ] = self::getLinkedCount($option->id);

                if($option->type != 'group') {
                    // If type is not group then we need to decide about the top parent id that it is a product option or group sub option
                    $top_parent_id         = $top_parent_id > 0 ? $top_parent_id : $option->id;
                    $data[ 'constraints' ] = self::getConstraints($option->id);
                    $data[ 'sub_options' ] = self::getSubOptionJson($option->id, 0, $top_parent_id);
                } else {
                    $data[ 'linked_sub_id' ] = self::getLinkedSubId($option->id);
                    // It will return the group sub options
                    $data[ 'sub_options' ] = self::getOptionJson(0, $option->id, $option->id);
                }
                $optionsJson[] = $data;
            }
            return $optionsJson;

        } catch (\Exception $e) {
            dd($e->getTrace());
        }

    }

    /*
     * Get Sub Options Json
     * if the option id is present then it will get sub options of an option
     * if the option id is present then it will get sub options of a sub option
     * @param Option id
     * @param Sub Option Id
     * @param top parent id
     * */
    public static function getSubOptionJson($option_id, $sub_option_id, $top_parent_id) {
        $subOptions = [];
        if($option_id > 0) {
            $subOptions = ProductOptionSelects::where('product_option_id', $option_id)->where('option_select_id', 0)->get();
        } else if($sub_option_id > 0) {
            $subOptions = ProductOptionSelects::where('option_select_id', $sub_option_id)->get();
        }
        $subOptionsJson = [];
        foreach ($subOptions as $option) {
            $data                      = [];
            $data[ 'id' ]              = $option->id;
            $data[ 'parent_id' ]       = $option_id > 0 ? $option_id : $sub_option_id;
            $data[ 'top_parent_id' ]   = $top_parent_id;
            $data[ 'sub_option_name' ] = $option->name;
            $data[ 'price_structure' ] = $option->price_structure;
            if(in_array($option->price_structure, self::$gridBasedPriceStructures)) {
                $data[ 'price_grid' ] = json_encode(self::getPriceGrid($option->id));
            }
            $data[ 'quantity_status' ]  = $option->has_quantity;
            $data[ 'apply_discount' ]   = $option->is_discountable;
            $data[ 'price_percent' ]    = $option->value ?: '';
            $data[ 'price_type' ]       = $option->price_type;
            $data[ 'mark_default' ]     = $option->is_default;
            $data[ 'linked_option_id' ] = $option->linked_option_id;
            $data[ 'linked_group_id' ] = self::getLinkedGroupId($option->id);
            $data[ 'is_selected' ]      = FALSE;
            $data[ 'sub_options' ]      = self::getSubOptionJson(0, $option->id, $top_parent_id);
            $subOptionsJson[]           = $data;
        }
        return $subOptionsJson;
    }

    /*
     * It will return the count of options that are linked to an option
     * @param $option_id
     * */
    public static function getLinkedCount($option_id) {
        $count = ProductOptionSelects::where('linked_option_id', $option_id)->count();
        return $count;
    }

    /*
     * Get Price Grid for an Option
     * */
    public static function getPriceGrid($option_id) {
        $grid     = PriceGrid::where('product_sub_option_id', $option_id)->get();
        $gridJson = [];
        foreach ($grid as $row) {
            $data = [];
            if($row->width >= 0 && $row->width != NULL) {
                $data[ 'width' ] = $row->width;
            }
            if($row->height >= 0 && $row->height != NULL) {
                $data[ 'height' ] = $row->height;
            }
            $data[ 'price' ] = $row->price;
            $gridJson[]      = $data;
        }
        return $gridJson;
    }

    /*
     * Get the constraints related to a particular option
     * */
    public static function getConstraints($option_id) {
        $constraints     = ProductOptionConstraints::where('product_option_id', $option_id)->get();
        $constraintsJSON = [];
        foreach ($constraints as $constraint) {
            $data                 = [];
            $data[ 'id' ]         = $constraint->id;
            $data[ 'if' ]         = $constraint->event_type;
            $data[ 'then' ]       = $constraint->action_type;
            $data[ 'conditions' ] = [];
            $conditions           = OptionConstraintActions::where('option_constraint_id', $constraint->id)->get();
            foreach ($conditions as $condition) {
                $dataCondition                   = [];
                $dataCondition[ 'id' ]           = $condition[ 'id' ];
                $dataCondition[ 'sub_option' ]   = $condition[ 'sub_option_id' ];
                $dataCondition[ 'option_label' ] = $condition[ 'option_id' ];

                $data[ 'conditions' ][] = $dataCondition;
            }
            $constraintsJSON[] = $data;
        }
        return $constraintsJSON;
    }

    /*
     * Get linked sub id
     * In the case of group check if the group is linked to any other option
     * */
    public static function getLinkedSubId($group_id) {
        $constraintAction = OptionConstraintActions::where('option_id', $group_id)->first();
        if($constraintAction) {
            $subOption  = ProductOptionSelects::where('id', $constraintAction->sub_option_id)->first();
            if(isset($subOption->product_option_id)){
                $mainOption = ProductOptions::where('id', $subOption->product_option_id)->first();
                return $mainOption->id;
            } else {
                return 0;
            }

        }
        return 0;
    }

    /*
     * It will return the option id
     * @param $sub_option_id
     * */
    public static function getLinkedGroupId($sub_option_id) {

        $linked_group_id = 0;
        $data = OptionConstraintActions::where('sub_option_id', $sub_option_id)->first();
        if($data){
            $linked_group_id = $data->option_id;
        }
        return $linked_group_id;
    }
}
