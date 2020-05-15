<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table='sale';
	public function sale_product(){
		return $this->hasMany('App\Sale_Product');
	}
    public function customer(){
        return $this->belongsTo('App\Customer','idCustomer');
    }
}
