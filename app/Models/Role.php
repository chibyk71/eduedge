<?php

namespace App\Models;

use Laratrust\Models\Role as RoleModel;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Role extends RoleModel
{
    use BelongsToTenant; 
    
    public $guarded = [];
}

