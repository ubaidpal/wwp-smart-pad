<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class ProductOptionConstraints extends Model implements AuditableInterface
{

//    use SoftDeletes;
    use Auditable;
    //

    protected $table = 'product_option_constraints';

}
