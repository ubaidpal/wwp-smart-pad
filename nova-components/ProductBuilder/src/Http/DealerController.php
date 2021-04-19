<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\AccountBrands;
use App\Models\AccountSMSCredits;
use App\Models\AccountSMSCreditsLogs;
use App\Models\AccountType;
use App\Models\Accounts;
use App\Models\Company;
use App\Models\Employees;
use App\Models\EmployeesRoles;
use App\Models\Folders;
use App\Models\MessageLoggable;
use App\Repository\admin\DealerRepository;
use App\Rules\UniqueUsername;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;


class DealerController extends Controller
{

    protected $DealerRepository;
    protected $user_id;
    protected $Accounts;

    public function __construct(
        Accounts $Accounts,
        DealerRepository $DealerRepository
    )
    {
        $this->Accounts = $Accounts;
        $this->DealerRepository = $DealerRepository;
        $this->user_id = \Auth::id();
    }


    public function index(Request $request, $folder_id = 0)
    {
        $accounts = $this->DealerRepository->index($request, $folder_id);
        return [
            'success' => TRUE,
            'accounts' => $accounts
        ];

    }


    /**
     * Get Dealer Status
     * **/
    public function getDealerStatus()
    {
        $dealerStatus = dealerStatus();
        return [
            'success' => TRUE,
            'dealerStatus' => $dealerStatus
        ];

    }

    /**
     * Save Dealer Status
     * **/
    public function saveDealerStatus(Request $request)
    {
        $status = Accounts::findOrFail($request->dealerId);
        if (isset($status->id)) {
            $status->status = $request->statusId;
            $status->save();
            return [
                'success' => TRUE,
                'status' => $status
            ];
        } else {
            return [
                'success' => False,
            ];
        }


    }

