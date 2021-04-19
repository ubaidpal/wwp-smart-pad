<?php

namespace App;

use App\Models\Role;
use App\Models\RolePermissions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Roles()
    {
        return $this->belongsTo(RolePermissions::class, 'role_id', 'id');
    }

    public function countries()
    {
        return $this->belongsTo(\App\Models\Country::class, 'country_id', 'id');
    }
}
