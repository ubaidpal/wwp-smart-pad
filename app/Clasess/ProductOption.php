<?php

namespace App\Classes;

// set and get meta
use App\Models\OptionConstraintActions;
use App\Models\PriceGrid;
use App\Models\Product;
use App\Models\ProductOptionConstraints;
use App\Models\ProductOptions;
use App\Models\ProductOptionSelects;
use function foo\func;
use Illuminate\Support\Facades\Config;
use phpDocumentor\Reflection\Types\Self_;
use DB;

class ProductOption
{
    private static $product_id;
    private static $product_options        = [];
    private static $product_option_selects = [];
    private static $product_option_meta    = [];
    private static $order                  = 1;
    private static $optionIds              = [];
    private static $connectedTos           = [];
    private static $data_dump              = FALSE; // if data is dumping
    // get Production Method

    /**
     * @param $product_id
     * @param null $option_meta
     *
     * @return bool
     */
    public static function getProductOption($product_id, $option_meta = NULL) {

        if(isset($product_id)) {
            try {
                Self::$product_id = $product_id;
                // save json in product table
                $product          = Product::find($product_id);
                $product->options = $option_meta;
                $isProductSaved   = $product->save();
                // return 1;

                if($isProductSaved) {
                    $option_meta_arrays        = json_decode($option_meta, TRUE);
                    self::$product_option_meta = $option_meta_arrays;
                    $DB                        = array();
                    if(!empty(self::$product_option_meta)) {
                        foreach (self::$product_option_meta as $index => $option_meta_array) {
                            $data = array();
                            switch ($option_meta_array[ 'type' ]) { //Switch to make decision of option types
                                case Config::get('constants_settings.OPTION'):
                                    $data = self::saveOption($product_id, $option_meta_array, Config::get('constants_settings.OPTION'));
                                    break;
                                case Config::get('constants_settings.BasePrice'):
                                    $data = self::saveOption($product_id, $option_meta_array, Config::get('constants_settings.BasePrice'));
                                    break;
                                case Config::get('constants_settings.Multi'):
                                    $data = self::saveOption($product_id, $option_meta_array, Config::get('constants_settings.Multi'));
                                    break;
                                case Config::get('constants_settings.Groups'):
                                    $data = self::saveOption($product_id, $option_meta_array, Config::get('constants_settings.Groups'));
                                    break;
                                default:
                                    $data = self::saveOption($product_id, $option_meta_array, $option_meta_array[ 'type' ]);
                                    break;
                            }

                            $Ids                               = array();
                            $Ids[ $option_meta_array[ 'id' ] ] = $data[ 'id' ];
                            array_push(Self::$optionIds, $Ids);

                            $option_meta_array[ 'id' ] = $data[ 'id' ];
                            if(isset($option_meta_array[ 'sub_options' ]) && isset($data[ 'sub_options' ])) {
                                $option_meta_array[ 'sub_options' ] = $data[ 'sub_options' ];
//                                foreach ($data[ 'optionIds' ] as $opt) {
//                                    array_push(Self::$optionIds, $opt);
//                                }
                            }
                            array_push($DB, $option_meta_array);
                            Self::$order++;
                        }

                        Self::UpdateConnectTo();

                        /**/
                        $finalOptions = $DB;
                        foreach (Self::$optionIds as $newId) {
                            $objJson      = json_encode($finalOptions);
//                            $regex = '/\<[\'"]' . key($newId) . '["\']\>/';
                            //$regex        = "/\<" . key($newId) . "\>/";
//                            $objArr       = preg_replace($regex, $newId[ key($newId) ], $objJson);
                            $objArr       = str_replace(key($newId), $newId[ key($newId) ], $objJson);
                            $finalOptions = json_decode($objArr, TRUE);
                        }
                        /**/
                        //Save Option Constraints
                        $Options = self::saveOptionConstraints($finalOptions);
                        // save json in product table
                        //$product_options = ProductOptions::select('*','name as options_name')->where('product_id',$product_id)->get();
                        $product          = Product::find($product_id);
                        $product->options = \GuzzleHttp\json_encode($Options);
                        $product->save();
                        Self::deleteUnneededOptions();
                        return Self::$optionIds;
                    }
                }

            } catch (\Exception $e) {
                return $e->getLine() . '--getProductOption--' . $e->getMessage();
            }

        } else {
            return FALSE;
        }

    }

