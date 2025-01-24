<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    // this relation for user and ...
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



    // this orm for user and ...
    public function monthlyContainers($month = null, $year = null, $status = ['transport', 'done'])
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        return $this->container()
            ->whereIn('status', $status)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);
    }

    // علاقة لجلب الإيرادات المتبقية
    public function remainingRevenue()
    {
        return $this->container->whereIn('status', ['transport', 'done'])->sum('price')
            + $this->container->sum(function ($container) {
                return $container->daily->where('type', 'withdraw')->sum('price');
            })
            - $this->clientdaily->where('type', 'deposit')->sum('price');
    }

}
