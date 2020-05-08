<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
class ProductController extends Controller
{
    public function __construct(){
        //mcodedleware
    }
    public function index(){ //GET
        //Devolvera todos los elementos de categorias
        $data=Product::all();
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }
    public function show($id){ //GET
        //Devolvera un elemento por su id
        $product=Product::find($id);
        if(is_object($product)){
            $response=array(
                'status'    =>'success',
                'code'      =>200,
                'data'   =>$product
            );
        }else{
            $response=array(
                'status'    =>'error',
                'code'      =>404,
                'message'   =>'Usuario no encontrado'
            );
        }
        return response()->json($response,$response['code']);
    }
    public function store(Request $request){ //POST

    }
    public function update(Request $request){ //PUT


    }
    public function destroy($code){ //DELETE

    }
}
