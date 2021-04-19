<?php

/**
 * Created by   :  Ubaid
 * Project Name : Smart Pad Pro
 * Product Name : PhpStorm
 * Date         : 1-29-2019 4:14 PM
 * File Name    : DealerRepository.php
 */

namespace App\Repository\admin;
use App\Jobs\SendAccountsEmail;
use App\Models\AccountSMSCredits;
use App\Models\AccountSMSCreditsLogs;
use App\Models\Accounts;
use App\Models\CompanyEmployees;
use App\Models\Country;
use App\Models\Employees;
use App\Models\EmployeesRoles;
use App\Models\Folders;
use App\Models\Role;
use App\Observers\AccountsObserver;
use App\User;
use Carbon\Carbon;
use DemeterChain\A;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Mail;
use Repository\admin\Eloquent\Repository;
class DealerRepository extends Repository
{

    function model() {
        return 'App\User';
    }

    public function index($request = Null,$folder_id=0) {

        $country_id = $this->getCountry();
        if($folder_id > 0){
            $folders = $this->getAllFolders($folder_id);
            $accounts = Accounts::whereIn('folder_id',$folders)->where('country_id',$country_id)->get();
        } else {
            $accounts = Accounts::where('country_id',$country_id)->get();
        }

        $accounts = $accounts->load('countries' , 'users' ,'employees','folder','accountType');
        $grouped = $accounts->groupBy('folder_id');
        $finalArray = array();

        foreach ($grouped as $dealer){

            $parentArray = array();
            $parentArray['mode'] =  "span";
            $parentArray['html'] =  false;
            /**/
            $childArray = array();
            $folder_name = 'Unfoldered';
            $folder = 0;
            $parentFolder = 0;
            /**/
            if(!empty($dealer)){
                foreach ($dealer as $key => $account){

                    if($key == 0 && $account->folder){
                        if($account->folder->parent_id == 0 || $account->folder->parent_id == null){
                            $folder_name = $account->folder->name;
                        } else {
                            $filtered = Folders::where('id', $account->folder->parent_id)->first();
                            if($filtered){
                                $folder_name = $filtered->name.' -> '.$account->folder->name;
                            }
                        }
                        $folder = $account->folder->id;
                        $parentFolder = $account->folder->parent_id;
                    }

                    // get the Administrator accounts
                    $adminEmployeeCredential = Employees::where('account_id' , $account->id)->first();
                    if(isset($adminEmployeeCredential->id)){

                        $adminEmployeeRoles = EmployeesRoles::where('employee_id' , $adminEmployeeCredential->id)->first();

                        if(isset($adminEmployeeRoles->role_id)){

                            $roles =  Role::findOrFail($adminEmployeeRoles->role_id);
                            if($roles->role == \Config::get('constants_settings.SUPER_ADMIN')){
                                $data[ 'user_name' ]     = ucfirst($adminEmployeeCredential->username);
                                $data[ 'is_calender' ]  = $adminEmployeeCredential->is_calendar;
                            }

                        }
                    }

                    // sms credits
                    $sms_credits = AccountSMSCredits::where('account_id', $account->id)->first();

                    $data[ 'id' ]              = $account->id;
                    $data[ 'email' ]           = $account->email;
                    $data[ 'first_name' ]      = setStringReturn($account->first_name);
                    $data[ 'last_name' ]       = setStringReturn($account->last_name);
                    $data[ 'company_name' ]    = setStringReturn($account->company_name);
                    $data[ 'company_phone' ]   = setStringReturn($account->company_phone);
                    $data[ 'city' ]            = setStringReturn($account->city);
                    $data[ 'state' ]           = setStringReturn($account->state);
                    $data[ 'postal_code' ]     = setStringReturn($account->postal_code);
                    $data[ 'username' ]        = setStringReturn($account->username);
                    $data[ 'master_slave' ]    = setStringReturn($account->master_slave);
                    $data[ 'account_type' ]    = $account->accountType?setStringReturn($account->accountType->name):'Not Defined';
                    $data[ 'payment_plan' ]    = setStringReturn($account->payment_plan);
                    $data[ 'address' ]         = setStringReturn($account->address);
                    $data[ 'company_address' ] = setStringReturn($account->company_address);
                    $data[ 'status' ]          = setStringReturn($account->status);
                    $data[ 'sms_credits' ]     = $sms_credits ? $sms_credits->sms_credits : 0;
                    $data[ 'max_employees' ]   = setStringReturn($account->max_employees);
                    $data[ 'max_dealers' ]     = $account->max_dealers ? $account->max_dealers : 0;
                    $data[ 'max_companies' ]   = setStringReturn($account->max_companies);
                    $data[ 'created_at' ]      = ($account->created_at) ? Carbon::parse($account->created_at)->format('d-m-Y') : 'not found';
                    $data[ 'country_name' ]    = $account->countries->name;
                    $data[ 'countries_name' ]  = $account->countries->id;
                    $data[ 'iso2_code' ]       = $account->countries->iso2_code;
                    $data[ 'flag' ]            = $account->countries->flag;

                    $data[ 'employees' ] = $account->employees; // get child employee
                    array_push($childArray, $data);
                }
            }
            $parentArray['label'] =  $folder_name;
            $parentArray['folder_id'] =  $folder;
            $parentArray['parent_folder'] =  $parentFolder;
            $parentArray['children'] = $childArray;
            array_push($finalArray, $parentArray);
            /**/

        }

        $collection = collect($finalArray);
        if($folder_id == 0){
            $parentsOnly = $collection->where('parent_folder', 0);
        } else {
            $parentsOnly = $collection;
        }
        $awesomeArray = array();
        $counter = 0;
        foreach($parentsOnly as $datasort){

            if($folder_id > 0){
                array_push($awesomeArray, $datasort);
            } else {

                if($datasort['folder_id'] != $datasort['parent_folder']){
                    array_push($awesomeArray, $datasort);
                    $filtered = $collection->where('parent_folder', $datasort['folder_id']);
                    if($filtered != null){
                        foreach ($filtered as $sub){
                            array_push($awesomeArray, $sub);
                            $subfiltered = $collection->where('parent_folder', $sub['folder_id']);
                            if($subfiltered != null){
                                foreach ($subfiltered as $subsub){
                                    array_push($awesomeArray, $subsub);
                                }
                            }
                        }
                    }
                }
                if($folder_id == 0 ){
                    $counter++;
                    if($parentsOnly->count() == $counter){
                        $unfolder = $collection->where('parent_folder', 0)->where('folder_id', 0)->first();
                        array_push($awesomeArray, $unfolder);
                    }
                }
            }
        }



        return  $awesomeArray;
    }
    public function addAccounts($request, $user_id) {
        // add Admin Accounts and Employee account
        $input = $request->all();
        return $this->emailSend($input ,$user_id);
    }
    public function getActiveCountry() {

        $countries = Country::with('currency')->where('active' , 1)->get();
        $countriesArray = array();
        foreach ($countries as $key => $country){
            $data[ 'id' ]              = $country->id;
            $data[ 'name' ]            = $country->name;
            $data[ 'flag' ]            = $country->flag;
            $data[ 'iso2_code' ]       = $country->iso2_code;
            $data[ 'currency' ]        = $country->currency->code;
            $data[ 'measure_system' ]  = $country->measure_system;
            $data[ 'currency_symbol' ] = $country->currency_symbol;
            $data[ 'date_format' ]     = $country->date_format;
            $data[ 'tax' ]             = $country->tax;
            $data[ 'active' ]          = $country->active;
            array_push($countriesArray, $data);
        }

        return $countriesArray;

    }
    public function emailSend(array $data ,$user_id) {

        $five_days_from_now = \Carbon\Carbon::now()->addDay(5);
        if(isset($data[ 'account_id' ]) && $data[ 'account_id' ] > 0){
            $accounts               = Accounts::findOrFail($data[ 'account_id' ]);
            $accounts->password     = bcrypt('password123');
        }else{
            $accounts           = new Accounts();
            $accounts->password = bcrypt('password123');
        }

        $accounts->account_type = $data[ 'account_type' ];
        $accounts->notes = $data[ 'notes' ];
        $accounts->master_slave = $data[ 'master_slave' ];
        $accounts->first_name        = $data[ 'first_name' ];
        $accounts->last_name         = $data[ 'last_name' ];
        $accounts->company_name      = $data[ 'company_name' ];
        $accounts->address           = $data[ 'address' ];
        $accounts->company_address   = $data[ 'company_address' ];
        $accounts->company_phone     = $data[ 'company_phone' ];
        $accounts->status            = ($data[ 'status' ]) ? $data[ 'status' ] : NULL;
        $accounts->city              = $data[ 'city' ];
        $accounts->state             = $data[ 'state' ];
        $accounts->postal_code       = $data[ 'postal_code' ];
        $accounts->email             = $data[ 'email' ];
        $accounts->activation_token  = str_random(30);
        $accounts->token_expiry_date = $five_days_from_now;
        $accounts->username          = $data[ 'username' ];
        ////$accounts->payment_method             = \GuzzleHttp\json_encode($data[ 'payment_method' ]);
        $accounts->max_employees = $data[ 'max_employees' ];
        $accounts->max_dealers   = $data[ 'max_dealers' ];
        $accounts->max_companies = $data[ 'max_companies' ];
        $accounts->country_id    = $data[ 'country_id' ];
        $accounts->master_id    = $data[ 'master_id' ];
        $accounts->has_admin_dashboard    = $data[ 'has_admin_dashboard' ];
        $accounts->price_controlled_by_master    = (isset($data[ 'price_controlled_by_master' ])) ? $data[ 'price_controlled_by_master' ]:0;
        $accounts->created_by    = $user_id;
        $accounts->updated_by    = $user_id;
        if(!empty($data[ 'trial_date' ]) && $data[ 'master_slave' ] == 'trial'){
            $accounts->trial_date    = Carbon::parse($data[ 'trial_date' ])->format('Y-m-d');
        }
        $accounts->save();

        // sms credits
        if(isset($data['sms_credits_topup'])){
            $action = 'created_by';
            $sms_credits = new AccountSMSCredits();
            if(isset($data['account_id']))
            {
                $sms_credits = AccountSMSCredits::where('account_id', $data[ 'account_id' ])->first();
                if($sms_credits)
                {
                    $action = 'updated_by';
                }else
                {
                    $sms_credits = new AccountSMSCredits();
                }
            }

            // sms credit logs
            $sms_credit_logs = new AccountSMSCreditsLogs();
            $sms_credit_logs->current       = $sms_credits->sms_credits ? $sms_credits->sms_credits : 0;
            $sms_credit_logs->topup         = @$data['sms_credits_topup'];
            $sms_credit_logs->account_id    = $accounts->id;
            $sms_credit_logs->created_by    = auth()->id();
            $sms_credit_logs->updated_by    = auth()->id();
            $sms_credit_logs->save();

            $sms_credits->account_id                    = $accounts->id;
            $sms_credits->sms_credits                   += $data['sms_credits_topup'];
            $sms_credits->sms_credits_recurring         = $data[ 'sms_credits_recurring' ];
            $sms_credits->sms_credits_recurring_start   = Carbon::parse($data[ 'sms_credits_recurring_start' ])->format('Y-m-d');
            if($action == 'created_by')
            {
                $sms_credits->created_by                = auth()->id();
            }
            $sms_credits->updated_by                    = auth()->id();
            $sms_credits->save();
        }


        /*Add Default Setting Of Dealer*/
        if($data[ 'account_id' ] == 0){
            $roleID = $this->createAdminRoleForDealer($accounts->id);
            if($data['master_slave'] != 'master'){
                // master employees attach role id of this dealer
                if($data[ 'master_id' ] != 0){
                    $this->attachMasterEmployees($data['master_id'], $roleID);
                }
            }
            $this->dealerDefaultSettings($accounts->id, $data['master_slave']);
        }
        /**/
        if($data[ 'account_id' ] == 0){
            $emp              = Employees::where('email', $data[ 'email' ])->first();
            if(isset($data[ 'account_id' ])) {

                if(isset($data[ 'account_id' ]) && $data[ 'account_id' ] > 0 ){
                    $employee         = Employees::findOrFail($emp->id);
                    $employee->status = $data[ 'status' ];
                } else {
                    $employee         = new Employees();
                    $employee->status = 'active';
                    $employee->password         = bcrypt('password123');
                    $employee->username         = $data[ 'email' ];
                }

                $employee->first_name       = $data[ 'first_name' ];
                $employee->last_name        = $data[ 'last_name' ];
                $employee->email            = $data[ 'email' ];
                $employee->text_color       = '#FFFFFF';
                $employee->background_color = '#000000';
                $employee->account_id       = $accounts->id;
                $employee->save();
            }


            if(!$emp)
            {
                $this->dealerDefaultSecondEmployee($data,$accounts->id);
            }
        }


        if(isset($data[ 'id' ])){
            return $accounts;
        }else{
            return  [
                'email_send' => $this->sendEmail($accounts->id ,$five_days_from_now ,$data[ 'password' ]),
                'accounts'  =>$accounts
            ];
        }

    }

