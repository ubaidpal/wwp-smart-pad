<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $table = 'notification_logs';

    public function getAccountIdAttribute($value)
    {
        $account = Accounts::where('id', $value)->first();
        if ($account->username) {
            $name = $account->username;
        } else {
            $name = $account->email;
        }
        return $name;
    }

    public function getMarkReadAttribute($value)
    {
        if ($value) {
            return 'Yes';
        } else {
            return 'No';
        }
    }
}
