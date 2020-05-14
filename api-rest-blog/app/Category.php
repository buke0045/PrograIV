<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $table='categories';
    public function sale_products(){
        return $this->hasMany('App\Sale_Product');
	}
}