    /*
     * save option
     * @param Product Id
     * @param order
     * @param null type
     * */
    public static function saveOption($product_id, $options, $type = NULL) {

        try {
            $isFound = FALSE;
            // Check whether the option id exist in database or do we need to create a new Entry in the database
            if(isset($options[ 'id' ]) && $options[ 'id' ] > 0) {
                $product_options = ProductOptions::where('id', $options[ 'id' ])->where('product_id', $product_id)->first();
            }

            if(!isset($product_options->id)) {
                $product_options = new ProductOptions();

                if(isset($options[ 'isFabricCuts' ]) && $options[ 'isFabricCuts' ] > 0) {
                    $product_options->is_fabric_cuts = $options[ 'isFabricCuts' ];
                }
                if(isset($options[ 'isQuantity' ]) && $options[ 'isQuantity' ] > 0) {
                    $product_options->is_quantity = $options[ 'isQuantity' ];
                }
                if(isset($options[ 'connectedTo' ])) {
                    if(!is_numeric($options[ 'connectedTo' ])) {
                        $isFound = TRUE;
                    } else {
                        if($options[ 'connectedTo' ] != null){
                            $product_options->connected_to = $options[ 'connectedTo' ];
                        } else {
                            $product_options->connected_to = 0;
                        }
                    }
                }
                if(isset($options[ 'calculation' ]) && $options[ 'calculation' ] != "") {
                    $product_options->calculation = $options[ 'calculation' ];
                }

            }
            //save the basic data of option
            $product_options->name              = $options[ 'options_name' ];
            $product_options->type              = $options[ 'type' ];
            $product_options->order             = Self::$order;
            $product_options->hide_on           = json_encode($options[ 'secret' ]);
            $product_options->product_id        = $product_id;
            $product_options->product_option_id = 0;
            $product_options->save();
            // Enter Product Options in a static array
            if($isFound) {
                Self::$connectedTos[] = array($product_options->id => $options[ 'connectedTo' ]);
            }
            Self::$product_options[] = $product_options->id;

            $options[ 'calculation' ]  = $product_options[ 'calculation' ];
            $options[ 'connectedTo' ]  = $product_options[ 'connected_to' ];
            $options[ 'isFabricCuts' ] = $product_options[ 'is_fabric_cuts' ];
            $options[ 'isQuantity' ]   = $product_options[ 'is_quantity' ];
            //Check if Sub Options Exist or not
            $data[ 'id' ] = $product_options->id;
            if(isset($options[ 'sub_options' ]) && count($options[ 'sub_options' ]) > 0) {
                $response_sub_data     = self::saveSubData($product_id, $product_options->id, $type, $options[ 'sub_options' ]);
                $data[ 'sub_options' ] = $response_sub_data[ 'sub_options' ];
                $data[ 'optionIds' ]   = $response_sub_data[ 'optionIds' ];
            }

            // Update the Order Window Products
            $orderWindowProductIds = DB::table('order_window_products')->select('order_window_products.*')
                                       ->leftJoin('orders', 'orders.id', '=', 'order_window_products.order_id')
                                       ->where('order_window_products.product_id', $product_id)
                                       ->where('orders.is_quote', 1)
                                       ->whereNull('order_window_products.deleted_at')
                                       ->get()
                                       ->pluck('id')->toArray();

            DB::table('order_window_products')
              ->whereIn('id', $orderWindowProductIds)
              ->update(['has_updates' => TRUE]);

            return $data;

        } catch (\Exception $e) {
            dd($e->getMessage() . ' saveOption2', $e->getLine());
        }

    }

    /*
     * Save All Sub Options
     * @param Product Id
     * @param Product Option Id
     * @param Option Type on which we will take decision that which sort of sub options we are gonna make
     * @param options
     * */
    public static function saveSubData($product_id, $product_option_id, $type, $options) {
        try {
            // there are options some options in which there is no sub option
            // There are some types (options, finishes, base price and multi select) which have different sort of sub options
            // While Groups has different sort of sub options

            if(!empty($options) && $type != Config::get('constants_settings.Groups')) {
                return self::saveSubOption($product_option_id, $options); // save sub Option
            } else if(!empty($options) && $type == Config::get('constants_settings.Groups')) {
                return self::saveGroupOptions($product_id, $product_option_id, $options); // save options under group
            }

        } catch (\Exception $e) {
            dd($e->getMessage() . 'saveSubData', $e->getLine());
        }

    }

