<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Category extends Model implements AuditableInterface
{

    use Auditable;

    public static function getCategoriesOptionList()
    {
        return self::where('is_active', 1)->where('is_fabric', 0)->pluck('name', 'id');
    }

    public static function getFabricCategoriesOptionList()
    {
        return self::where('is_active', 1)->where('is_fabric', 1)->select('name', 'id')->get();
    }


}
