<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomsDeclaration extends Model
{
    use HasFactory;

    protected $guarded = ['id'];



    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function container()
    {
        return $this->hasMany(Container::class, 'customs_id');
    }
}
