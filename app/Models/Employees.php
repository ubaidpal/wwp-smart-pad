<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Employees extends Model implements AuditableInterface
{

    use Auditable;
    use SoftDeletes;

    public function getEmailAttribute($value)
    {
        return ucfirst($value);
    }

    public function getUsernameAttribute($value)
    {
        return ucfirst($value);
    }

    public function account()
    {
        return $this->BelongsTo('App\Models\Accounts');
    }

    public function company()
    {
        return $this->BelongsTo('App\Models\Company');
    }

    public function companyInfo()
    {
        return $this->hasMany('App\Models\CompanyEmployees', 'employee_id', 'id');
    }

}
