<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'ingredient_id', 'vendor_id', 'quantity', 'price', 'type', 'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];
     public function setUnitAttribute($value)
    {
        $this->attributes['unit'] = 'kg';
    }
}
