<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
    public function prueba(){
        $customer = Customer::all();
        return response()->json($customer,200);
    }

    //prueba
}
