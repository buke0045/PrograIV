<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
class SupplierController extends Controller
{
    public function __construct(){
        //middleware
    }
    public function index(){ //GET
        //Devolvera todos los elementos de categorias
        $data=Supplier::all();
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }
    public function show($id){ //GET
        //Devolvera un elemento por su Id
        $supplier=Supplier::find($id);
        if(is_object($supplier)){
            $response=array(
                'status'    =>'success',
                'code'      =>200,
                'data'   =>$supplier
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
        //{"address":"San José","cellphone":"88888888","deliveryDays":"3","email":"razer@gmail.com","name":"Razer","phone":"26738888","supplierName":"Roberto"}
        $json=$request->input('json',null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'name'=>'required|alpha'
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
                $supplier= new Supplier();
                $supplier->address=$data['address'];
                $supplier->name=$data['name'];
                $supplier->email=$data['email'];
                $supplier->phone=$data['phone'];
                $supplier->save();
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
        $json = $request->input('json',null);
        $data= json_decode($json,true);// el true es para pasar ese json a array
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'name'=>'required'
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

                //capturamos el email en una variable
                $name =$data['name'];
                //quitamos los campos del arreglo,que no quiero que se modifiquen en la base de datos.
                //esto en caso de que vengan dentro de los parametros.
                unset($data['id']);
                unset($data['create_at']);

                //Buscamos y modificamos mediante ORM. Esto devuelve la cantidad de registros modificados. Cero si no se modificó
                $updated=Supplier::where('name',$name)->update($data);
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
                        'message'   =>'No se pudo actualizar, puede que el usuario no exita'
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
    public function destroy($id){ //DELETE
        //Elimina un elemento
        if(isset($id)){
            $deleted=User::where('id',$id)->delete();
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
