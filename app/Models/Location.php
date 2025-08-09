<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'customs_declaration_id',
        'title',
        'subtitle',
        'maps_url',
    ];

    public function customsDeclaration()
    {
        return $this->belongsTo(CustomsDeclaration::class);
    }
}
