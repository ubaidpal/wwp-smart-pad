<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Notification extends Model implements AuditableInterface
{

    use Auditable;

    protected $attributes = [
        'text_color' => '#ffffff',
        'background_color' => '#ffffff'
    ];

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function dealers()
    {
        return $this->belongsTo(Accounts::class, 'dealer_id', 'id');
    }
}