    public static function saveGroupOptions($product_id, $product_option_id, $options) {
        try {

            $DB = array();
            foreach ($options as $subGroups) {
                try {
                    $dbdata = '';
                    Self::$order++;

                    /* check if the option is already saved in database or do we need to create a new one*/
                    if(isset($subGroups[ 'id' ]) && $subGroups[ 'id' ] > 0) {
                        $groups = ProductOptions::where('id', $subGroups[ 'id' ])->where('product_id', $product_id)->first();
                    }
                    if(!isset($groups)) {
                        $groups = new ProductOptions();
                    }
                    //save the basic data of option
                    $groups->name              = ($subGroups[ 'options_name' ]) ? $subGroups[ 'options_name' ] : NULL;
                    $groups->type              = $subGroups[ 'type' ];
                    $groups->hide_on           = json_encode($subGroups[ 'secret' ]);
                    $groups->product_option_id = $product_option_id;
                    $groups->product_id        = $product_id;
                    $groups->order             = Self::$order;
                    $groups->save();
                    // Enter Product Options in a static array
                    Self::$product_options[] = $groups->id;

                    $Ids                       = array();
                    $Ids[ $subGroups[ 'id' ] ] = $groups->id;
                    array_push(Self::$optionIds, $Ids);

                    // check if there is any sub_option of that particular option
                    if(isset($subGroups[ 'sub_options' ]) && count($subGroups[ 'sub_options' ]) > 0) {
                        $dbdata = self::saveSubOption($groups->id, $subGroups[ 'sub_options' ]); // save sub Option

                    }

                } catch (\Exception $e) {
                    dd($e->getLine() . '--saveGroupOption--' . $e->getMessage());
                }

                $subGroups[ 'id' ]            = $groups->id;
                $subGroups[ 'parent_id' ]     = $product_option_id;
                $subGroups[ 'top_parent_id' ] = $product_option_id;
                if(isset($dbdata) && isset($dbdata['sub_options'])) {
                    $subGroups[ 'sub_options' ] = $dbdata[ 'sub_options' ];
                }
                array_push($DB, $subGroups);
                unset($groups);
            }

            $data[ 'sub_options' ] = $DB;
            $data[ 'optionIds' ]   = Self::$optionIds;
            return $data;

        } catch (\Exception $e) {
            dd($e->getMessage() . '<<--saveGroupOptions-->>', $e->getLine());
        }

    }
    /**/
    // save Meta  Secret Option
    public static function saveSubOption($product_options_id, $sub_options, $linked_id = NULL, $option_select_id = NULL) {
        //$productOptionSelect = ProductOptionSelect::where('option_select_id' ,$product_options_id )->first();;

        if(!empty($sub_options)) {
            $DB = array();
            foreach ($sub_options as $sub_option) {

                try {

                    /**/
                    $productOptionSelect = NULL;
                    if(isset($sub_option[ 'id' ]) && $sub_option[ 'id' ] > 0) {
                        $productOptionSelect = ProductOptionSelects::where('id', $sub_option[ 'id' ])->where('product_option_id', $product_options_id)->first();
                    }
                    if(!isset($productOptionSelect->id)) {
                        $productOptionSelect = new ProductOptionSelects();
                    }

                    if(!empty($product_options_id) && !empty($productOptionSelect->product_option_id)) {
                        if($productOptionSelect->product_option_id != $product_options_id) {
                            $productOptionSelect = new ProductOptionSelects();
                        } else {
                            if(!empty($option_select_id) && !empty($productOptionSelect->option_select_id)) {
                                if($option_select_id != $productOptionSelect->option_select_id) {
                                    $productOptionSelect = new ProductOptionSelects();
                                }
                            }
                        }

                    }
                    /* Save the Basic Data of the Sub option */
                    $productOptionSelect->name            = $sub_option[ 'sub_option_name' ];
                    $productOptionSelect->price_structure = (empty($sub_option[ 'price_structure' ])) ? 'free' : $sub_option[ 'price_structure' ];
                    $productOptionSelect->price_type      = $sub_option[ 'price_type' ];
                    $productOptionSelect->has_quantity    = $sub_option[ 'quantity_status' ];
                    $productOptionSelect->is_discountable = $sub_option[ 'apply_discount' ];
                    if($sub_option[ 'price_percent' ]) {
                        $productOptionSelect->value = $sub_option[ 'price_percent' ];
                    }
                    $productOptionSelect->is_default        = $sub_option[ 'mark_default' ];
                    $productOptionSelect->product_option_id = $product_options_id;//
                    $productOptionSelect->option_select_id  = $option_select_id ?: 0;//
                    // check if the current sub option is linked to any other option or not
                    if(isset($sub_option[ 'linked_option_id' ]) && $sub_option[ 'linked_option_id' ] > 0) {
                        $random_linked_id = $sub_option[ 'linked_option_id' ];
                        $linked_option_id = array_first(
                            Self::$optionIds,
                            function ($arrValue) use ($random_linked_id) {
                                return (key($arrValue) == $random_linked_id);
                            }
                        );
                        $linked_option_id = $linked_option_id[ $sub_option[ 'linked_option_id' ] ];
                        if(!$linked_option_id) {
                            $findValue        = $sub_option[ 'linked_option_id' ];
                            $result           = array_filter(
                                self::$product_option_meta,
                                function ($arrValue) use ($findValue) {
                                    return ($arrValue[ 'id' ] == $findValue);
                                }
                            );
                            $linked_option_id = 0;
                            foreach ($result as $key => $value) {
                                $newOption = self::saveManualOption(self::$product_id, $value);

                                self::$product_option_meta[ $key ][ 'id' ] = $newOption;
                                $linked_option_id                          = $newOption;
                                break;
                            }
                        }
                        $productOptionSelect->linked_option_id = $linked_option_id;
                    }
                    $productOptionSelect->order = Self::$order;
                    $productOptionSelect->save();
                    Self::$order++;
                    // Enter Product Options in a static array
                    Self::$product_option_selects[] = $productOptionSelect->id;

                    $Ids                        = array();
                    $Ids[ $sub_option[ 'id' ] ] = $productOptionSelect->id;
                    array_push(Self::$optionIds, $Ids);

                    $sub_option[ 'id' ] = $productOptionSelect->id;
                    if(isset($sub_option[ 'price_grid' ]) && !empty($sub_option[ 'price_grid' ])) {
                        self::savePriceGridDB($sub_option[ 'price_grid' ], $product_options_id, $productOptionSelect->id);
                    }

                    $child_sub_option = FALSE;
                    if(isset($productOptionSelect->id) && !empty($sub_option[ 'sub_options' ])) {
                        $subSubData                  = self::saveSubOption($product_options_id, $sub_option[ 'sub_options' ], NULL, $productOptionSelect->id);
                        $sub_option[ 'sub_options' ] = $subSubData[ 'sub_options' ];
                    }
                    if($option_select_id > 0) {
                        $sub_option[ 'parent_id' ] = $option_select_id;
                    } else {
                        $sub_option[ 'parent_id' ] = $product_options_id;
                    }
                    $sub_option[ 'top_parent_id' ] = $product_options_id;
                    array_push($DB, $sub_option);

                } catch (\Exception $e) {
                    dd($e->getLine() . '-saveSub121Option--' . $e->getMessage());
                }

            }
            $data[ 'sub_options' ] = $DB;
            $data[ 'optionIds' ]   = Self::$optionIds;
            return $data;
        } else if(isset($linked_id)) {
            $productOptionSelect = ProductOptionSelects::where('option_select_id', $product_options_id)->first();
            if(isset($productOptionSelect->id)) {
                $productOptionSelect->linked_option_id = $linked_id;
                $productOptionSelect->save();
            }
        } else {
            return FALSE;
        }

    }

