<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class AccountBrands extends Model implements AuditableInterface
{

    use Auditable;
    //
    protected $table = 'account_brands';
    protected $fillable = [
        'account_id',
        'brand_id',
        'brand_access',
        'dealer_admin',
        'main_admin'
    ];

    public function dealer()
    {
        return $this->belongsTo(Accounts::class, 'account_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
