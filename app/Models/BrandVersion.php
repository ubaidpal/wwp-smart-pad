<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class BrandVersion extends Model implements AuditableInterface
{

    use Auditable;
    //use SoftDeletes;
    protected $table = 'brand_version';
}
