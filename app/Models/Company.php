<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Company extends Model implements AuditableInterface
{

    use Auditable;

    //
    public function account()
    {
        return $this->BelongsTo('App\Models\Accounts');
    }

    public function employees()
    {
        return $this->hasMany('App\Models\CompanyEmployees', 'company_id', 'id');
    }
}