    public function getActiveCountry()
    {
        try {
            $countries = $this->DealerRepository->getActiveCountry();
            return [
                'success' => true,
                'countries' => $countries
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }


    }

    public function addAccounts(Request $request)
    {

        if (!isset($request->id)) {
            $this->validate($request, [
                'company_name' => 'required',
                'account_type' => 'required',
                'status' => 'required',
                'email' => 'required|email|max:255|unique:accounts',
//                'username' => 'required|min:4|unique:accounts,username',
                'username' => ['required','min:4', new UniqueUsername()]
            ]);
        } else {
            $this->validate($request, [
                'company_name' => 'required',
                'account_type' => 'required',
                'status' => 'required'
            ]);
        }

        try {

            if (!isset($request->id)) {
                $accounts = Accounts::where('email', $request->email)->first();
                $employee = Accounts::where('username', $request->username)->first();
                if (isset($accounts->id)) {
                    $message = $accounts->email . ' email is already exist Please try another one.';
                    return [
                        'error' => 1,
                        'message' => $message,
                    ];
                } else if (isset($employee->id)) {
                    $message = 'This' . $accounts->username . ' username is already exist Please try another one.';
                    return [
                        'error' => 1,
                        'message' => $message,
                    ];
                } else {

                    $accounts = $this->DealerRepository->addAccounts($request, \Auth::id());
                    if (!empty($accounts)) {
                        return [
                            'success' => true,
                            'message' => 'Email Send Successfully...',
                            'accounts' => $accounts
                        ];

                    }
                }

            } else {

                $accounts = $this->DealerRepository->addAccounts($request, \Auth::id());
                if (!empty($accounts)) {
                    return [
                        'success' => true,
                        'message' => 'Account Updated Successfully...',
                        'accounts' => $accounts
                    ];

                }
            }


        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }


    }

    public function addEmployeeAccounts(Request $request)
    {

        if (!isset($request->id)) {
            $this->validate($request, [
                'email' => 'required|email|max:255|unique:employees',
                'username' => 'required|min:4|unique:employees,username',
                'password' => 'required|min:6',
                'confirmed_password' => 'sometimes|required_with:password',
            ]);
        }

        try {
            if (!isset($request->id)) {

                $employee = Employees::where('username', $request->username)->orWhere('email', $request->email)->first();
                if (isset($accounts->email)) {
                    $message = $employee->email . ' email is already exist Please try another one.';
                    return [
                        'error' => 1,
                        'message' => $message,
                    ];
                } else if (isset($accounts->username)) {
                    $message = $employee->username . ' username is already exist Please try another one.';
                    return [
                        'error' => 1,
                        'message' => $message,
                    ];
                } else {

                    $employees = $this->DealerRepository->addEmployeeAccounts($request);
                    $message = 'Employee Add Successfully...';
                    $employee = $employees;
                    return [
                        'success' => TRUE,
                        'message' => $message,
                        'employee' => $employee
                    ];
                }
            } else {

                $employees = $this->DealerRepository->addEmployeeAccounts($request);
                $message = 'Employee Updated Successfully...';
                $employee = $employees;
                return [
                    'success' => TRUE,
                    'message' => $message,
                    'employee' => $employee
                ];
            }
        } catch (\Exception $e) {

            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function signUpDealer($activation_token = Null)
    {
        echo '<pre>';
        print_r('Please Kevin write your Dealer Sign Up Code here');
        echo '<pre>';
        print_r('Your activation Token is');
        echo '<pre>';
        print_r($activation_token);
        die;
    }

    //Delete Accounts
    public function destroyAccounts(Request $request)
    {

        try {
            $employee = Employees::where('account_id', $request->resourceId)->get();
            if (!empty($employee)) {
                foreach ($employee as $employees) {
                    $role = EmployeesRoles::where('employee_id', $employees->id)->delete();
                    if (isset($role)) {
                        Employees::where('id', $employees->id)->delete();
                    } else {
                        Employees::where('id', $employees->id)->delete();
                    }

                }
                $accounts = Accounts::where('id', $request->resourceId)->delete();
            } else {
                $accounts = Accounts::where('id', $request->resourceId)->delete();
            }

            return [
                'success' => true,
                'employee' => $accounts
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    // delete Employess
    public function destroyEmployee(Request $request)
    {

        try {

            $employee = Employees::where('id', '=', $request->resourceId)->delete();
            return [
                'success' => true,
                'employee' => $employee
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }


    public function getEmployeeRecord(Request $request)
    {

        try {
            $employee = $this->DealerRepository->getEmployeeRecord($request);
            return [
                'success' => true,
                'employee' => $employee
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }


    }

    public function getCompanies($account_id)
    {
        try {
            $companies = Company::where('account_id', $account_id)->get();
            return [
                'success' => true,
                'companies' => $companies
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function updateAccountStatus(Request $request)
    {
        try {

            $account = $this->DealerRepository->updateAccountStatus($request);
            return [
                'success' => true,
                'categories' => $account
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function updateEmployeeStatus(Request $request)
    {
        try {

            $employee = $this->DealerRepository->updateEmployeeStatus($request);
            return [
                'success' => true,
                'categories' => $employee
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function dealerWithCompanies(Request $request)
    {
        $accounts = $this->DealerRepository->dealerWithCompanies($request);
        return [
            'success' => TRUE,
            'accounts' => $accounts
        ];

    }

    /*
     * Update the brand access for a specific dealer for a specific brand
     * */
    public function updateAccountBrand(Request $request)
    {
        try {
            $type = $request->type;
            $active = $request->active;
            $account = AccountBrands::where('account_id', $request->account_id)->where('brand_id', $request->brand_id)->first();
            if (!isset($account->id)) {
                $account = new AccountBrands();
                $account->brand_id = $request->brand_id;
                $account->account_id = $request->account_id;
            }

            $account->$type = $active;
            if ($type == 'dealer_admin' && $active) {
                $account->main_admin = false;
            } else if ($type == 'main_admin' && $active) {
                $account->dealer_admin = false;
            }
            $account->save();

            return [
                'success' => true
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    /*
 * Update the brand access for a specific dealer for a specific brand
 * */
    public function updateAccountExclusiveBrand(Request $request)
    {
        try {
            $active = $request->active;
            $account = AccountBrands::where('account_id', $request->account_id)->where('brand_id', $request->brand_id)->first();
            if (!isset($account->id)) {
                if ($active) {
                    $account = new AccountBrands();
                    $account->brand_id = $request->brand_id;
                    $account->account_id = $request->account_id;
                    $account->save();
                }
            } else if (!$active) {
                $account->delete();
            }

            return [
                'success' => true
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    /*
     * Update Brand Access to dealers
     * @param: $type: contains the type whether it is brand access, main admin or dealer admin
     *
     * */
    public function updateAccountBrandInBulk(Request $request)
    {
        try {
            $type = $request->type;
            $active = $request->active;
            $brands = $request->brands;
            $brands = explode(',', $brands);
            foreach ($brands as $key => $brand) {
                $account = AccountBrands::where('account_id', $request->account_id)->where('brand_id', $brand)->first();
                if (!isset($account->id)) {
                    $account = new AccountBrands();
                    $account->brand_id = $brand;
                    $account->account_id = $request->account_id;
                }

                $account->$type = $active;
                $account->save();
            }

            return [
                'success' => TRUE
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }


    public function getBrandAccounts($brand_id)
    {
        try {

            $accounts = AccountBrands::with('dealer')->where('brand_id', $brand_id)->get();
            $dealersArray = array();
            foreach ($accounts as $account) {
                $data['id'] = $account->dealer->id;
                $data['email'] = $account->dealer->email;
                $data['max_employees'] = $account->dealer->max_employees;
                $data['max_dealers'] = $account->dealer->max_dealers;
                $data['max_companies'] = $account->dealer->max_companies;
                $data['status'] = $account->dealer->status;
                $data['created_at'] = ($account->dealer->created_at) ? Carbon::parse($account->created_at)->format('d-m-Y') : 'Date Note Found..';
                array_push($dealersArray, $data);
            }

            return [
                'success' => TRUE,
                'accounts' => $dealersArray
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getDealerInfo($account_id)
    {
        try{
            return [
                'success' => true,
                'account' => Accounts::where('id',$account_id)->first(),
                'account_sms' => AccountSMSCredits::where('account_id',$account_id)->first(),
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function addDealerFolder(Request $request)
    {
        try {
            $folder_id = 0;
            $folder = null;
            if ($request->folder_id > 0) {
                $folder_id = $request->folder_id;
            } else {
                $folder = new Folders();
                $folder->name = $request->name;
                $folder->parent_id = $request->parent_id;
                $folder->save();
                $folder_id = $folder->id;
            }

            $response = $this->DealerRepository->createFolderAddDealers($request, $folder_id);
            return [
                'success' => true,
                'folder' => $folder,
                'response' => $response
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getFolders(Request $request, $parent_id = 0)
    {
        try {
//            echo '<pre>'; print_r($parent_id); echo '</pre>';
            $folder = Folders::where('parent_id', $parent_id)->get();
            return [
                'success' => true,
                'folders' => $folder
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    //Delete Folder
    public function destroyFolder(Request $request)
    {

        try {

            Accounts::where('folder_id', $request->resourceId)->update(['folder_id' => null]);
            $folder = Folders::where('id', $request->resourceId)->delete();

            return [
                'success' => true,
                'folder' => $folder
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }


    public function dealers(Request $request)
    {
        $accounts = $this->DealerRepository->dealers();
        return [
            'success' => TRUE,
            'accounts' => $accounts
        ];

    }

    public function getAccountTypes(Request $request)
    {

        try {
            $types = AccountType::where('active', 1)->get();

            return [
                'success' => true,
                'types' => $types
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getAllFolders(Request $request)
    {
        try {

            $folders = Folders::all();
            $data = array();
            $filter = $folders->where('parent_id', 0);
            foreach ($filter as $folder) {
                $fold['id'] = $folder->id;
                $fold['name'] = $folder->name;
                $fold['parent_id'] = $folder->parent_id;
                array_push($data, $fold);
                $child = $folders->where('parent_id', $folder->id);
                foreach ($child as $sub) {
                    $sfold['id'] = $sub->id;
                    $sfold['name'] = $folder->name . ' > ' . $sub->name;
                    $sfold['parent_id'] = $sub->parent_id;
                    array_push($data, $sfold);
                    $schild = $folders->where('parent_id', $sub->id);
                    foreach ($schild as $sub) {
                        $sbfold['id'] = $sub->id;
                        $sbfold['name'] = $folder->name . ' >> ' . $sub->name;
                        $sbfold['parent_id'] = $sub->parent_id;
                        array_push($data, $sbfold);
                    }
                }
            }
            return [
                'success' => true,
                'folders' => $data
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getDealers($brand_id)
    {
        $accounts = $this->DealerRepository->getDealers();
        $accountFinal = array();
        foreach ($accounts as $account) {
            $data['id'] = $account->id;
            $data['email'] = $account->email;
            $data['company_name'] = $account->company_name;
            $status = AccountBrands::where('account_id', $account->id)->where('brand_id', $brand_id)->get();
            if ($status->count() > 0) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            if ($account->created_at) {
                $data['created_at'] = Carbon::parse($account->created_at)->format('d/m/Y');
            } else {
                $data['created_at'] = 'Not Available';
            }

            array_push($accountFinal, $data);
        }
        return [
            'success' => TRUE,
            'accounts' => $accountFinal
        ];

    }


    public function updateBrandAccountInBulk(Request $request)
    {
        try {

            $active = $request->active;
            $accounts = explode(',', $request->account_ids);
            foreach ($accounts as $key => $accountId) {

                $account = AccountBrands::where('account_id', $accountId)->where('brand_id', $request->brand)->first();
                if (!isset($account->id)) {
                    if ($active) {
                        $account = new AccountBrands();
                        $account->brand_id = $request->brand;
                        $account->account_id = $accountId;
                        $account->save();
                    }
                } else if (!$active) {
                    $account->delete();
                }

            }


            return [
                'success' => true
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getNotSlave(Request $request)
    {

        try {
            $accounts = Accounts::where('master_slave', 'master')->get();

            return [
                'success' => true,
                'accounts' => $accounts
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    /*
     * Set Refferal Token for a particular dealer
     * */
    public function setRefferalToken($id, Request $request)
    {
        $dealer = Accounts::where('id', $id)->first();
        if (isset($dealer->id)) {
            if ($request->has('token')) {
                $token = $request->token;
                $dealer->referral_token = $token;
                $dealer->save();
                return [
                    'success' => TRUE,
                    'error' => ''
                ];
            } else {
                return [
                    'success' => TRUE,
                    'error' => 'No Token Found'
                ];
            }
        }
    }

    public function getSMSLogs($id, MessageLoggable $smsLogs, AccountSMSCreditsLogs $smsCreditLogs)
    {
        return [
                'sms_logs' => $smsLogs->where('account_id', $id)->where('type', 'sms')->orderBy('id', 'desc')->get(),
                'sms_credit_logs' => $smsCreditLogs->where('account_id', $id)->orderBy('id', 'desc')->get(),
                'sms_fee' => \Config::has('constants_settings.sms_fee') ? \Config::get('constants_settings.sms_fee') : '0.7'
                ];
    }
}
