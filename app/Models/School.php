<?php

namespace App\Models;

use App\Traits\HasAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use RuangDeveloper\LaravelSettings\Traits\HasSettings;
use Spatie\Onboard\Concerns\GetsOnboarded;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant;

class School extends Tenant implements TenantWithDatabase
{
    /** @use HasFactory<\Database\Factories\SchoolFactory> */
    use HasFactory, HasDatabase, HasDomains, GetsOnboarded, HasSettings, HasAddress;

}
