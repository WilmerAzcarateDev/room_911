<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'document',
        'email',
        'password',
        'production_departament_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public function username()
    {
        return 'document';
    }

    public function scopeAdmins911(Builder $query)
    {
        return $query->role('admin_room_911');
    }

    public function scopeByProductionDepartament(Builder $query,$production_departament_id)
    {
        return $query->where('production_departament_id',$production_departament_id);
    }

    public function production_departament()
    {
        return $this->belongsTo(ProductionDepartament::class);
    }

    public function login_attempts()
    {
        return $this->hasMany(LoginAttempt::class);
    }


}
