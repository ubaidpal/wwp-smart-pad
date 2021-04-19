<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class OptionConstraintActions extends Model implements AuditableInterface
{

    use SoftDeletes;
    use Auditable;
    //

    protected $table = 'product_option_constraint_actions';

}
