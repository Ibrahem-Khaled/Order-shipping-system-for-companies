<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function container()
    {
        return $this->hasMany(Container::class, 'car_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function daily()
    {
        return $this->hasMany(Daily::class, 'car_id');
    }
}
