<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Brand extends Model implements AuditableInterface
{

    use Auditable;

    //
    public function collections()
    {
        return $this->hasMany('App\Models\Collection')->orderBy('name');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function getPriceTypeAttribute($value)
    {
        return $value;
    }

    public function accounts()
    {
        return $this->hasMany(AccountBrands::class, 'brand_id', 'id');
    }
}
