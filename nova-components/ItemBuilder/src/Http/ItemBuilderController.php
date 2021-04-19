<?php

namespace Spp\ItemBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\BrandVersion;
use App\Models\Category;
use App\Models\Item;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class ItemBuilderController extends Controller
{


    public function store(Request $request)
    {
        // try {
            set_time_limit(300);

            $requestData = $request->all();
            $requestData['row'] = json_decode($requestData['row'], TRUE);
            $request->merge($requestData);

            $this->validate($request, [
                'brand_id' => 'required',
                'category_id' => 'required',
                'brand_version' => 'required',
                'row' => 'required',
                'row.*.Fabric_Name' => 'required',
                'row.*.Price' => 'required|numeric',
                'row.*.Active' => 'required|boolean',
            ], [
                'brand_id.required' => 'Brand is required',
                'category_id.required' => 'Category is required',
                'brand_version.required' => 'Brand Version is required',
                'row.required' => 'At-least one row is required',
            ]);
            
            // add versions
            if (isset($requestData['brand_version'])) {
                $brand_version_id = $this->saveBrandVersion($requestData['brand_id'], $requestData['brand_version']);
            }
           
            $data = [];
            $query = "INSERT INTO items (`category_id`, `brand_id`, `name`, `fabric_width`, `fabric_repeat`, `price`, `active`, `version_id`, `updated_at`, `created_at`) VALUES ";

            foreach ($requestData['row'] as $gridDatum) {
                $gridDatum['Fabric_Width'] = $gridDatum['Fabric_Width'] ? $gridDatum['Fabric_Width'] : 0;
                $gridDatum['Fabric_Repeat'] = $gridDatum['Fabric_Repeat'] ? $gridDatum['Fabric_Repeat'] : 0;

                $data[] = '("'.$requestData['category_id'].'", "'.
                                $requestData['brand_id'].'", "'.
                                trim($gridDatum['Fabric_Name']).'", "'.
                                $gridDatum['Fabric_Width'].'", "'.
                                $gridDatum['Fabric_Repeat'].'", "'.
                                $gridDatum['Price'].'", "'.
                                $gridDatum['Active'].'", "'.
                                @$brand_version_id.'", "'.
                                date('Y-m-d H:i:s').'", "'.
                                date('Y-m-d H:i:s').
                            '")';
            }
            $query = $query.' '.implode(',', $data);
            DB::statement($query);

            $this->updateBrandComment($requestData['brand_id'], $requestData['brand_comment']);

            return [
                'success' => true
            ];

        // } catch (\Exception $e) {
        //     return [
        //         'error' => $e->getMessage()
        //     ];
        // }

    }

    public function saveBrandVersion($brand_id, $brand_version)
    {
        try {

            $brandVersion = new  BrandVersion();
            $brandVersion->version = $brand_version;
            $brandVersion->brand_id = $brand_id;
            $brandVersion->active = 1;
            $brandVersion->save();
            if ($brandVersion->save()) {
                return $brandVersion->id;
            }

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function updateBrandComment($brand_id, $comment)
    {
        try {

            $product = \App\Models\Brand::find($brand_id);
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

    public function browse(Request $request)
    {

        $this->validate($request, [
            'brand_id' => 'required',
            'category_id' => 'required',
            'version_id' => 'required',
        ]);

        $data = Item::where('brand_id', $request->brand_id)
            ->where('category_id', $request->category_id)
            ->where('version_id', $request->version_id)
            ->paginate(10000);

        return [
            'success' => TRUE,
            'body' => $data
        ];
    }

    // get versions

    public function update(Request $request)
    {
        $requestData = $request->all();
        $requestData['row'] = json_decode($requestData['row'], TRUE);
        $requestData['idsToDelete'] = json_decode($requestData['idsToDelete'], TRUE);
        $request->merge($requestData);

        $this->validate($request, [
            'brand_id' => 'required',
            'category_id' => 'required',
            'version_id' => 'required',
            'row.*.Fabric_Name' => 'required',
            'row.*.Price' => 'required|numeric',
            'row.*.Active' => 'required|boolean',
        ]);


        foreach ($requestData['row'] as $gridDatum) {

            if (!empty($gridDatum['ID'])) {
                $item = Item::find($gridDatum['ID']);
                if ($item === NULL) {
                    $item = new Item();
                }
            } else {
                $item = new Item();
            }

            $item->category_id = $requestData['category_id'];
            $item->brand_id = $requestData['brand_id'];
            $item->name = $gridDatum['Fabric_Name'];
            $item->price = $gridDatum['Price'];
            $item->active = $gridDatum['Active'];
            $item->version_id = ($requestData['version_id']) ? $requestData['version_id'] : NULL; //add version id
            $item->save();
        }

        if (!empty($requestData['idsToDelete'])) {
            Item::whereIn('id', $requestData['idsToDelete'])->delete();
        }

        $this->updateBrandComment($requestData['brand_id'], $requestData['brand_comment']);

        return [
            'success' => true
        ];

    }

    public function excelToJson(Request $request)
    {

        /*-- upload path --*/
        $destination = storage_path('/excel/');
        $extension = $request->file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;

        /*-- upload file full path --*/
        $FullPath = storage_path('/excel/' . $fileName);

        /*-- uploading file to given path --*/
        if ($request->file->move($destination, $fileName)) {
            $response = array(
                'success' => TRUE,
                'data' => (new FastExcel)->import($FullPath)
            );
            return json_encode($response);
        }
    }

    public function getVersion(Request $request)
    {
        try {

            $brand_versions = BrandVersion::where('brand_id', $request->brand_id)->get();
            if (!empty($brand_versions)) {
                return [
                    'success' => TRUE,
                    'versions' => $brand_versions
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    //Get Brand Comment

    public function indexBrand(Request $request)
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

            $brands = \App\Models\Brand::with('countries')->where('country_id', $session_id)->where('is_fabric', 1)->get();
            $brandsArray = array();
            foreach ($brands as $key => $brand) {
                $data['id'] = $brand->id;
                $data['name'] = $brand->name;
                $data['active'] = $brand->active;
                $data['type'] = $brand->type;
                $data['iso2_code'] = $brand->countries->iso2_code;
                $data['flag'] = $brand->countries->flag;
                //$data['count'] = Product::where('brand_id',$brand->id)->count();
                $data['is_fabric'] = $brand->is_fabric;
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

    //Update Brand Comment

    public function activeCategories(Request $request)
    {

        try {

            $categories = Category::where('is_active', '1')->where('is_fabric', 1)->get();

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

    //Save Brand Version

    public function getBrandComment($brand_id)
    {
        try {
            $brand = \App\Models\Brand::find($brand_id);
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

    public function updateVersion(Request $request)
    {
        try{
            $brand = BrandVersion::where('id', $request->version_id)->where('brand_id', $request->brand_id)->update(['version' => $request->version_name]);
            return [
                'success' => TRUE,
                'brand' => $brand
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function deleteVersion(Request $request)
    {
        try{

            DB::table('item_settings')
                ->join('items', 'item_settings.item_id', '=', 'items.id')
                ->where('items.brand_id', $request->brand_id)
                ->where('items.version_id', $request->version_id)
                ->delete();

            Item::where('version_id', $request->version_id)->where('brand_id', $request->brand_id)->delete();
            $brand = BrandVersion::where('id', $request->version_id)->delete();

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
}
