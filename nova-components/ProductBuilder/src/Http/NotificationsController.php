<?php

namespace Spp\ProductBuilder\Http;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationLog;
use App\Repository\admin\NotificationRepository;
use Illuminate\Http\Request;


class NotificationsController extends Controller
{

    protected $NotificationRepository;
    protected $user_id;
    protected $Accounts;

    public function __construct(
        NotificationRepository $NotificationRepository
    )
    {

        $this->NotificationRepository = $NotificationRepository;
        $this->user_id = \Auth::id();
    }


    public function index(Request $request)
    {

        $notification = $this->NotificationRepository->index($request);

        return [
            'success' => TRUE,
            'notification' => $notification
        ];

    }


    //Delete Notification
    public function destroyNotification(Request $request)
    {

        try {
            $notification = Notification::findOrFail($request->resourceId);
            if (isset($notification->id)) {
                $notification = Notification::where('id', $request->resourceId)->delete();
                return [
                    'success' => true,
                    'notification' => $notification
                ];
            }

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function saveNotification(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'content' => 'required',
        ], [
            'name.required' => 'Name is required',
            'content.required' => 'Content is required',
        ]);

        try {

            $notification = $this->NotificationRepository->saveNotification($request);
            return [
                'success' => TRUE,
                'notification' => $notification
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getNotifyData($id)
    {
        try {
            $notification = $this->NotificationRepository->getNotifyData($id);
            return [
                'success' => TRUE,
                'notification' => $notification
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }


    public function getLogs(Request $request)
    {
        $logs = NotificationLog::all();
        $to_date = $request->get('to');
        $from_date = $request->get('from');
        $result = NotificationLog::orderBy('id', 'DESC');
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
    }
}
