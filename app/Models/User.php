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
        'last_name',
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

    protected $appends = ['is_admin'];

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

    public function getIsAdminAttribute()
    {
        return $this->hasRole('admin_room_911');
    }

    public function scopeCountAccess(Builder $query,$start,$end)
    {
 
        $query = $query ->withCount([
            'login_attempts as total_access' => function($query) use ($start, $end) {
                if ($start && $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                }
            }
        ]);

        if($start && $end)
        {
           $query = $query->having('total_access', '>=', 1);
        }
        return $query;
    }

    public function scopeAdmins911(Builder $query)
    {
        return $query->role('admin_room_911');
    }

    public function scopeByProductionDepartament(Builder $query,?int $production_departament_id)
    {   
        $query = $query->with('production_departament');
        if(!$production_departament_id) {
            return $query;
        }
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
