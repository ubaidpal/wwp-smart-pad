<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Employees;
use App\Models\EmployeesRoles;
use App\Models\Notification;
use App\Repository\admin\DealerRepository;
use Illuminate\Http\Request;


class SettingsController extends Controller
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


    public function index(Request $request)
    {
        $settings = Notification::get();
        return [
            'success' => TRUE,
            'settings' => $settings
        ];

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


}
