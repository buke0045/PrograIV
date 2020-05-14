<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
    protected $table='sale_product';
    public function user(){
        return $this->belongsTo('App\Sale','idSale');
    }
    public function category(){
        return $this->belongsTo('App\Category','idProduct');
    }

}