    // save Secret Option
    public static function saveSecretOption($product_id, $secret_option) {

        if(isset($product_id) && !empty($secret_option)) {
            $product          = ProductOption::find($product_id);
            $product->hide_on = json_encode($secret_option);
            $product->save();
            return 1;
        } else {
            return FALSE;
        }

    }

    // save constraints
    public static function saveConstraints($product_option_id, $constraints) {
        $product_options = ProductOption::find($product_option_id);
        if(isset($product_options->id) && !empty($constraints)) {
            $product_options->constraints = json_encode($constraints);
            $product_options->save();
            return $product_options;
        } else {
            return FALSE;
        }

    }

    public static function savePriceGridDB($price_grid, $top_parent_id, $sub_option_id) {
        if($price_grid != '') {
            /**/
            $parent     = ProductOptions::find($top_parent_id);
            $product_id = $parent->product_id;
            /**/
            $grid_data = \GuzzleHttp\json_decode($price_grid);
            /*Get Current Grid*/
            if(!Self::$data_dump){
                $gridquery = PriceGrid::where('product_sub_option_id', $sub_option_id)->get();
            }else{
                $gridquery = PriceGrid::where('product_sub_option_id', $sub_option_id)->delete();
            }
            $new_grid  = [];
            foreach ($grid_data as $data) {
                try {
                    $grid_count = 0;
                    if(!Self::$data_dump) {
                        if(isset($data->width)) {
                            $grid_count = $gridquery->where('width', $data->width)->count();
                        } else if(isset($data->height)) {
                            $grid_count = $gridquery->where('height', $data->height)->count();
                        } else if(isset($data->width) && isset($data->height)) {
                            $grid_count = $gridquery->where('width', $data->width)->where('height', $data->height)->count();
                        }
                    }

                    /**/
                    if($grid_count == 0) {
                        $grid                            = array();
                        $grid[ 'width' ]                 = (isset($data->width)) ? $data->width : NULL;
                        $grid[ 'height' ]                = (isset($data->height)) ? $data->height : NULL;
                        $grid[ 'price' ]                 = (isset($data->price)) ? $data->price : 0;
                        $grid[ 'product_id' ]            = $product_id;
                        $grid[ 'product_sub_option_id' ] = $sub_option_id;
                        $new_grid[]                      = $grid;
                    } else {

                        $update = PriceGrid::where('product_sub_option_id', $sub_option_id);

                        if(isset($data->width)) {
                            $update->where('width', $data->width);
                        }
                        if(isset($data->height)) {
                            $update->where('height', $data->height);
                        }

                        $update = $update->first();

                        if($update->price != $data->price) {
                            $update->price = (isset($data->price)) ? $data->price : 0;
                            $update->save();
                        }
                    }
                    /**/
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }

            PriceGrid::insert($new_grid);
        }

    }

    /*
   * Save Price Grid
   * @param Options
   * @param Options Ids Old => Key
   * */
    public static function clonePriceGrid($price_grid, $product_id) {
        if(!empty($price_grid)) {
            try {
                $grid_data = \GuzzleHttp\json_decode($price_grid);
                $new_grid  = [];
                foreach ($grid_data as $data) {
                    $grid                 = array();
                    $grid[ 'width' ]      = (isset($data->width)) ? $data->width : NULL;
                    $grid[ 'height' ]     = (isset($data->height)) ? $data->height : NULL;
                    $grid[ 'price' ]      = (isset($data->price)) ? $data->price : 0;
                    $grid[ 'product_id' ] = $product_id;
                    $new_grid[]           = $grid;
                }

                PriceGrid::insert($new_grid);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

    }

    /*
    * Save Option Constraints
    * @param Options
    * @param Options Ids Old => Key
    * */

    public static function saveOptionConstraints($options) {
        try {
            $finalOptions = array();

            // Store in an array all saved option ids
            $optionIdList = [];
            foreach (Self::$optionIds as $newId) {
                $optionIdList[ key($newId) ] = $newId[ key($newId) ];
            }

            foreach ($options as $option) {
                if(isset($option[ 'constraints' ])) {
                    $DB = array();

                    $product_option_id = $option[ 'id' ];

                    if(isset($optionIdList[ $product_option_id ])) {
                        $product_option_id = $optionIdList[ $product_option_id ];

                    }

                    foreach ($option[ 'constraints' ] as $constraint) {
                        if($constraint[ 'id' ]) {
                            $constObj = ProductOptionConstraints::find($constraint[ 'id' ]);
                            if(!$constObj) {
                                $constObj = new ProductOptionConstraints();
                            }
                        }
                        $constObj->event_type        = $constraint[ 'if' ];
                        $constObj->action_type       = $constraint[ 'then' ];
                        $constObj->product_option_id = $product_option_id;
                        $constObj->save();
                        $data[ 'id' ]         = $constObj->id;
                        $data[ 'if' ]         = $constraint[ 'if' ];
                        $data[ 'then' ]       = $constraint[ 'then' ];
                        $data[ 'conditions' ] = self::addConditions($constraint[ 'conditions' ], $constObj->id);
                        array_push($DB, $data);
                    }
                    $option[ 'constraints' ] = $DB;
                }
                array_push($finalOptions, $option);
            }

            return $finalOptions;

        } catch (\Exception $e) {
            dd($e->getMessage() . '--product_option_constraints--', $e->getLine());
        }

    }

    /*
    * Save Constraint Conditions
    * @param Conditions
    * @param Constraints Id
    * @param Options Ids Old => Key
    * */

    public static function addConditions($conditions, $const_id) {
        try {
            $DB           = array();
            $optionIdList = [];
            foreach (Self::$optionIds as $newId) {
                $optionIdList[ key($newId) ] = $newId[ key($newId) ];
            }
            foreach ($conditions as $condition) {
                if($condition[ 'id' ]) {
                    $conditon_data = OptionConstraintActions::find($condition[ 'id' ]);
                    if(!$conditon_data) {
                        $conditon_data = new OptionConstraintActions();
                    }
                }
//                foreach (Self::$optionIds as $newId) {
                if(isset($optionIdList[ $condition[ 'sub_option' ] ])) {
                    $condition[ 'sub_option' ] = (int)$optionIdList[ $condition[ 'sub_option' ] ];
                } else {
                    $condition[ 'sub_option' ] = (int)$condition[ 'sub_option' ];
                }
                if(isset($optionIdList[ $condition[ 'option_label' ] ])) {
                    $condition[ 'option_label' ] = (int)$optionIdList[ $condition[ 'option_label' ] ];
                } else {
                    $condition[ 'option_label' ] = (int)$condition[ 'option_label' ];

                }
//                }

                $conditon_data->sub_option_id        = $condition[ 'sub_option' ];
                $conditon_data->option_id            = $condition[ 'option_label' ];
                $conditon_data->option_constraint_id = $const_id;
                $conditon_data->save();

                $cond = array(
                    'id'           => $conditon_data->id,
                    'sub_option'   => $condition[ 'sub_option' ],
                    'option_label' => $condition[ 'option_label' ],
                );

                array_push($DB, $cond);
            }
            return $DB;
        } catch (\Exception $e) {
            dd($e->getMessage() . 'saveGroupOptions', $e->getLine());
        }

    }

    /*
     * Delete the product options that are not in product option json
     * */
    public static function deleteUnneededOptions() {

        $productOptionQuery = ProductOptions::where('product_id', Self::$product_id)->whereNotIn('id', Self::$product_options)->get();
        $productOptionQuery->each(function ($option) {
            $option->delete();
        });
        $productSubOptionQuery = ProductOptionSelects::whereNotIn('id', Self::$product_option_selects)
                                                     ->whereIn('product_option_id', Self::$product_options)
                                                     ->where(function ($query) {
                                                         $query->Orwhere('option_select_id', '0')
                                                               ->orWhereNull('option_select_id');
                                                     })->get();
        $productSubOptionQuery->each(function ($option) {
            $option->delete();
        });

        $productSubSubOptionQuery = ProductOptionSelects::whereNotIn('id', Self::$product_option_selects)
                                                        ->whereNotIn('option_select_id', Self::$product_option_selects)
                                                        ->where('option_select_id', '>', '0')
                                                        ->get();
        $productSubSubOptionQuery->each(function ($option) {
            $option->delete();
        });
    }

    public static function saveManualOption($product_id, $options) {
        if(isset($options[ 'id' ]) && $options[ 'id' ] > 0) {
            $product_options = ProductOptions::find($options[ 'id' ]);
        }

        if(!isset($product_options)) {
            $product_options = new ProductOptions();
        }
        //save the basic data of option
        $product_options->name       = $options[ 'options_name' ];
        $product_options->type       = $options[ 'type' ];
        $product_options->order      = Self::$order;
        $product_options->hide_on    = json_encode($options[ 'secret' ]);
        $product_options->product_id = $product_id;
        $product_options->save();
        $Ids                     = array();
        $Ids[ $options[ 'id' ] ] = $product_options->id;
        array_push(Self::$optionIds, $Ids);
        Self::$product_options[] = $product_options->id;

        return $product_options->id;
    }

    public static function UpdateConnectTo() {
        foreach (Self::$optionIds as $newId) {
            foreach (Self::$connectedTos as $Info) {
                if($Info[ key($Info) ] == key($newId)) {
                    $option               = ProductOptions::find(key($Info));
                    $option->connected_to = $newId[ key($newId) ];
                    $option->save();
                }
            }
        }

    }

    public static function dumpOptions($product_id, $options){
        Self::$data_dump = TRUE;
        Self::getProductOption($product_id, $options);
    }
}
