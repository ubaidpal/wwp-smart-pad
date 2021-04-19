<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Collection extends Model implements AuditableInterface
{

    use Auditable;

    public function brand()
    {
        return $this->BelongsTo('App\Models\Brand');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'collection_id', 'id');
    }
}
