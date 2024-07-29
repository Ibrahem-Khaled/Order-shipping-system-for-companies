<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function car()
    {
        return $this->belongsTo(Cars::class, 'car_id');
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function emplyee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
    public function container()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }
}
