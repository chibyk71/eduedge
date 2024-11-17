<?php

namespace App\Models;

use Laratrust\Models\Team as LaratrustTeam;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Team extends LaratrustTeam
{
    use BelongsToTenant;
    public $guarded = [];
}