    //
    public function dealerDefaultSecondEmployee($data,$account_id)
    {
        $emailBreak = explode("@",$data[ 'email' ]);
        $employee                   = new Employees();
        $employee->status           = 'active';
        $employee->is_appointee     = 0;
        $employee->first_name       = 'Smartpad Pro';
        $employee->last_name        = 'Admin';
        $employee->email            = $data[ 'username' ].'@smartpadpro.com.au';
        $employee->password         = bcrypt('Smartpadpro12!');
        $employee->text_color       = '#FFFFFF';
        $employee->background_color = '#000000';
        $employee->username         = $data[ 'username' ];
        $employee->maximum_discount   = 200;
        $employee->main_admin_acceess = true;
        $employee->account_id       = $account_id;
        $employee->save();
        //
        $role = Role::where('account_id',$account_id)->first();
        $newEmployeeRoles              = new EmployeesRoles();
        $newEmployeeRoles->role_id     = $role->id;
        $newEmployeeRoles->employee_id = $employee->id;
        $newEmployeeRoles->account_id = $account_id;
        $newEmployeeRoles->save();

    }

    public function addEmployeeAccounts($request) {

        if(isset($request->id)){
            $employee                   = Employees::findOrFail($request->id);
        }else{
            $employee           = new Employees();
            $employee->email    = $request->email;
            $employee->username = $request->username;
            $employee->password = bcrypt($request->password);
        }

        $employee->first_name       = $request->first_name;
        $employee->last_name        = $request->last_name;
        $employee->status           = $request->status;
        $employee->text_color       = '#FFFFFF';
        $employee->background_color = '#FFFFFF';
        $employee->account_id       = $request->account_id;
        $employee->save();
        /**/
        if($request->company_id){
            $companyEmployee = new CompanyEmployees();
            $companyEmployee->employee_id = $employee->id;
            $companyEmployee->company_id  = $request->company_id;
            $companyEmployee->save();

        }
        /**/
        if($employee->save()){
            $data = array(
                'email'            => $employee->email,
                'username'         => $employee->username,
                'password'         => $request->password,
                'url' => '/nova-vendor/ProductBuilder/signUpDealer/',
            );
            if(!isset($request->id)){
                $job = (new SendAccountsEmail($employee, $data))
                    ->delay(Carbon::now()->addSecond(2));
                dispatch($job);
                return $employee;

            }else{
                return $employee;
            }




        }
    }
    public function sendEmail($accounts_id  , $five_days_from_now ,$random_password) {

        $accounts = Accounts::where('id' , $accounts_id)->where('token_expiry_date', '<', $five_days_from_now)->first();

        if(isset($accounts->id)){
            $employees = Employees::where('account_id' ,$accounts_id)->first();
            if(isset($employees->id)) {
                //= Role::where('role',  'Administrator')->first();
                $role = Role::where('account_id',$accounts_id)->first();
                $newEmployeeRoles              = new EmployeesRoles();
                $newEmployeeRoles->role_id     = $role->id;
                $newEmployeeRoles->employee_id = $employees->id;
                $newEmployeeRoles->account_id = $accounts_id;
                $newEmployeeRoles->save();
//                $data = array(
//                    'email'            => $accounts->email,
//                    'username'         => $employees->username,
//                    'password'         => $random_password,
//                    'url' => '/nova-vendor/ProductBuilder/signUpDealer/'.$accounts->activation_token,
//                );
//
//                $job = (new SendAccountsEmail($accounts, $data))
//                    ->delay(Carbon::now()->addSecond(2));
//                dispatch($job);
            }

        }

        return 1;

    }

