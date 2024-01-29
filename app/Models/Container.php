<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function customs()
    {
        return $this->belongsTo(CustomsDeclaration::class, 'customs_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function car()
    {
        return $this->belongsTo(Cars::class, 'car_id');
    }

    public function rent()
    {
        return $this->belongsTo(User::class, 'rent_id');
    }
}
