<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class OrderWindowProductOptions extends Model implements AuditableInterface
{

    use Auditable;
    //
    protected $table = 'order_window_product_options';

}
