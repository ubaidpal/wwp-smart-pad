<?php

namespace App\Models;
use App\Models\Accounts;
use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class AccountSMSCredits extends Model implements  AuditableInterface{

    use Auditable;
    protected $table = 'account_sms_credits';

    public function accounts()
    {
        return $this->belongsTo(Accounts::class, 'account_id', 'id');
    }
}
