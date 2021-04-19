<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class EmployeesRoles extends Model implements AuditableInterface
{

    use Auditable;
    use SoftDeletes;
    protected $table = 'employee_roles';

    public function Role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function employees()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }
}
