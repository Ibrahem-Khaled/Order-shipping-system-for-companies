<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellAndBuy extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'price', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(SellAndBuy::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(SellAndBuy::class, 'parent_id');
    }
}
