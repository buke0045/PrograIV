<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth',['except'=>['index','show','login','avatar','store','upload']]);
    }
    public function index(){ //GET
        //Devolvera todos los elementos de categorias
        $data=User::all();
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }
    public function show($id){ //GET
        //Devolvera un elemento por su Id
        $user=User::find($id);
        if(is_object($user)){
            $response=array(
                'status'    =>'success',
                'code'      =>200,
                'data'   =>$user
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
        //{"name":"admin","last_name":"","email":"admin@gmail.com","username":"adminnnn", "password":"admin"}
        $json=$request->input('json',null);
        $data= json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=[
            'name'=>'required|alpha',
            'email'=>'required|email|unique:user',
            'last_name'=>'required',
            'password'=>'required',
            'username'=>'required'
        ];
        $valid= \validator($data,$rules);
        if($valid->fails()){
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Los datos enviados son incorrectos',
                'errors'=>$valid->errors()
            );
        }else{
            $user =new User();
            $user->name=$data['name'];
            $user->last_name=$data['last_name'];
            $user->email=$data['email'];
            $user->username=$data['username'];
            $user->password= hash('sha256', $data['password']);
            $user->save();
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Datos almacenados satisfactoriamente'
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
                'name'=>'required|alpha',
                'last_name'=>'required',
                'email'=>'required|email|unique:user',
                'password'=>'required',
                'username'=>'required'
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
                $email =$data['email'];
                //quitamos los campos del arreglo,que no quiero que se modifiquen en la base de datos.
                //esto en caso de que vengan dentro de los parametros.
                unset($data['id']);
                unset($data['email']);
                unset($data['password']);
                unset($data['create_at']);
                unset($data['remember_token']);

                //Buscamos y modificamos mediante ORM. Esto devuelve la cantidad de registros modificados. Cero si no se modificó
                $updated=User::where('email',$email)->update($data);
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
    public function getIdentity(Request $request){
        $jwtAuth= new JwtAuth();
        $token=$request->header('token');
        $response=$jwtAuth->checkToken($token,true);
        return response()->json($response);
    }
    public function login(Request $request){
        $jwtAuth= new JwtAuth();
        $json = $request->input('json',null);
        $data = json_decode($json,true);
        $data = array_map('trim', $data);
        $rules=[
            'email'=>'required|email',
            'password'=>'required'
        ];
        $validate= \validator($data,$rules);

        if($validate->fails()){
            $response=array(
                'status'=>'error',
                'code'=>'406',
                'message'=>'Los datos enviados son incorrectos',
                'errors'=>$validate->errors()
            );
        }
        else{
            $response = $jwtAuth->signin($data['email'],$data['password']);
        }
        return response()->json($response);
    }
}
