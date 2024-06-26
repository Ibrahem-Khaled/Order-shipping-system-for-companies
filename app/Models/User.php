<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function customs()
    {
        return $this->hasMany(CustomsDeclaration::class, 'client_id');
    }
    public function container()
    {
        return $this->hasMany(Container::class, 'client_id');
    }
    public function driverContainer()
    {
        return $this->hasMany(Container::class, 'driver_id');
    }
    public function userinfo()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }
    public function car()
    {
        return $this->hasOne(Cars::class, 'driver_id');
    }
    public function clientdaily()
    {
        return $this->hasMany(Daily::class, 'client_id');
    }
    public function employeedaily()
    {
        return $this->hasMany(Daily::class, 'employee_id');
    }
    public function partnerdaily()
    {
        return $this->hasMany(Daily::class, 'partner_id');
    }
    public function rentCont()
    {
        return $this->hasMany(Container::class, 'rent_id');
    }

    public function partnerInfo()
    {
        return $this->hasMany(PartnerInfo::class, 'partner_id');
    }

    public function tipsEmpty()
    {
        return $this->hasMany(Tips::class, 'user_id');
    }
}
