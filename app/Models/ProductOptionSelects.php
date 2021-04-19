<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class ProductOptionSelects extends Model implements AuditableInterface
{
//    use SoftDeletes;
    use Auditable;
    //

    protected $table = 'product_option_selects';

    protected $fillable = [
        'id',
        'name',
        'price_structure',
        'dealer_admin',
        'main_admin'
    ];
}
