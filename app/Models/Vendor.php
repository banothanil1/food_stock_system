<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ingredient;
class Vendor extends Model
{
    use HasFactory;
    protected $fillable=['name','contact_details'];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class,'vendor_id','id');
    }
}
