<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Help extends Model implements AuditableInterface
{

    use Auditable;

    protected $table = 'help';

    public function help()
    {
        return $this->belongsTo(HelpGroups::class, 'help_group_id', 'id');
    }

}
