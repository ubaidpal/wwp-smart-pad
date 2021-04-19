<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class PriceGrid extends Model implements AuditableInterface
{
//    use SoftDeletes;
    use Auditable;

    //
    protected $table = 'price_grids';

}
