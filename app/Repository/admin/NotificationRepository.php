<?php

/**
 * Created by   :  Ubaid
 * Project Name : Smart Pad Pro
 * Product Name : PhpStorm
 * Date         : 3-26-2019 2:02 PM
 * File Name    : NotificationRepository.php
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
use App\Models\Notification;
use App\Models\Role;
use App\Observers\AccountsObserver;
use DemeterChain\A;
use phpDocumentor\Reflection\Types\Null_;
use Repository\admin\Eloquent\Repository;
use Carbon\Carbon;
use Zend\Diactoros\Request;

class NotificationRepository extends Repository
{

    function model() {
        return 'App\User';
    }

    public function index($request = Null) {

        $notifications = Notification::all();

        $finalArray = array();
        if(!empty($notifications)){
            foreach ($notifications as $key => $notification){
                $data[ 'id' ]               = $notification->id;
                $data[ 'name' ]             = $notification->name;
                $data[ 'content' ]          = $notification->content;
                $data[ 'text_color' ]       = $notification->text_color;
                $data[ 'background_color' ] = $notification->background_color;
                $data[ 'created_at' ] = ($notification->created_at) ? Carbon::parse($notification->created_at)->format('d-m-Y') : 'Date Note Found..';
                array_push($finalArray, $data);
            }
        }

        return  $finalArray;
    }

    public function saveNotification($request)
    {
        if(isset($request->id)){
            $new = Notification::find($request->id);
        } else {
            $new = new Notification();
        }

        $new->name             = $request->name;
        $new->content          = $request->content;
        $new->text_color       = $request->text_color;
        $new->background_color = $request->background_color;
        $new->active = $request->active;
        $new->notify_type = $request->notify_type;
        if($request->notify_type == 'specific_dealers'){
            $new->dealers = $request->dealer_id;
            $new->country_id = 0;
        }
        if($request->notify_type == 'specific_country'){
            $new->dealers = Null;
            $new->country_id = $request->country_id;
        }
        $new->save();
        return $new;

    }

    public function getNotifyData($notify_id)
    {
        $notify = Notification::find($notify_id);
        return $notify;


    }


}
