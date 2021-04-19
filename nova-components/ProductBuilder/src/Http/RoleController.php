<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\RolePermissions;
use Illuminate\Http\Request;


class RoleController extends Controller
{

    public function getRoles(Request $request)
    {
        try {
            $roles = RolePermissions::all();
            $role_data = array();
            foreach ($roles as $role) {
                $data['id'] = $role->id;
                $data['name'] = $role->name;
                $data['permissions'] = explode(',', $role->permissions);
                array_push($role_data, $data);
            }
            return [
                'success' => true,
                'roles' => $role_data
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function destroyRole(Request $request)
    {
        try {

            $role = RolePermissions::where('id', '=', $request->resourceId)->delete();
            return [
                'success' => true,
                'role' => $role
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }


    public function getLogs(Request $request)
    {
        try {
            $to_date = $request->get('to');
            $from_date = $request->get('from');
            $result = Activities::with('user')->orderBy('id', 'DESC');
            if ($to_date) {
                $result = $result->whereDate('created_at', '<=', $to_date);
            }
            if ($from_date) {
                $result = $result->whereDate('created_at', '>=', $from_date);
            }
            $logs = $result->get();
            return [
                'success' => true,
                'logs' => $logs
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }


}
