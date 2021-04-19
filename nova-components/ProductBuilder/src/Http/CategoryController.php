<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;


class CategoryController extends Controller
{


    public function index()
    {
        try {

            $categories = Category::orderBy('name', 'asc')->get();
            $categoriesArray = array();
            foreach ($categories as $key => $category) {
                $data['id'] = $category->id;
                $data['name'] = $category->name;
                $data['is_active'] = $category->is_active;
                $data['count'] = Product::where('category_id', $category->id)->count();
                $data['is_fabric'] = $category->is_fabric;
                array_push($categoriesArray, $data);
            }

            return [
                'success' => true,
                'categories' => $categoriesArray
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function destroy(Request $request)
    {
        try {

            $category = Category::where('id', '=', $request->resourceId)->delete();
            return [
                'success' => true,
                'categories' => $category
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function indexFabric()
    {
        try {

            $categories = Category::all();
            $categoriesArray = array();
            foreach ($categories as $key => $category) {
                $data['id'] = $category->id;
                $data['name'] = $category->name;
                $data['is_active'] = $category->is_active;
                $data['is_fabric'] = $category->is_fabric;
                array_push($categoriesArray, $data);
            }

            return [
                'success' => true,
                'categories' => $categoriesArray
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function indexBrand()
    {
        try {

            $session_id = \Session::get('belongsToId');

            if (isset($session_id)) {
                $session_id = $session_id;
            } else {
                $selected_country_id = User::findOrFail(\Auth::user()->id);
                if ($selected_country_id->is_favourite_country != 0) {
                    $session_id = $selected_country_id->is_favourite_country;
                }
            }

            $brands = \App\Models\Brand::with('countries')->where('country_id', $session_id)->orderBy('name')->get();
            $brandsArray = array();
            foreach ($brands as $key => $brand) {
                $data['id'] = $brand->id;
                $data['name'] = $brand->name;
                $data['is_active'] = $brand->is_active;
                $data['type'] = $brand->type;
                $data['iso2_code'] = $brand->countries->iso2_code;
                $data['flag'] = $brand->countries->flag;
                $data['count'] = Product::where('brand_id', $brand->id)->count();
                $data['is_fabric'] = $brand->is_fabric;
                $data['price_type'] = $brand->price_type;
                $data['tax_amount'] = $brand->tax_amount;
                array_push($brandsArray, $data);
            }

            return [
                'success' => true,
                'brands' => $brandsArray
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    /*
     * It will return the brands of specific type like exclusive or non exclusive
     * */
    public function getExclusiveBrands(Request $request, $type)
    {
        try {
            if (!$type) {
                $type = 0;
            }
            $session_id = \Session::get('belongsToId');

            if (isset($session_id)) {
                $session_id = $session_id;
            } else {
                $selected_country_id = User::findOrFail(\Auth::user()->id);
                if ($selected_country_id->is_favourite_country != 0) {
                    $session_id = $selected_country_id->is_favourite_country;
                }
            }

            $brands = \App\Models\Brand::with('countries')->where('type', $type)->where('country_id', $session_id)->get();
            $brandsArray = array();
            foreach ($brands as $key => $brand) {
                $data['id'] = $brand->id;
                $data['name'] = $brand->name;
                $data['is_active'] = $brand->is_active;
                $data['type'] = $brand->type;
                $data['iso2_code'] = $brand->countries->iso2_code;
                $data['flag'] = $brand->countries->flag;
                $data['count'] = Product::where('brand_id', $brand->id)->count();
                $data['is_fabric'] = $brand->is_fabric;
                $data['price_type'] = $brand->price_type;
                $data['tax_amount'] = $brand->tax_amount;
                array_push($brandsArray, $data);
            }

            return [
                'success' => true,
                'brands' => $brandsArray
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function destroyBrand(Request $request)
    {
        try {

            $brand = \App\Models\Brand::where('id', '=', $request->resourceId)->delete();
            return [
                'success' => true,
                'categories' => $brand
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function indexCountries(Request $request)
    {
        try {

            $countries = Country::with('currency')->get();
            $countriesArray = array();
            foreach ($countries as $key => $country) {
                $data['id'] = $country->id;
                $data['name'] = str_limit($country->name, '8', '...');;
                $data['flag'] = $country->flag;
                $data['iso2_code'] = $country->iso2_code;
                $data['currency'] = $country->currency->code;
                $data['measure_system'] = $country->measure_system;
                $data['currency_symbol'] = $country->currency_symbol;
                $data['date_format'] = $country->date_format;
                $data['tax'] = $country->tax;
                $data['active'] = $country->active;
                array_push($countriesArray, $data);
            }

            return [
                'success' => true,
                'countries' => $countriesArray
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function destroyProduct(Request $request)
    {
        try {

            $brand = \App\Models\Product::where('id', '=', $request->resourceId)->delete();
            return [
                'success' => true,
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function updateBrandStatus(Request $request)
    {
        try {

            $status = ($request->current_status == 1) ? 0 : 1;

            $brand = \App\Models\Brand::find($request->resourceId);

            if ($request->type == 'is_fabric') {
                $brand->is_fabric = $status;
            } else {
                $brand->is_active = $status;
            }

            $brand->save();
            return [
                'success' => true,
                'categories' => $brand
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function updateCategoryStatus(Request $request)
    {
        try {

            $category = Category::find($request->resourceId);
            $status = ($request->current_status == 1) ? 0 : 1;

            if ($request->type == 'is_fabric') {
                $category->is_fabric = $status;
            } else {
                $category->is_active = $status;
            }

            $category->save();


            return [
                'success' => true,
                'categories' => $category
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }


    public function updateExclusiveStatus(Request $request)
    {
        try {

            $status = ($request->exclusive_status == 1) ? 0 : 1;
            $brand = \App\Models\Brand::find($request->resourceId);

            if ($request->type == 'type') {
                $brand->type = $status;
            } else {
                $brand->type = $status;
            }

            $brand->save();
            return [
                'success' => true,
                'Exclusive' => $brand
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function updateCountryStatus(Request $request)
    {
        try {

            $country = Country::find($request->resourceId);
            $status = ($request->current_status == 1) ? 0 : 1;
            $country->active = $status;
            $country->save();


            return [
                'success' => true,
                'country' => $country
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

}
