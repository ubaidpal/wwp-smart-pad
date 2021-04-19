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
use App\Models\Accounts;
use App\Models\CompanyEmployees;
use App\Models\Country;
use App\Models\Employees;
use App\Models\EmployeesRoles;
use App\Models\Help;
use App\Models\HelpGroups;
use App\Models\Role;
use App\Observers\AccountsObserver;
use DemeterChain\A;
use Repository\admin\Eloquent\Repository;
use Carbon\Carbon;
class HelpRepository extends Repository
{

    function model() {
        return 'App\User';
    }

    public function index($request = Null) {

        $helpGroups = HelpGroups::all();
        $helpGroups = $helpGroups->load('help' );
        $finalArray = array();
        if(!empty($helpGroups)){

            foreach ($helpGroups as $key => $helpGroup){
                $data[ 'id' ]         = $helpGroup->id;
                $data[ 'name' ]       = $helpGroup->name;
                $data[ 'order' ]      = $helpGroup->order;
                $data[ 'created_at' ] = ($helpGroup->created_at) ? Carbon::parse($helpGroup->created_at)->format('d-m-Y') : 'Date Note Found..';

                array_push($finalArray, $data);
            }
        }

        return  $finalArray;
    }

    public function getHelp($request) {

        if(isset($request->help_group_id) && $request->help_group_id != 'undefined'){

            $help = Help::where('help_group_id' , $request->help_group_id)->get();
        }else{

            $help_group_id = \Session::get('help_group_id');
            $help = Help::where('help_group_id' , $help_group_id)->get();
        }

        $finalArray = array();
        if(!empty($help)){

            foreach ($help as $key => $helpGroup){
                $data[ 'id' ]         = $helpGroup->id;
                $data[ 'name' ]       = $helpGroup->name;
                $data[ 'order' ]      = $helpGroup->order;
                $data[ 'content' ]    = $helpGroup->content;
                $data[ 'created_at' ] = ($helpGroup->created_at) ? Carbon::parse($helpGroup->created_at)->format('d-m-Y') : 'Date Note Found..';

                array_push($finalArray, $data);
            }
        }

        return  $finalArray;
    }


}
