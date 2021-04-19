<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function getUsers(Request $request)
    {
        try {
            $users = User::with('Roles')->get();
            return [
                'success' => true,
                'users' => $users
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function destroyUser(Request $request)
    {
        try {

            $User = User::where('id', '=', $request->resourceId)->delete();
            return [
                'success' => true,
                'users' => $User
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getUserAccess(Request $request)
    {
        try {
            $access = User::with('Roles')->where('id', Auth::user()->id)->first();
            if ($access->roles->permissions == '*') {
                $permissions = $access->roles->permissions;
            } else {
                $permissions = explode(',', $access->roles->permissions);
            }
            return [
                'success' => true,
                'access' => $permissions
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }


}
