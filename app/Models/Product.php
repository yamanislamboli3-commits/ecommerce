<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=['title','description','price','quantity'];
    public function customers()
    {
        return $this->belongsToMany(User::class, 'customers_products')->withTimestamps();
    }
    
}
