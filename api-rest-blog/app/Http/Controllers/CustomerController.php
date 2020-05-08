<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
class CustomerController extends Controller
{
    public function __construct(){
        //middleware
    }
    public function index(){ //GET
        //Devolvera todos los elementos de categorias
        $data=Customer::all();
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }
    public function show($id){ //GET
        //Devolvera un elemento por su Id
        $customer=Customer::find($id);
        if(is_object($customer)){
            $response=array(
                'status'    =>'success',
                'code'      =>200,
                'data'   =>$customer
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
    public function destroy($id){ //DELETE

    }
}
