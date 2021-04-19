<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Currency extends Model implements AuditableInterface
{

    use Auditable;

    //

    public function countries()
    {
        return $this->hasMany(Country::class, 'country_id');
    }

    public function getTitleAttribute()
    {
        return "{$this->code} ({$this->symbol})";
    }
}