    public function createAdminRoleForDealer($accounts_id)
    {
        $dealer = new Role();
        $dealer->role = 'Administrator';
        $dealer->all_access = 1;
        $dealer->account_id = $accounts_id;
        $dealer->created_by = Auth::user()->id;
        $dealer->updated_by = Auth::user()->id;
        $dealer->save();
        return $dealer->id;

    }

    public function getEmployeeRecord($request) {
        $adminEmployeeCredential = Employees::where('account_id' , $request->account_Id)->get();
        if(!empty($adminEmployeeCredential)){
            foreach ($adminEmployeeCredential as $employee){
                $adminEmployeeRoles = EmployeesRoles::where('employee_id' , $employee->id)->first();
                if(isset($adminEmployeeRoles->role_id)){

                    $roles =  Role::findOrFail($adminEmployeeRoles->role_id);
                    if($roles->role == \Config::get('constants_settings.SUPER_ADMIN')){
                       return   Employees::findOrFail($adminEmployeeRoles->employee_id);

                    }

                }
            }
        }
    }

    public function updateAccountStatus($request) {

        $account         = Accounts::find($request->resourceId);
        $status          = ($request->current_status == '1') ? '0' : '1';
        $account->status = $status;
        $account->save();
        return $account;
    }
    public function updateEmployeeStatus($request) {
        $employee         = Employees::find($request->resourceId);
        $status           = ($request->current_status == '1') ? '0' : '1';
        $employee->status = $status;
        $employee->save();
        return $employee;
    }

