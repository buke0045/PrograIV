<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Supplier;
use App\Category;
class ProductController extends Controller
{
    public function __construct(){
    }
    public function index(){ //GET
        $data=Product::all()->load('supplier','category');
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }
    public function show($id){ //GET
        //Devolvera un elemento por su Id
        $data = Product::find($id)->load('supplier','category');
        if(is_object($data)){
            $response=array(
                'status'=>'success',
                'code'=> 200,
                'data'=>$data
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Recurso no encontrado'
            );
        }
        return response()->json($response,$response['code']);
    }
    public function store(Request $request){ //POST
        //Guardará un nuevo elemento
        //JSON
        //{"id":"22","name":"M","description":"m","currentExist":"5","minExist":"2","price":"5000","image":"null","created_at":"null","updated_at":"null","idSupplier":1,"idCategory":"1"}        $json=$request->input('json',null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'name'=>'required|alpha',
                'idSupplier' => 'required',
                'idCategory' => 'required'
            ];
            $validate=\validator($data,$rules);
            if($validate->fails()){
                $response=array(
                    'status'=>'error',
                    'code'=>406,
                    'message'=>'los datos enviados son incorrectos',
                    'errors'=>$validate->errors()
                );
            }else{
                $product= new Product();
                $product->id=$data['id'];
                $product->name=$data['name'];
                $product->description=$data['description'];
                $product->currentExist=$data['currentExist'];
                $product->minExist=$data['minExist'];
                $product->price=$data['price'];
                $product->image=$data['image'];
                $product->idSupplier=$data['idSupplier'];
                $product->idCategory=$data['idCategory'];
                $product->save();
                $response=array(
                    'status'=>'success',
                    'code'=>201,
                    'message'=>'Datos almacenados satisfactoriamente'
                );
            }
        }
        else{
            $response=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'faltan parametros'
            );
        }
        return response()->json($response,$response['code']);
    }
    public function update(Request $request){ //PUT
        //Actualiza un elemento
        $json= $request->input('json',null);
        $data=json_decode($json,true);
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'name'=>'required'
            ];
            $validate=\validator($data,$rules);
            if($validate->fails()){
                $response=array(
                    'status'=>'error',
                    'code'=>406,
                    'message'=>'Los datos son incorrectos',
                    'errors'=>$validate->errors()
                );
            }
            else{
                $id=$data['id'];
                unset($data['id']);
                unset($data['name']);
                unset($data['created_at']);
                $updated=Product::where('id',$id)->update($data);
                if($updated>0){
                    $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Datos actualizados satisfactoriamente'
                    );
                }else{
                    $response=array(
                        'status'=>'error',
                        'code'=>400,
                        'message'=>'Problemas en la actualización'
                        );
                }
            }
        }else{
            $response=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'faltan parametros'
            );
        }
        return response()->json($response,$response['code']);

    }
    public function destroy($id){ //DELETE
        //Elimina un elemento
        if(isset($id)){
            $deleted=Product::where('id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Eliminado correctamente'
                    );
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo eliminar'
                    );
            }
        }
        else{
            $response=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Falta el identificador del recurso'
                );
        }
        return response()->json($response,$response['code']);
    }

}
