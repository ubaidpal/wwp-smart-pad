<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Item extends Model implements AuditableInterface
{

    use Auditable;

    //

    public function brands()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function fabricCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
