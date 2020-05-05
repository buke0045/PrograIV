<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
    //
    public function prueba(){
        $categorias = Category::all();
        return response()->json($categorias,200);
    }
}
