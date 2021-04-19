<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class RolePermissions extends Model implements AuditableInterface
{

    use Auditable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_permission';


}
