<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    /*
    * index
    * It will return list of companies
    * @return
    */

    public function index(Request $request)
    {
        $companies = Company::all();
        return [
            'success' => TRUE,
            'companies' => $companies
        ];

    }

    /*
    * destroyCompany
    * it will delete or destroy company data
    * @resourceId
    * @return
    */

    public function destroyCompany(Request $request)
    {
        try {

            $company = Company::where('id', '=', $request->resourceId)->delete();
            return [
                'success' => true,
                'companies' => $company
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

}
