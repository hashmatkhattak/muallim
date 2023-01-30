<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function role_permissions()
    {
        return $this->belongsToMany(RolePermissions::class)
            ->select("route", "permission");
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
