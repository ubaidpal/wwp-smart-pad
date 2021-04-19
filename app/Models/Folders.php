<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Folders extends Model implements AuditableInterface
{

    use Auditable;

    protected $table = 'folders';

    public function subFolder()
    {
        return $this->hasMany(Folders::class, 'parent_id', 'id');
    }

}
