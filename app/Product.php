<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table='product';
	public function sale_product(){
		return $this->hasMany('App\Sale_Product');
	}
    public function supplier(){
        return $this->belongsTo('App\Supplier','idSupplier');
    }
    public function category(){
        return $this->belongsTo('App\Category','idCategory');
    }
}
