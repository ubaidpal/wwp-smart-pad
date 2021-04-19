<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Help;
use App\Models\HelpGroups;
use App\Repository\admin\HelpRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class HelpController extends Controller
{

    protected $HelpRepository;
    protected $user_id;
    protected $Accounts;

    public function __construct(
        Accounts $Accounts,
        HelpRepository $HelpRepository
    )
    {
        $this->Accounts = $Accounts;
        $this->HelpRepository = $HelpRepository;
        $this->user_id = \Auth::id();
    }


    public function index(Request $request)
    {

        $groups = $this->HelpRepository->index($request);

        return [
            'success' => TRUE,
            'groups' => $groups
        ];

    }


    //Delete GroupHelp
    public function destroyGroup(Request $request)
    {

        try {
            $help = Help::where('help_group_id', $request->resourceId)->get();
            if (!empty($help)) {
                foreach ($help as $helps) {
                    if (isset($helps->id)) {
                        Help::where('id', $helps->id)->delete();
                    }
                }
                $helpGroup = HelpGroups::where('id', $request->resourceId)->delete();
            } else {
                $helpGroup = HelpGroups::where('id', $request->resourceId)->delete();
            }

            return [
                'success' => true,
                'helpGroup' => $helpGroup
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getHelp(Request $request)
    {

        if (isset($request->help_group_id)) {

            $session_id = \Session::get('help_group_id');
            if (!isset($session_id) && $request->help_group_id != 'undefined') {
                Session::put('help_group_id', $request->help_group_id);
            } else if ($request->help_group_id != 'undefined') {
                Session::put('help_group_id', $request->help_group_id);
            }
        }

        if (isset($request->help_group_id) && $request->help_group_id != 'undefined') {
            $helpGroups = HelpGroups::find($request->help_group_id);
            $group_name = $helpGroups->name;
        } else {
            $help_group_id = \Session::get('help_group_id');

            $helpGroups = HelpGroups::find($help_group_id);
            $group_name = $helpGroups->name;
        }

        $groups = $this->HelpRepository->getHelp($request);

        return [
            'success' => TRUE,
            'groups' => $groups,
            'group_name' => $group_name
        ];


    }

    public function destroyHelp(Request $request)

    {
        try {
            $helpGroup = Help::where('id', $request->resourceId)->delete();
            return [
                'success' => true,
                'helpGroup' => $helpGroup
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getGroupsList(Request $request)
    {
        {
            try {
                $helpGroup = HelpGroups::where('id', '>', 0)->get();
                if (!empty($helpGroup)) {
                    return [
                        'success' => true,
                        'help_groups' => $helpGroup
                    ];
                }


            } catch (\Exception $e) {
                return [
                    'error' => $e->getMessage()
                ];
            }

        }
    }


}
