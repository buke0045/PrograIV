<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
class CustomerController extends Controller
{
    public function __construct(){
        //middleware
        $this->middleware('api.auth',['except'=>['index','show']]);
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
                'data'      =>$customer
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
        //Guardará un nuevo elemento
        //JSON
        //{"id":"504230111","address":"Limón","email":"lim@gmail.com","gender":"Hombre","last_name":"Salas","name":"Roberto","phone":"88888888"}
        $json=$request->input('json',null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'id'=>'required',
                'name'=>'required|alpha',
                'last_name'=>'required|alpha',
                'address'=>'required',
                'phone'=>'required',
                'email'=>'required|email'
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
                $customer= new Customer();
                $customer->id=$data['id'];
                $customer->name=$data['name'];
                $customer->last_name=$data['last_name'];
                $customer->address=$data['address'];
                $customer->phone=$data['phone'];
                $customer->email=$data['email'];
                $customer->save();
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
                'id'=>'required',
                'name'=>'required|alpha',
                'last_name'=>'required|alpha',
                'address'=>'required',
                'email'=>'required|email'
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
                unset($data['last_name']);
                unset($data['created_at']);
                $updated=Customer::where('id',$id)->update($data);
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
            $deleted=Customer::where('id',$id)->delete();
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
