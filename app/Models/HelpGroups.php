<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class HelpGroups extends Model implements AuditableInterface
{

    use Auditable;
    protected $table = 'help_groups';

    public function help()
    {
        return $this->hasMany(Help::class, 'help_group_id', 'id');
    }
}
