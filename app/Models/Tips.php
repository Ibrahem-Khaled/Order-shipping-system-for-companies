<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tips extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'container_id', 'user_id', 'car_id'];

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Cars::class);
    }
}
