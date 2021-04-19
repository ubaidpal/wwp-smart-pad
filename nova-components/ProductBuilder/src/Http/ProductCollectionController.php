<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Collection;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class ProductCollectionController extends Controller
{

    public function getBrands(Request $request)
    {


        $session_id = Session::get('belongsToId');
        if (isset($session_id)) {
            $session_id = $session_id;
        } else {
            $selected_country_id = User::findOrFail(\Auth::user()->id);
            if ($selected_country_id->is_favourite_country != 0) {
                $session_id = $selected_country_id->is_favourite_country;
            }
        }

        // $brands = Brand::with('collections', 'collections.products')->where('country_id', $session_id)->limit(10)->get();
        $brands = Brand::where('country_id', $session_id)->get();
     
        return json_encode($brands);

    }

    public function getCollectionsById($id)
    {
        $collections = Brand::with(['collections', 'collections.products' => function($query) use($id) {
                $query->where('brand_id', $id);
            }])
            ->where('id', $id)
            ->first();
        return json_encode($collections);
    }

    public function getCollections(Request $request)
    {
        $collections = Collection::with('brand')->with('products')->get();
        return json_encode($collections);
    }

    public function storeCollection(Request $request)
    {
        $data = array();
        if ($request->has('name') && $request->has('brand_id')) {
            $name = $request->get('name');
            $brand_id = $request->get('brand_id');
            if ($request->has('id')) {
                $collection = Collection::findOrFail($request->get('id'));
            } else {
                $collection = new Collection();
            }
            $collection->name = $name;
            $collection->brand_id = $brand_id;
            $result = $collection->save();
            $data['success'] = $result;

            $data['collection'] = Collection::with('brand')->with('products')->where('id', $collection->id)->firstOrFail();
            // $data['collection'] = $this->getCollectionsById($brand_id);
            return json_encode($data);
        }
    }

    public function getProducts(Request $request)
    {
        if ($request->has('brand_id')) {
            $products = Product::whereNull('collection_id')->where('brand_id', $request->get('brand_id'))->get();
            return json_encode($products);
        } else {
            return json_encode(Product::all());
        }
    }

    public function addProductToCollection(Request $request)
    {
        if ($request->has('product_id') && $request->has('collection_id')) {
            $productArray = explode(",", $request->get('product_id'));
            for ($i = 0; $i < count($productArray); $i++) {
                $product = Product::findOrFail($productArray[$i]);
                $product->collection_id = $request->get('collection_id');
                $result = $product->save();
            }
            $data['success'] = $result;
            $data['product'] = Product::where('collection_id', $request->get('collection_id'))->where('brand_id', $request->get('brand_id'))->get()->toArray();
            return json_encode($data);
        }
    }

    public function deleteProductFromCollection(Request $request)
    {
        if ($request->has('product_id')) {
            $product = Product::findOrFail($request->get('product_id'));
            $product->collection_id = null;
            $result = $product->save();
            $data['success'] = $result;
            return json_encode($data);
        }
    }

    public function deleteCollection(Request $request)
    {
        try{

            if ($request->has('collection_id')) {

                /**/
                $data = DB::table('product_settings')->where('collection_id', $request->get('collection_id'))->first();
                if(isset($data->id)){
                    DB::table('product_settings_tiers')->where('product_setting_id', $data->id)->delete();
                }
                DB::table('product_settings')->where('collection_id', $request->get('collection_id'))->delete();
                /**/
                $collection = Collection::findOrFail($request->get('collection_id'));
                $collection->collection_id = null;
                if (count($collection->products) == 0) {
                    $data['success'] = $collection->delete();
                } else {
                    $data['success'] = false;
                }
                return json_encode($data);
            }

        } catch (\Exception $e){
            return json_encode(['error' => $e->getMessage().''.$e->getLine()]);
        }

    }

    /*
     *  Create New Collection
     *  Add Product in newly created collection
     * */

    public function addProductCollection(Request $request)
    {

        if ($request->has('collection_id')) {
            $collection_id = $request->get('collection_id');
        } else {
            /**/
            $collection = new Collection();
            $collection->name = $request->get('collection_name');
            $collection->brand_id = $request->get('brand_id');
            $collection->save();
            $collection_id = $collection->id;
            /**/
        }

        if ($request->has('product_id') && $collection_id) {
            $productArray = explode(",", $request->get('product_id'));
            for ($i = 0; $i < count($productArray); $i++) {
                $product = Product::findOrFail($productArray[$i]);
                $product->collection_id = $collection_id;
                $product->save();
            }
        }

        $data['success'] = true;
        $data['collection'] = $collection_id;
        return json_encode($data);
    }

    /*
     *
     * */
    public function getProductCollections($product_id)
    {

        $product = Product::find($product_id);
        $brand_id = $product->brand_id;
        $collections = Collection::where('brand_id', $brand_id)->get();
        return json_encode($collections);
    }


}
