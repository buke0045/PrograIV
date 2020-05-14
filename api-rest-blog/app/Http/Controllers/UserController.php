<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{

    public function __construct(){
        //middleware
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
        //JSON
        //{"email":"asd@asd.com","gender":"hombre","name":"bukesaso","password":"pass","surname":"alvar","username":"bukss"}
        $json=$request->input('json',null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'username'=>'required|unique:user',
                'password'=>'required'
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
                $user= new User();
                $user->email=$data['email'];
                $user->gender=$data['gender'];
                $user->name=$data['name'];
                $user->password=$data['password'];
                $user->surname=$data['surname'];
                $user->username=$data['username'];
                $user->save();
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


    }
    public function destroy($id){ //DELETE

    }



/*
    //      -----------------------------------------CODIGO DEL PROFE----------------------------------------------------------    
    public function __construct(){
        //middleware
        $this->middleware('api.auth',['except'=>['index','show','login','avatar']]);
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
        $json=$request->input('json',null);
        $data= json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=[
            'name'=>'required|alpha',
            'email'=>'required|email|unique:users',
            'password'=>'required',
            'role'=>'required'
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
            $user->role=$data['role'];
            $user->email=$data['email'];
            $user->password= hash('sha256', $data['password']);
            $user->save();
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Datos almacenads satisfactoriamente'
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
                'email'=>'required|email',
                'role'=>'required'
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

    public function upload(Request $request){
        $image=$request->file('file0');
        $validate=\Validator::make($request->all(),[
            'file0'=>'required|image|mimes:jpg,png'
        ]);
        if(!$image || $validate->fails()){
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Error al subir la imagen',
                'errors'=> $validate->errors()
            );
        }
        else{
            $image_name=time().$image->getClientOriginalName();
            \Storage::disk('users')->put($image_name,\File::get($image));
            $response=array(
                'status'=>'success',
                'code' => 200,
                'message'=>'Imagen guardada exitosamente',
                'image' => $image_name
            );
        }
        return response()->json($response,$response['code']);
    }
    public function avatar($filename){
        $exist=\Storage::disk('users')->exists($filename);
        if($exist){
            $file=\Storage::disk('users')->get($filename);
            return new Response($file,200);
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Imagen no existe'
            );
            return response()->json($response,$response['code']);
        }
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

    */
}
