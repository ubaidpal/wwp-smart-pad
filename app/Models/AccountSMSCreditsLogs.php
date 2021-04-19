<?php

namespace App\Models;
use App\Models\Accounts;
use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class AccountSMSCreditsLogs extends Model implements  AuditableInterface{

    use Auditable;
    protected $table = 'account_sms_credits_logs';

    public function accounts()
    {
        return $this->belongsTo(Accounts::class, 'account_id', 'id');
    }
}
