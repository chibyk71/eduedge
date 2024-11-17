<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

class User extends Authenticatable implements LaratrustUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRolesAndPermissions, HasAddress;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'enrollment_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the school sections the user belongs to.
     */
    public function schoolSections()
    {
        return $this->belongsToMany(Team::class, 'user_school_section')
                    ->withTimestamps();
    }

    /**
     * Get the schools the user belongs to via school sections.
     */
    public function schools()
    {
        return $this->schoolSections()->with('school');
    }

    /**
     * Check if the user belongs to a specific school
     *
     * @param int|null $schoolId The ID of the school to check. If null, use the current tenant ID.
     * @return bool
     */
    public function belongsToSchool($schoolId = null): bool
    {
        // If no schoolId is provided, use the current tenant ID
        $schoolId = $schoolId ?? tenant('id');

        // Check if the user belongs to the school
        return $this->schools()->where('schools.id', $schoolId)->exists();
    }
}
