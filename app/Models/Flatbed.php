<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flatbed extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function containers()
    {
        return $this->belongsToMany(Container::class, 'flatbed_containers', 'container_id', 'flatbed_id');
    }
}
