<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale_Product;
use App\Sale;
use App\Product;

class Sale_ProductController extends Controller
{
    public function __construct(){
        //$this->middleware('api.auth',['except'=>['index','show']]);
    }

    public function index(){
        $data=Sale_Product::all()->load('sale','product');
        $response=array(
            'status'    =>'success',
            'code'      =>200,
            'data'   =>$data
        );
        return response()->json($response,$response['code']);
    }
    public function show($id){
        $data=Sale_Product::find($id)->load('sale','product');
        if(is_object($data)){
            $response=array(
                'status'    =>'success',
                'code'      =>200,
                'data'   =>$data
            );
        }else{
            $response=array(
                'status'    =>'error',
                'code'      =>404,
                'message'   =>'Recurso no encontrado'
            );
        }
        return response()->json($response,$response['code']);
    }
    public function store(Request $request){
        $json = $request->input('json',null);
        $data= json_decode($json,true);// el true es para pasar ese json a array
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'quantity'=>'required',
                'idSale'=>'required',
                'idProduct'=>'required'
            ];
            //validamos
            $validate = \validator($data, $rules);
            if($validate->fails()){
                $response=array(
                    'status'    =>'error',
                    'code'      =>406,
                    'message'   =>'Los datos enviados son incorrectos',
                    'errors'    => $validate->errors()
                );
            }else{
                $jwtAuth=new JwtAuth();
                $token=$request->header('token',null);
                $user=$jwtAuth->checkToken($token,true);

                $sale_product=new Sale_Product();
//    $sale_product->user_id=$user->sub;
                //$sale_product->id=$data['id'];
                $sale_product->quantity=$data['quantity'];
                $sale_product->totalPrice=$data['totalPrice'];
                $sale_product->idSale=$data['idSale'];
                $sale_product->idProduct=$data['idProduct'];
                $sale_product->save();

                $response=array(
                    'status'    =>'success',
                    'code'      =>200,
                    'message'   =>'Datos almacenados satisfactoriamente'
                );
            }
        }else{
            $response=array(
                'status'    =>'error',
                'code'      =>400,
                'message'   =>'Faltan parametros'
            );
        }
        return response()->json($response,$response['code']);
    }
    public function update(Request $request){
        $json = $request->input('json',null);
        $data= json_decode($json,true);// el true es para pasar ese json a array
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'id'=>'required',
                'title'=>'required',
                'content'=>'required',
                'image'=>'required',
                'idSale'=>'required'
            ];
            //validamos
            $validate = \validator($data, $rules);
            if($validate->fails()){
                $response=array(
                    'status'    =>'error',
                    'code'      =>406,
                    'message'   =>'Los datos enviados son incorrectos',
                    'errors'    => $validate->errors()
                );
            }
            else{
                $id=$data['id'];
                unset($data['id']);
                unset($data['user_id']);
                unset($data['create_at']);
                $updated=sale::where('id',$id)->update($data);
                if($updated>0){
                    $response=array(
                        'status'    =>'success',
                        'code'      =>200,
                        'message'   =>'Actualizado correctamente'
                    );
                }else{
                    $response=array(
                        'status'    =>'error',
                        'code'      =>400,
                        'message'   =>'No se pudo actualizar'
                    );
                }
            }
        }else{
            $response=array(
                'status'    =>'error',
                'code'      =>400,
                'message'   =>'Faltan parametros'
            );
        }

        return response()->json($response,$response['code']);
    }
    public function destroy($id){
        if(isset($id)){
            $deleted=Sale_Product::where('id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'    =>'success',
                    'code'      =>200,
                    'message'   =>'Eliminado correctamente'
                );
            }else{
                $response=array(
                    'status'    =>'error',
                    'code'      =>400,
                    'message'   =>'No se pudo eliminar, puede que el registro ya no exista'
                );
            }
        }else{
            $response=array(
                'status'    =>'error',
                'code'      =>400,
                'message'   =>'Faltan parametros'
            );
        }
        return response()->json($response,$response['code']);
    }
}
