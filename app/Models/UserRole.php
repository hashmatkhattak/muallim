<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function user_permission()
    {
        return $this->hasMany(RolePermissions::class);
    }
}
