<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Activities extends Model implements AuditableInterface
{

    use Auditable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activities';

    public function getAuditableTypeAttribute($value)
    {
        return substr($value, strripos($value, '\\') + 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
