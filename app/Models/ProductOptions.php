<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class ProductOptions extends Model implements AuditableInterface
{
//    use SoftDeletes;
    use Auditable;
    //
    protected $table = 'product_options';
    protected $fillable = [
        'account_id',
        'brand_id',
        'brand_access',
        'dealer_admin',
        'main_admin'
    ];

    public function getHideOnAttribute($value)
    {
        return json_decode($value);
    }

}
