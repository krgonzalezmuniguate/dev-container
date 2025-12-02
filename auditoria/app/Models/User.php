<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; 
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'manager_id'
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
    protected $appends = ['fullname', 'smallname', 'initial', 'image'];
    protected $guard_name = 'api';
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
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getFullNameAttribute()
    {
        return trim($this->name) . ' ' . trim($this->last_name);
    }

    public function getSmallNameAttribute()
    {
        $names = explode(' ', str_replace([' de ', ' la ', ' los ', ' del ', ' Del '], '', strtolower($this->name)));
        $lastnames = explode(' ', str_replace([' de ', ' la ', ' los ', ' del ', ' Del '], '', strtolower($this->last_name)));
        return ucwords($names[0] . ' ' . $lastnames[0]);
    }

    public function getInitialAttribute()
    {
        $names = explode(' ', $this->smallname);
        $logo = '';
        $logo = $names[0][0];
        return $logo;
    }
    public function getImageAttribute()
    {
        return $this->personal_details?->profile_picture_url;
    }
    public function personal_details()
    {
        return $this->hasOne(PersonalDetails::class);
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
