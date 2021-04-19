<?php

/**
 * Created by   :  Ubaid
 * Project Name : Smart Pad Pro
 * Product Name : PhpStorm
 * Date         : 1-29-2019 4:14 PM
 * File Name    : ProductRepository.php
 */

namespace App\Repository\admin;

use App\Classes\ProductOption;
use App\Jobs\SendAccountsEmail;
use App\Models\AccountBrands;
use App\Models\Accounts;
use App\Models\CompanyEmployees;
use App\Models\Country;
use App\Models\Employees;
use App\Models\EmployeesRoles;
use App\Models\Help;
use App\Models\HelpGroups;
use App\Models\Product;
use App\Models\Role;
use App\Nova\Brand;
use App\Observers\AccountsObserver;
use App\User;
use DemeterChain\A;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Repository\admin\Eloquent\Repository;
use Carbon\Carbon;
class ProductRepository extends Repository
{

    function model() {
        return 'App\User';
    }

    public function getCloneProduct($request) {
        return Product::findOrFail($request->product_id);
    }

    public function cloneProduct($request) {

        if(!empty($request->options)){
            $options = json_decode($request->options);
        }
        $product                  = new Product();
        $product->name            = $request->name;
        $product->brand_id        = $request->brand_id;
        $product->category_id     = $request->category_id;
        $product->country_id      = $request->country_id;
        $product->is_active       = (isset($request->is_active)) ? $request->is_active : 0;
        $product->price_structure = (!empty($request->price_structure)) ? $request->price_structure : NULL;
        $product->price_grid      = (!empty($request->gridOption)) ? $request->gridOption : NULL;
        $product->options         = (!empty($options)) ? json_encode($options) : NULL;
       // echo '<pre>'; print_r($product); die;

        $product->save();
        if($product->save()) {
            if(!empty($request->gridOption)){
                ProductOption::clonePriceGrid($request->gridOption,$product->id);
            }
            if(!empty($options)){
                ProductOption::getProductOption($product->id ,$request->options );
            }

            $prod                  = Product::with('brands', 'category', 'countries')->where('id', $product->id)->first();
            $finalArray            = array();
            $data[ 'id' ]          = $prod->id;
            $data[ 'name' ]        = $prod->name;
            $data[ 'brand' ]       = $prod->brands->name;
            $data[ 'category' ]    = $prod->category->name;
            $data[ 'brand_id' ]    = $prod->brands->id;
            $data[ 'category_id' ] = $prod->category->id;
            $data[ 'country_id' ]  = $prod->country_id;
            $data[ 'is_active' ]  = $prod->is_active;
            array_push($finalArray, $data);

            return $finalArray;
        }

    }

    public function getProducts($brand_id, $category_id, $request = []) {
        /* $session_id = Session::get('belongsToId');
            if(isset($session_id)) {
                $session_id = $session_id;
            } else {
                $selected_country_id = User::findOrFail(\Auth::user()->id);
                if($selected_country_id->is_favourite_country != 0) {
                    $session_id = $selected_country_id->is_favourite_country;
                }
            }
            $brands   = Brand::where('country_id', $session_id)->where('id', $brand_id)->get();
            $brandIds = array();
            foreach ($brands as $brand) {
                array_push($brandIds, $brand->id);
            }*/

        $session_id = $this->getCurrentCountry();
 
        $products = Product::with(['brands', 'category', 'countries'])
                             ->whereHas('category', function ($q) use ($category_id, $request) {
                                 // Query the name field in status table
                                 if($category_id > 0) {
                                     $q->where('id', $category_id); // '=' is optional
                                 }
                             })->when($brand_id, function ($q) use ($brand_id, $request) {
                                    if($brand_id > 0) {
                                        $q->where('brand_id', $brand_id);
                                    }
                            })->where('country_id', $session_id);

        if(isset($request['search'])){
            $products->orWhere('id', 'LIKE', '%'.$request['search'].'%')
                    ->orWhere('name', 'LIKE', '%'.$request['search'].'%');
        }

        $totalRecords = $products->count();

        if(isset($request['sort_field']) && isset($request['sort_type']) && isset($request['page'])){
            $products = $products->orderBy($request['sort_field'], $request['sort_type'])
                ->offset(($request['page']-1)*$request['perPage'])
                ->take($request['perPage'])
                ->get();
        }else{
            $products = $products->orderBy('name')
                ->get();
        }

        $finalArray = [];
        foreach ($products as $key => $prod) {
            $data[ 'id' ]            = $prod->id;
            $data[ 'name' ]          = $prod->name;
            $data[ 'brand' ]         = $prod->brands->name;
            $data[ 'category' ]      = $prod->category->name;
            $data[ 'brand_id' ]      = $prod->brands->id;
            $data[ 'category_id' ]   = $prod->category->id;
            $data[ 'country_id' ]    = $prod->country_id;
            $data[ 'is_workroom' ]   = $prod->is_workroom;
            $data[ 'collection_id' ] = $prod->collection_id;
            $data[ 'is_active' ]     = $prod->is_active;
            array_push($finalArray, $data);
        }
        return [
                'data'          => $finalArray,
                'totalRecords'  => $totalRecords
                ];
    }

