<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Role extends Model implements AuditableInterface
{

    use Auditable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';


}
