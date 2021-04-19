<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class MessageLoggable extends Model implements AuditableInterface
{

    use Auditable;
    //
    protected $table = 'message_loggable';

}