    public function inCollectionProducts($in_collection, $brand_id, $category_id) {
        $session_id = $this->getCurrentCountry();
        $products   = array();
        if(isset($category_id)) {
            $value = $category_id;
        } else {
            $value = 0;
        }
        if($in_collection == 'true') {
            $query = Product::with(['brands', 'category', 'countries'])
                            ->whereHas('category', function ($q) use ($value) {
                                // Query the name field in status table
                                if($value > 0) {
                                    $q->where('id', $value); // '=' is optional
                                }
                            })->whereNotNull('collection_id');
        } else {
            $query = Product::with(['brands', 'category', 'countries'])
                            ->whereHas('category', function ($q) use ($value) {
                                // Query the name field in status table
                                if($value > 0) {
                                    $q->where('id', $value); // '=' is optional
                                }
                            })->whereNull('collection_id');

        }

        if($brand_id > -1) {
            $query = $query->where('brand_id', $brand_id);
        }
        $products[] = $query->where('country_id', $session_id)->get();
        $finalArray = array();
        foreach ($products[ 0 ] as $key => $prod) {
            $data[ 'id' ]       = $prod->id;
            $data[ 'name' ]     = $prod->name;
            $data[ 'brand' ]    = $prod->brands->name;
            $data[ 'category' ] = $prod->category->name;
            array_push($finalArray, $data);
        }
        return $finalArray;
    }

    // add brand
    public function addBrand($request) {

        if(isset($request->id)) {
            $brand = \App\Models\Brand::where('id', $request->id)->first();
        } else {
            $brand = new \App\Models\Brand();
        }
        $brand->name       = $request->name;
        $brand->is_active  = ($request->is_active) ? $request->is_active : 1;
        $brand->country_id = $request->countries;
        $brand->is_fabric  = $request->is_fabric;
        $brand->comment    = $request->comment;
        $brand->price_type = $request->price_type;
        $brand->tax_amount = $request->tax_amount;
        if(!empty($request->activeBrand)) {
            $data                   = json_decode($request->activeBrand, TRUE);
            $brand->company_name    = ($data[ 'company_name' ]) ? $data[ 'company_name' ] : NULL;
            $brand->address         = ($data[ 'address' ]) ? $data[ 'address' ] : NULL;
            $brand->company_address = ($data[ 'company_address' ]) ? $data[ 'company_address' ] : NULL;
            $brand->company_phone   = ($data[ 'company_phone' ]) ? $data[ 'company_phone' ] : NULL;
            $brand->email           = ($data[ 'email' ]) ? $data[ 'email' ] : NULL;
            $brand->city            = ($data[ 'city' ]) ? $data[ 'city' ] : NULL;
            $brand->state           = ($data[ 'state' ]) ? $data[ 'state' ] : NULL;
            $brand->postal_code     = ($data[ 'postal_code' ]) ? $data[ 'postal_code' ] : NULL;
            $brand->company_fax     = ($data[ 'company_fax' ]) ? $data[ 'company_fax' ] : NULL;
            $brand->type            = ($data[ 'type' ]) ? $data[ 'type' ] : 0;
            $brand->save();
            if($data['dealers']){
                $brand_id = $brand->id;
                $this->setBrandDealers($brand_id, $data['dealers']);
            }
        }else{
            $brand->save();
        }

        return $brand;
    }
    /*
     * Set Dealers for Exclusive Brand
     * */
    public function setBrandDealers($brand_id, $dealers){
        $brandDealers = AccountBrands::where('brand_id', $brand_id)->get();
        foreach ($brandDealers as $brandDealer) {
            if(!in_array($brandDealer->account_id, $dealers)) {
                $brandDealer->delete();
            }
        }
        foreach ($dealers as $key => $dealer) {
            $isDealerExist = $brandDealers->where('account_id', $dealer)->first();
            if(!$isDealerExist) {
                $newBrandDealer               = new AccountBrands();
                $newBrandDealer->brand_id     = $brand_id;
                $newBrandDealer->account_id   = $dealer;
                $newBrandDealer->brand_access = 0;
                $newBrandDealer->main_admin   = 0;
                $newBrandDealer->dealer_admin = 0;
                $newBrandDealer->save();
            }
        }

    }
    public function addNewCategory($request) {

        $category            = new \App\Models\Category();
        $category->name      = $request->category_name;
        $category->is_active = ($request->is_active) ? $request->is_active : 1;
        $category->is_fabric = $request->is_fabric;
        $category->save();
        return $category;
    }

