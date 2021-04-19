<?php

namespace Spp\ProductBuilder\Http;


use App\Classes\ProductOption;
use App\Classes\ProductOptionJson;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Country;
use App\Models\PriceGrid;
use App\Models\Product;
use App\Models\ProductOptionConstraints;
use App\Models\ProductOptions;
use App\Models\ProductOptionSelects;
use App\Repository\admin\ProductRepository;
use App\User;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;


class ProductBuilderController extends Controller
{

    protected $ProductRepository;

    public function __construct(
        ProductRepository $ProductRepository
    )
    {
        $this->ProductRepository = $ProductRepository;
        $this->user_id = \Auth::id();
    }

    public function store(Request $request)
    {
        $requestData = $request->all();

        $this->validate($request, [
            'name' => 'required',
            'brands' => 'required',
            'category' => 'required',
        ]);

        $product = new Product();
        $product->name = $requestData['name'];
        $product->brand_id = $requestData['brands'];
        $product->category_id = $requestData['category'];
        $product->country_id = $requestData['countries'];
//        $product->is_workroom = $requestData[ 'is_workroom' ];
        $product->notes = $requestData['notes'];
        $product->save();

        $this->updateBrandComment($requestData['brands'], $requestData['brand_comment']);
        $colection = $this->createCollection($requestData['brands'], $requestData['category'], $product->id);

        return [
            'success' => true,
            'product_id' => $product->id,
            'product' => $product,
            'collection' => $colection
        ];
    }

    /*
     * Update Product Information
     * */