    public function dealerWithCompanies($request = Null) {

        $accounts = Accounts::with('employees','employees.companyInfo.company')->get();

        return  $accounts;
    }

    public function dealerDefaultSettings($account_id, $accountType = 'standard')
    {
        $user_id = ((Auth::user()->id) > 0) ? Auth::user()->id:1;

        if($accountType != 'master'){
            //CONFIGURABLE Settings
            DB::table('configurable')->insert(
               array(
                   array(
                       'group' => 'scheduler_date_range',
                       'name' => 'start',
                       'value' => '08:00',
                       'configurable_type' => 'App\Models\Account\Account',
                       'configurable_id' => $account_id,
                       'created_by' => $user_id
                   ),
                   array(
                       'group' => 'scheduler_date_range',
                       'name' => 'end',
                       'value' => '17:00',
                       'configurable_type' => 'App\Models\Account\Account',
                       'configurable_id' => $account_id,
                       'created_by' => $user_id
                   )
               )
            );
            $date_format = ['php' => 'd/m/Y', 'js' => 'dd/mm/YYYY'];
            $time_format = ['php' => 'h:i A', 'js' => 'hh:mm A'];
            DB::table('configurable')->insert(
                array(
                    array(
                        'group'           => 'date_format',
                        'name'            => 'date',
                        'value'           => serialize($date_format),
                        'configurable_id' => $account_id,
                        'created_by'      => $user_id
                    ),
                    array(
                        'group'           => 'date_format',
                        'name'            => 'date',
                        'value'           => serialize($time_format),
                        'configurable_id' => $account_id,
                        'created_by'      => $user_id
                    )
                )
            );
            //APPOINTMENT STATUS Settings
            DB::table('appointment_statuses')->insert(
                array(
                    array(
                        'name'             => 'Confirmed',
                        'background_color' => '#5cb85c',
                        'text_color'       => 'white',
                        'account_id'       => $account_id,
                        'required'         => TRUE,
                        'is_active'        => TRUE,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'name'             => 'Declined',
                        'background_color' => '#d9534f',
                        'text_color'       => 'white',
                        'account_id'       => $account_id,
                        'required'         => TRUE,
                        'is_active'        => TRUE,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'name'             => 'Pending',
                        'background_color' => '#8c8e9a',
                        'text_color'       => 'white',
                        'account_id'       => $account_id,
                        'required'         => TRUE,
                        'is_active'        => TRUE,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'name'             => 'Re-Schedule',
                        'background_color' => '#f78f01',
                        'text_color'       => 'white',
                        'account_id'       => $account_id,
                        'required'         => TRUE,
                        'is_active'        => TRUE,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    )
                )
            );
            //ORDER STATUSES Settings
            DB::table('order_statuses')->insert(
                array(
                    array(
                        'name'             => 'New Order',
                        'background_color' => '#0db7dd',
                        'text_color'       => 'white',
                        'account_id'       => $account_id,
                        'required'         => TRUE,
                        'is_active'        => TRUE,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'name'             => 'Completed',
                        'background_color' => '#5cb85c',
                        'text_color'       => 'white',
                        'account_id'       => $account_id,
                        'required'         => TRUE,
                        'is_active'        => TRUE,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    )
                )
            );
            //QUOTE STATUSES Settings
            DB::table('quote_statuses')->insert(
                array(
                    'name'             => 'Good',
                    'background_color' => '#358ca7',
                    'text_color'       => '#fff',
                    'account_id'       => $account_id,
                    'immutable'        => 0,
                    'is_active'        => 1,
                    'created_by'       => $user_id,
                    'updated_by'       => $user_id
                )
            );
            $status_id = DB::table('quote_statuses')->insertGetId(
                array(
                    'name'             => 'Lost',
                    'background_color' => '#d80000',
                    'text_color'       => '#fff',
                    'account_id'       => $account_id,
                    'immutable'        => 0,
                    'is_active'        => 1,
                    'created_by'       => $user_id,
                    'updated_by'       => $user_id
                )
            );
            DB::table('quote_statuses')->insert(
                array(
                    array(
                        'name'             => 'Hot Lead',
                        'background_color' => '#10f603',
                        'text_color'       => '#050505',
                        'account_id'       => $account_id,
                        'immutable'        => 0,
                        'is_active'        => 1,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'name'             => 'Cold Lead',
                        'background_color' => '#0307f6',
                        'text_color'       => '#fdf9f9',
                        'account_id'       => $account_id,
                        'immutable'        => 0,
                        'is_active'        => 1,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    )
                ,
                    array(
                        'name'             => 'Follow up',
                        'background_color' => '#f603eb',
                        'text_color'       => '#fdf9f9',
                        'account_id'       => $account_id,
                        'immutable'        => 0,
                        'is_active'        => 1,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    )
                )
            );
            //QUOTE STATUSES Reasons
            DB::table('quote_status_reasons')->insert(
                array(
                    array(
                        'reason'           => 'Too Expensive',
                        'background_color' => '#f60505',
                        'text_color'       => '#FFF',
                        'quote_status_id'  => $status_id,
                        'is_active'        => 1,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'reason'           => 'Backed Out',
                        'background_color' => '#f0303',
                        'text_color'       => '#FFF',
                        'quote_status_id'  => $status_id,
                        'is_active'        => 1,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'reason'           => 'Bought Elsewhere',
                        'background_color' => '#f20606',
                        'text_color'       => '#FFF',
                        'quote_status_id'  => $status_id,
                        'is_active'        => 1,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'reason'           => 'Lack of Product Range',
                        'background_color' => '#f20606',
                        'text_color'       => '#FFF',
                        'quote_status_id'  => $status_id,
                        'is_active'        => 1,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'reason'           => 'Lead Time Issue',
                        'background_color' => '#f20606',
                        'text_color'       => '#FFF',
                        'quote_status_id'  => $status_id,
                        'is_active'        => 1,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'reason'           => 'No Show at Appointment',
                        'background_color' => '#f20606',
                        'text_color'       => '#FFF',
                        'quote_status_id'  => $status_id,
                        'is_active'        => 1,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    ),
                    array(
                        'reason'           => 'No Answering calls',
                        'background_color' => '#f20606',
                        'text_color'       => '#FFF',
                        'quote_status_id'  => $status_id,
                        'is_active'        => 1,
                        'created_by'       => $user_id,
                        'updated_by'       => $user_id
                    )
                )
            );
            //Product Document Category
            DB::table('product_document_categories')->insert(
                array(
                    array(
                        'category'   => 'Order',
                        'account_id' => $account_id,
                        'required'   => 1,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'category'   => 'Install',
                        'account_id' => $account_id,
                        'required'   => 1,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'category'   => 'Measure',
                        'account_id' => $account_id,
                        'required'   => 1,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'category'   => 'General',
                        'account_id' => $account_id,
                        'required'   => 1,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    )
                )
            );
            //
            //Windows
            DB::table('windows')->insert(
                array(
                    array(
                        'name'       => 'Dining',
                        'account_id' => $account_id,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'name'       => 'Bedroom',
                        'account_id' => $account_id,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'name'       => 'Kitchen',
                        'account_id' => $account_id,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'name'       => 'Living',
                        'account_id' => $account_id,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'name'       => 'Rooftop',
                        'account_id' => $account_id,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'name'       => 'Bathroom',
                        'account_id' => $account_id,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'name'       => 'Ensuite',
                        'account_id' => $account_id,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'name'       => 'Entry',
                        'account_id' => $account_id,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'name'       => 'Family',
                        'account_id' => $account_id,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ),
                    array(
                        'name'       => 'Games',
                        'account_id' => $account_id,
                        'is_active'  => 1,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    )
                )
            );
        }

        //TIERS Settings
        DB::table('tiers')->insert(
            array(
                array(
                    'name'       => 'Default',
                    'account_id' => $account_id,
                    'required'   => 1,
                    'is_active'  => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                )
            )
        );

        return true;

    }