    public function changeBulkStatus($statusField, $action, $ids) {
        if($ids) {
            $ids = explode(',', $ids);
            foreach ($ids as $id) {
                $product = Product::findOrFail($id);
                if($action == 'active') {
                    $product->$statusField = 1;
                    $product->save();
                } else if($action == 'deactive') {
                    $product->$statusField = 0;
                    $product->save();
                } else if($action == 'delete') {
                    $product->delete();
                }
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }
    /*
     * Get current selected country from session
     * */
    public function getCurrentCountry() {
        $session_id = \Session::get('belongsToId');
        if(isset($session_id)) {
            $session_id = $session_id;
        } else {
            $current_user_id     = \Auth::user()->id;
            $selected_country_id = User::findOrFail($current_user_id);
            if($selected_country_id->is_favourite_country != 0) {
                $session_id = $selected_country_id->is_favourite_country;
            }
        }
        return $session_id;
    }
    /*
     * Get Specific Brand
     * */
    public function getBrand($request) {

        $brands = \App\Models\Brand::where('id', $request->id)->first();
        if(isset($brands->id)) {
            $finalArray                = array();
            $data[ 'type' ]            = ($brands->type) ? $brands->type : NULL;
            $data[ 'dealers' ]         = $this->getBrandDealers($request->id);
            $data[ 'company_name' ]    = ($brands->company_name) ? $brands->company_name : NULL;
            $data[ 'address' ]         = ($brands->address) ? $brands->address : NULL;
            $data[ 'company_address' ] = ($brands->company_address) ? $brands->company_address : NULL;
            $data[ 'company_phone' ]   = ($brands->company_phone) ? $brands->company_name : NULL;
            $data[ 'email' ]           = ($brands->email) ? $brands->email : NULL;
            $data[ 'city' ]            = ($brands->city) ? $brands->city : NULL;
            $data[ 'state' ]           = ($brands->state) ? $brands->state : NULL;
            $data[ 'company_fax' ]     = ($brands->company_fax) ? $brands->company_fax : NULL;
            $data[ 'postal_code' ]     = ($brands->postal_code) ? $brands->postal_code : NULL;
            array_push($finalArray, $data);

        }
        return $finalArray;

    }
    /*
     * Get Brand Dealers
     * */
    public function getBrandDealers($brand_id) {
        $dealers = AccountBrands::select('account_id')->where('brand_id', $brand_id)->get();
        $data    = [];
        foreach ($dealers as $dealer) {
            $data[] = $dealer->account_id;
        }
        return $data;
    }
}
