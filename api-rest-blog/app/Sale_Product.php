<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale_Product extends Model
{
    protected $table='sale_product';
    public function sale(){
        return $this->belongsTo('App\Sale','idSale');
    }
    public function product(){
        return $this->belongsTo('App\Category','idProduct');
    }
}