    public function createFolderAddDealers($request,$folder_id) {
        if($request->ids) {
            $ids = explode(',', $request->ids);
            foreach ($ids as $id) {
                $account = Accounts::findOrFail($id);
                $account->folder_id = $folder_id;
                $account->save();
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function dealers() {

        $accounts = Accounts::select(['id','company_name as name'])->get();
        return  $accounts;
    }

    public function getDealers() {

        $accounts = Accounts::all();
        return  $accounts;
    }

    public function getCountry()
    {
        $session_id = Session::get('belongsToId');
        if(isset($session_id)) {
            $session_id = $session_id;
        } else {
            $selected_country_id = User::findOrFail(\Auth::user()->id);
            if($selected_country_id->is_favourite_country != 0) {
                $session_id = $selected_country_id->is_favourite_country;
            }
        }
        return $session_id;

    }

    public function getAllFolders($folder_id =1)
    {
        $allfolders = array();
        $folders = Folders::where('id',$folder_id)->orWhere('parent_id',$folder_id)->get();
        foreach ($folders as $folder){
            array_push($allfolders,$folder->id);
            if($folder->parent_id > 0){
                $subfolders = Folders::Where('parent_id',$folder->id)->get();
                if($subfolders->count() > 0){
                    foreach($subfolders as $sub){
                        array_push($allfolders,$sub->id);
                    }
                }
            }
        }
        $ids = implode(",",$allfolders);
        return $allfolders;

    }

    /**
     * Attach Master Employees to this Dealers Role ID
     * @param  int $master_id
     * @param  int $role_id
     */
    public function attachMasterEmployees($master_id, $role_id)
    {
        $employees = Employees::select('employees.id')
            ->leftJoin('employee_roles', 'employee_roles.employee_id', 'employees.id')
            ->where('employees.account_id',$master_id)
            ->where('employee_roles.role_id', '!=', $role_id)
            ->groupBy('employees.id')
            ->get();

        $data = [];
        foreach($employees as $employee)
        {
            $data[] = [
                'role_id' => $role_id,
                'employee_id' => $employee->id,
                'account_id' => $master_id
            ];
        }

        if(!empty($data))
        {
            EmployeesRoles::insert($data);
        }

    }

}
