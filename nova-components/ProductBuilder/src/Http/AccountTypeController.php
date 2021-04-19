<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use Illuminate\Http\Request;


class AccountTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('active')) {
            $result = AccountType::where('active', '1')->get();
        } else {
            $result = AccountType::get();
        }
        return response()->json([
            'types' => $result,
            'success' => true
        ]);
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();
        $type = AccountType::findOrFail($id);
        $type->update($data);
        return response()->json([
            'success' => true,
            'type' => $type
        ]);
    }

    public function destroy($id)
    {
        $type = AccountType::findOrFail($id);
        $type->delete();
        return response()->json(['success' => true]);
    }
}
