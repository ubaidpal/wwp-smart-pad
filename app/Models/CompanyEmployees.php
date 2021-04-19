<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class CompanyEmployees extends Model implements AuditableInterface
{

    use Auditable;
    //

    protected $table = 'employee_companies';

    public function info()
    {
        return $this->hasOne('App\Models\Employees', 'id', 'employee_id');
    }

    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }
}
