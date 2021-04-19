<?php

namespace App\Models;

use App\User;
use App\Models\MessageLoggable;
use App\Models\AccountSMSCredits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Accounts extends Model implements AuditableInterface
{

    use Auditable;
    use SoftDeletes;

    public function getEmailAttribute($value)
    {
        return ucfirst($value);
    }

    public function users()
    {
        return $this->belongsTo(User::class, '	created_by', 'id');
    }

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function employees()
    {
        return $this->hasMany(Employees::class, 'account_id', 'id');
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'account_id', 'id');
    }

    public function folder()
    {
        return $this->belongsTo(Folders::class, 'folder_id', 'id');
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class, 'account_type', 'id');
    }

    public function messageLoggable()
    {
        return $this->hasMany(MessageLoggable::class, 'account_id', 'id');
    }

    public function smsCredits(){
        return $this->hasMany(AccountSMSCredits::class, 'account_id', 'id');
    }

}