    public function updateBrandComment($brand_id, $comment)
    {
        try {

            $product = Brand::find($brand_id);
            $product->comment = $comment;
            $product->save();

            return [
                'success' => true
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function createCollection($brand_id, $category_id, $product_id)
    {
        try {

            $collection_id = 0;
            //Get Category Name
            $category = Category::find($category_id);
            if ($category->name) {
                $collect = Collection::where('name', 'LIKE', "%$category->name%")->where('brand_id', $brand_id)->first();
                if ($collect) {
                    $collection_id = $collect->id;
                }
            }

            if ($collection_id == 0) {
                $collection = new Collection();
                $collection->name = $category->name;
                $collection->brand_id = $brand_id;
                $collection->save();
                $collection_id = $collection->id;
            }

            $product = Product::find($product_id);
            $product->collection_id = $collection_id;
            $product->save();

            return [
                'success' => TRUE,
                'collection_id' => $collection_id
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function updateProductInfo($product_id, Request $request)
    {
        $requestData = $request->all();
        $product = Product::find($product_id);
        if ($product->brand_id != $requestData['brands']) {
            $product->collection_id = NULL;
        }
        $product->name = $requestData['name'];
        $product->brand_id = $requestData['brands'];
        $product->category_id = $requestData['category'];
        $product->country_id = $requestData['countries'];
        //$product->is_workroom = $requestData[ 'is_workroom' ];
        $product->notes = $requestData['notes'];
        $product->save();

        $this->updateBrandComment($requestData['brands'], $requestData['brand_comment']);

        return [
            'success' => TRUE,
            'product_id' => $product->id,
            'requestdata' => $requestData
        ];
    }

    public function update(Request $request)
    {
        $this->savePriceGridDB($request->grid_data, $request->product_id);
        $product = Product::find($request->product_id);
        $product->price_structure = $request->price_grid_type;
        $product->price_grid = $request->grid_data;
        $product->square_meter_rate = $request->square_meter_rate;
        $product->save();

        // Update the Order Window Products
            $orderWindowProductIds = DB::table('order_window_products')->select('order_window_products.*')
                ->leftJoin('orders', 'orders.id', '=', 'order_window_products.order_id')
                ->where('order_window_products.product_id', $request->product_id)
                ->where('orders.is_quote', 1)
                ->whereNull('order_window_products.deleted_at')
                ->get()
                ->pluck('id')->toArray();

            DB::table('order_window_products')
                ->whereIn('id', $orderWindowProductIds)
                ->update(['has_updates' => true]);

        return [
            'success' => true,
            'product_id' => $product
        ];

    }

    /*
     * get Product Options of a Product
     * */

    public function savePriceGridDB($price_grid, $product_id)
    {
        if ($price_grid != '') {
            $grid_data = \GuzzleHttp\json_decode($price_grid);
            $gridquery = PriceGrid::where('product_id', $product_id);
            $gridquery->delete();
            $new_grid = [];
            foreach ($grid_data as $data) {

                $grid = array();
                $grid['width'] = (isset($data->width)) ? $data->width : NULL;
                $grid['height'] = (isset($data->height)) ? $data->height : NULL;
                $grid['price'] = (isset($data->price)) ? $data->price : 0;
                $grid['product_id'] = $product_id;
                $new_grid[] = $grid;
                /**/

            }
            PriceGrid::insert($new_grid);
            return true;
        }
    }

    /*
     * Get Products of a specific brand or category
     * */

    public function excelReader(Request $request)
    {

        $height_width = [
            'width_and_height_based_chart_mt',
            'width_and_height_based_chart_ft',
            'width_and_height_based_chart_in'
        ];

        $width_chart = [
            'width_based_chart_mt',
            'width_based_chart_ft',
            'width_based_chart_in'
        ];

        /*-- upload path --*/
        $destination = storage_path('excel');
        $extension = $request->file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;

        /*-- upload file full path --*/
        $FullPath = storage_path('excel/' . $fileName);

        /*-- uploading file to given path (new FastExcel)->import($FullPath) --*/
        if ($request->file->move($destination, $fileName)) {

            $excel = Excel::load($FullPath)->get();
            $header = (($excel->first())->keys())->toArray();
     
            $exceldata = Array();
            foreach ($excel as $key => $row) {
                $info = $row->toArray();
                array_push($exceldata, $info);
            }

            if (in_array($request->price_grid_type, $width_chart)) {
                $data = array();
                foreach ($header as $k => $v) {
                    if ($k > 0) {
                        $info = array('width' => $v, 'price' => $exceldata[0][$v]);
                        array_push($data, $info);
                    }
                }
                $data['rows'] = $data;
            } else if (in_array($request->price_grid_type, $height_width)) {
                $data['header'] = $header;
                $data['rows'] = $exceldata;
            } else {
                $data = array();
                $data['rows'] = $excel;
            }
            $response = array(
                'success' => true,
                'data' => $data,
                'excel' => $header
            );

            return json_encode($response);
        }
    }

    public function importSubOptions(Request $request)
    {

        /*-- upload path --*/
        $destination = storage_path('excel');
        $extension = $request->file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;

        /*-- upload file full path --*/
        $FullPath = storage_path('excel/' . $fileName);

        /*-- uploading file to given path (new FastExcel)->import($FullPath) --*/
        if ($request->file->move($destination, $fileName)) {

            $excel = Excel::load($FullPath)->get();
            $header = (($excel->first())->keys())->toArray();

            $exceldata = Array();
            foreach ($excel as $key => $row) {
                $info = $row->toArray();
                array_push($exceldata, $info);
            }
            $data['header'] = $header;
            $data['rows'] = $exceldata;

            $response = array(
                'success' => true,
                'data' => $data
            );

            return json_encode($response);
        }
    }

    public function productOptions(Request $request)
    {
        if (isset($request->product_id) && !empty($request->options)) {
            $response = ProductOption::getProductOption($request->product_id, $request->options);
        }
        return [
            'success' => true,
            'optionIds' => $response

        ];

    }

    public function getProducts($brand_id, $category_id = Null, Request $request)
    {
        return [
                'products' => $this->ProductRepository->getProducts($brand_id, $category_id, $request->all()),
                'success' => TRUE
                ];

    }

    public function inCollectionProducts($in_collection = true, $brand_id, $category_id)
    {
        $data = array();
        $product = ($brand_id != 0) ? $this->ProductRepository->inCollectionProducts($in_collection, $brand_id, $category_id) : [];
        $data['products'] = $product;
        $data['success'] = TRUE;
        return $data;
    }

    public function getCountries()
    {
        $profile = User::findOrFail(\Auth::user()->id);
        $country_id = 0;

        //Full Access Admin Role
        if ($profile->role_id == 1) {
            $selected_country = Session::get('belongsToId');
            if (!isset($selected_country)) {
                if ($profile->country_id == null) {
                    $country_id = $profile->is_favourite_country;
                } else {
                    $country_id = $profile->country_id;
                }
            } else {
                $country_id = $selected_country;
            }
        }

        //Other Users
        if ($profile->is_access_limited == 1) {
            $country_id = $profile->country_id;
        }

        Session::put('belongsToId', $country_id);
        $counties = Country::Select('id', 'name', 'iso2_code', 'flag', \DB::raw("id=$country_id as selected"))->where('active', 1)->get();
        return [
            'success' => true,
            'countries' => $counties
        ];

        if (!isset($selected_country)) {
            $selected_country_id = User::findOrFail(\Auth::user()->id);
            if ($selected_country_id->is_favourite_country != 0) {
                $selected_country = $selected_country_id->is_favourite_country;
            } else {
                $selected_country = 0;
            }

        }

        if ($selected_country != 0) {

            $counties = Country::Select('id', 'name', 'iso2_code', 'flag', \DB::raw("id=$selected_country as selected"))->where('active', 1)->get();

            if (!Session::has('belongsToId')) {
                Session::put('belongsToId', $counties[0]->id);
            }

            return [
                'success' => true,
                'countries' => $counties
            ];
        } else {

            $counties = Country::Select('id', 'name', 'iso2_code', 'flag')->where('active', 1)->get();
            return [
                'success' => false,
                'countries' => $counties
            ];
        }


    }

    public function getActiveCountries()
    {
        $counties = Country::Select('id', 'name', 'iso2_code', 'flag')->where('active', 1)->get();
        return [
            'success' => false,
            'countries' => $counties
        ];

    }

    public function setSession($belongsToId)
    {
        $selected_country = '';
        Session::put('belongsToId', $belongsToId);

        $users = User::findOrFail(\Auth::user()->id);

        if (isset($users->id)) {
            $users->is_favourite_country = $belongsToId;
            $users->save();
            if ($users->save()) {
                $selected_country_id = User::findOrFail($users->id);
                $selected_country = Country::findOrFail($selected_country_id->is_favourite_country);
            }
        }


        return [
            'selected_country' => $selected_country,
            'success' => true
        ];
    }

    public function resetPriceGrid(Request $request)
    {
        try {

            $product = Product::find($request->product_id);
            $product->price_structure = '';
            $product->square_meter_rate = 0;
            $product->price_grid = '';
            $product->save();
            $this->deletePriceGrid($request);
            return [
                'success' => TRUE
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function deletePriceGrid(Request $request)
    {
        try{

            $productId = $request->get('product_id');
            $optionId = $request->get('option_id');
            $widths = $request->get('widths');
            $heights = $request->get('heights');
            $priceGrid = '';

            if ($productId) {
                $priceGrid = PriceGrid::where('product_id', $productId);
            } else if ($optionId) {
                $priceGrid = PriceGrid::where('product_sub_option_id', $optionId);
            }

            if ($widths) {
                $widthsArray = explode(',', $widths);
                $priceGrid = $priceGrid->whereIn('width', $widthsArray);
            }

            if ($heights) {
                $heightsArray = explode(',', $heights);
                $priceGrid = $priceGrid->whereIn('height', $heightsArray);
            }

            if ($priceGrid) {
                $priceGrid = $priceGrid->get();
                $result = $priceGrid->each->delete();
                if ($result) {
                    return json_encode([
                        'success' => true,
                    ]);
                } else {
                    return json_encode([
                        'success' => false
                    ]);
                }
            } else {
                return json_encode([
                    'success' => false
                ]);
            }

        } catch (\Exception $e){
            return json_encode(['error' => $e->getLine().'----'.$e->getMessage()]);
        }

    }

    public function getPriceGrid($product_id)
    {
        try {

            $product = Product::select('price_structure AS price_grid_type', 'price_grid', 'square_meter_rate')->find($product_id);

            if ($product->price_grid_type == 'width_and_height_based_chart_in' || $product->price_grid_type == 'width_and_height_based_chart_ft' || $product->price_grid_type == 'width_and_height_based_chart_mt') {
                $data = $this->parsePriceGrid($product->price_grid);
            } else {
                $data = $product->price_grid;
            }
            return [
                'success' => TRUE,
                'price_grid_type' => $product->price_grid_type,
                'square_meter_rate' => $product->square_meter_rate,
                'price_grid' => $data
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    //<!--  Get Brand List -->

    public function parsePriceGrid($grid)
    {
        $price_grid_array = json_decode($grid);
        $collection = collect($price_grid_array);
        $grouped = $collection->groupBy('width');
        $gropedHeight = $collection->groupBy('height');
        $cols = array();
        foreach ($grouped as $key => $group) {
            array_push($cols, $key);
        }
        sort($cols);
        $data['columns'] = $cols;
        $rows = array();
        $i = 0;
        foreach ($gropedHeight as $height => $price) {
            $key = array(
                '0' => $height,
            );
            foreach ($cols as $width) {
                $key[$width] = null;
                $object = $collection->where('height', $height)->where('width', $width);
                if ($object->count() > 0) {
                    $key[$width] = ($object[key($object->toArray())]->price) ? $object[key($object->toArray())]->price : null;
                }
                $i++;
            }
            array_push($rows, $key);
        }
        $data['rows'] = $rows;
        return $data;
    }

    public function getParsedPriceGrid(Request $request)
    {
        $grid = $request->grid;
        $data = $this->parsePriceGrid($grid);
        return $data;
    }

    public function getOptions($product_id)
    {
        try {
            $this->recompileProductOptions($product_id);

            $options = Product::select('options')->find($product_id);
            //$options = ProductOptions::select('*','name as options_name')->where('product_id',$product_id)->get();

            return [
                'success' => true,
                'options' => $options
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    // get product cloning

    public function getCountryMeasureUnit()
    {
        $selected_country = Session::get('belongsToId');

        if (!isset($selected_country)) {
            $selected_country_id = User::findOrFail(\Auth::user()->id);
            if ($selected_country_id->is_favourite_country != 0) {
                $selected_country = $selected_country_id->is_favourite_country;
            } else {
                $selected_country = 0;
            }

        }

        if ($selected_country != 0) {

            $counties = Country::Select('id', 'measure_system')->where('id', $selected_country)->get();

            if (!Session::has('belongsToId')) {
                Session::put('belongsToId', $counties[0]->id);
            }

            return [
                'success' => true,
                'countries' => $counties
            ];
        } else {

            $counties = Country::Select('id', 'iso2_code', 'flag')->where('active', 1)->get();
            return [
                'success' => false,
                'countries' => $counties
            ];
        }


    }

    //Clone products

    public function getBrandList()
    {
        try {

            $session_id = Session::get('belongsToId');
            if (isset($session_id)) {
                $session_id = $session_id;
            } else {
                $selected_country_id = User::findOrFail(\Auth::user()->id);
                if ($selected_country_id->is_favourite_country != 0) {
                    $session_id = $selected_country_id->is_favourite_country;
                }
            }
            $brand = Brand::where('country_id', $session_id)->orderBy('name', 'ASC')->get();

            return [
                'success' => true,
                'brand' => $brand
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    //Get Product Info Single

    public function getCategories($brand_id = false)
    {

        try {
            if ($brand_id > 0) {
                $active_ids = Product::select('category_id')->where('brand_id', $brand_id)->get();
                $categories = Category::whereIn('id', $active_ids)->orderBy('name', 'ASC')->get();
            } else if ($brand_id == -1 || $brand_id == false) {
                $categories = Category::orderBy('name', 'ASC')->get();
            } else {
                $categories = [];
            }
            return [
                'success' => true,
                'categories' => $categories
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    //Get Brand Comment

    public function activeCategories($brand_id = false)
    {

        try {
            if (is_numeric($brand_id)) {
                $active_ids = Product::select('category_id')->where('brand_id', $brand_id)->get();
                $categories = Category::where('is_active', '1')->whereIn('id', $active_ids)->orderBy('name')->get();
            } else {
                $categories = Category::where('is_active', '1')->orderBy('name')->get();
            }
            return [
                'success' => TRUE,
                'categories' => $categories
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    //Set Dealer and Brand Access

    public function getCloneProduct(Request $request)
    {
        try {
            $getProduct = $this->ProductRepository->getCloneProduct($request);
            if (isset($getProduct->id)) {
                return [
                    'success' => TRUE,
                    'product' => $getProduct
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    //Update Brand Comment

    public function cloneProduct(Request $request)
    {
        try {

            $productClone = $this->ProductRepository->cloneProduct($request);
            if (!empty($productClone)) {
                return [
                    'success' => TRUE,
                    'productClone' => $productClone
                ];
            }


        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    //add brand

    public function getProductInfo($product_id)
    {
        try {
            $product = Product::with('brands')->find($product_id);
            return [
                'success' => TRUE,
                'product' => $product
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }


    //get brand

    public function getBrandComment($brand_id)
    {
        try {
            $brand = Brand::find($brand_id);
            return [
                'success' => TRUE,
                'comment' => $brand->comment
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    // add new category

    public function setBrandDealer($id, $column, $value)
    {
        try {
            $brand = Brand::find($brand_id);
            return [
                'success' => TRUE,
                'comment' => $brand->comment
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    // Update Product Status

    public function addNewBrand(Request $request)
    {
        try {

            $data = json_decode($request->activeBrand, true);

            if (isset($request->id)) {
                if (isset($request->name)) {
                    $name = array('name' => $request->name);
                    $data = array_merge($data, $name);
                }

                $rules = [
                    'name' => 'required',
                ];
            } else {
                if (isset($request->name)) {
                    $name = array('name' => $request->name);
                    $data = array_merge($data, $name);
                }

                $rules = [
                    'name' => 'required',
                ];
            }

            $validator = \Validator::make($data, $rules);

            if ($validator->passes()) {
                $brands = $this->ProductRepository->addBrand($request);
                if (!empty($brands)) {
                    return [
                        'success' => TRUE,
                        'message' => $brands
                    ];
                }
            } else {
                return [
                    'errors' => TRUE,
                    'message' => $validator->errors()
                ];

                // dd($validator->errors()->all());
            }


        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    // Update Bulk Status

    public function getBrand(Request $request)
    {
        try {

            $brand = $this->ProductRepository->getBrand($request);

            if (!empty($brand)) {
                return [
                    'success' => TRUE,
                    'brand' => $brand
                ];
            }


        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /*
     * @param $dealer_id
     * it will return all the access for a dealer for brands
     * */

    public function addNewCategory(Request $request)
    {
        try {

            $category = $this->ProductRepository->addNewCategory($request);
            if (!empty($category)) {
                return [
                    'success' => TRUE,
                    'message' => $category
                ];
            }


        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function updateProductStatus(Request $request)
    {
        try {

            $product = Product::find($request->resourceId);
            $status = $request->current_status;
            $product->is_active = $status;
            $product->save();
            return [
                'success' => TRUE,
                'products' => $product
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function changeBulkStatus(Request $request)
    {
        try {
            $action = $request->get('action');
            $ids = $request->get('ids');
            $statusField = 'is_active';
            $response = $this->ProductRepository->changeBulkStatus($statusField, $action, $ids);
            if ($response) {
                return [
                    'success' => true,
                ];
            } else {
                return [
                    'success' => false,
                ];
            }


        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /*
     * Delete an Option
     * */

    public function getBrandDealers($dealer_id, $fabric)
    {
        try {

            $fabric = ($fabric == 'fabric') ? 1 : 0;
            $session_id = Session::get('belongsToId');
            if (isset($session_id)) {
                $session_id = $session_id;
            } else {
                $selected_country_id = User::findOrFail(\Auth::user()->id);
                if ($selected_country_id->is_favourite_country != 0) {
                    $session_id = $selected_country_id->is_favourite_country;
                }
            }
            $brands = Brand::with('accounts')
                ->where('country_id', $session_id)
                ->where('is_active', 1)
                ->where('is_fabric', $fabric)
                ->orderBy('name', 'ASC')
                ->get();
            $rows = array();
            foreach ($brands as $brand) {
                $dealerExist = false;
                $data = array();
                foreach ($brand->accounts as $account) {
                    if ($account->account_id == $dealer_id) {
                        $data = array();
                        $data['id'] = $brand->id;
                        $data['name'] = $brand->name;
                        $data['brand_access'] = $account->brand_access;
                        $data['dealer_admin'] = $account->dealer_admin;
                        $data['main_admin'] = $account->main_admin;
                        $dealerExist = true;
                    }
                }
                if (!$dealerExist && $brand->type != '1') {
                    $data = array();
                    $data['id'] = $brand->id;
                    $data['name'] = $brand->name;
                    $data['type'] = $brand->type;
                    $data['brand_access'] = 0;
                    $data['dealer_admin'] = 0;
                    $data['main_admin'] = 0;
                }
                if (!empty($data)) {
                    $rows[] = $data;
                }

            }

            return [
                'success' => TRUE,
                'brands' => $rows
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function appendProductOption(Request $request)
    {
        $productId = $request->id;
        $optionsJson = json_decode($request->optionsJson);
        $activeProduct = Product::findOrFail($productId);

        if (isset($activeProduct->id)) {
            $option_array = json_decode($activeProduct->options, true);
            $option_array = $option_array ?: [];
            if (is_array($optionsJson)) {
                foreach ($optionsJson as $option) {
                    $option_array[] = $option;
                }
            } else {
                $option_array[] = $optionsJson;
            }
            $activeProduct->options = json_encode($option_array);
            $result = $activeProduct->save();
            $response = ProductOption::getProductOption($productId, $activeProduct->options);
            return json_encode([
                'result' => $result,
                'success' => true,
                'optionsIds' => $response
            ]);

        } else {

            return json_encode([
                'success' => false
            ]);

        }

    }

    public function deleteOption(Request $request)
    {
        try {
            $id = $request->get('id');
            $ids = explode(",", $id);
            $options = ProductOptions::whereIn('id', $ids)->get();
            $result = $options->each->delete();
            if ($result) {
                return json_encode([
                    'success' => true
                ]);
            } else {
                return json_encode([
                    'success' => false
                ]);
            }
        } catch (\Exception $e) {
            return json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleteSubOption(Request $request)
    {
        try {
            $id = $request->get('id');
            $ids = explode(",", $id);
            $options = ProductOptionSelects::whereIn('id', $ids)->get();
            $result = $options->each->delete();
            if ($result) {
                return json_encode([
                    'success' => true,
                ]);
            } else {
                return json_encode([
                    'success' => false
                ]);
            }
        } catch (\Exception $e) {
            return json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updateCountryUser(Request $request)
    {
        try {
            $user = User::find($request->resourceId);
            $user->is_access_limited = ($request->is_access_limited) ? 1 : 0;
            $user->country_id = $request->country_id;
            $user->save();
            return [
                'success' => TRUE,
                'user' => $user
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getOptionJson($product_id)
    {
        return ProductOptionJson::getOptionJson($product_id);
    }

    public function recompileProductOptions($product_id){
        $product = Product::findOrFail($product_id);
        if(!$product->is_recompiled){
            /**/
            $options = json_decode($product->options);
            foreach ($options as $option){
                if($option->type == 'group'){
                    $sub_options = ProductOptionSelects::select('id')->where('product_option_id',$option->linked_sub_id)->get();
                    $constraints = DB::table('product_option_constraint_actions')->whereIn('sub_option_id', $sub_options)->first();
                    if($constraints){
                        $const = ProductOptionConstraints::find($constraints->option_constraint_id);
                        if($option->linked_sub_id  != $const->product_option_id) {
                            $const->product_option_id = $option->linked_sub_id;
                            $const->save();
                        }
                    }
                }
            }
            /**/
            $json = ProductOptionJson::getOptionJson($product->id);
            $product->is_recompiled = true;
            $product->options = json_encode($json);
            $product->save();
        }
        return $product;
    }
}
