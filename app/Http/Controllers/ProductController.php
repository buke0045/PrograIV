<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Supplier;
use App\Category;
use Illuminate\Http\Response;
class ProductController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth',['except'=>['index','show','getImage']]);
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
        //{"id":"22","name":"M","description":"m","currentExist":"5","minExist":"2","price":"5000","image":"null","created_at":"null","updated_at":"null","idSupplier":1,"idCategory":"1"}
        $json=$request->input('json',null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'name'=>'required',
                'description' => 'required',
                'currentExist' => 'required',
                'minExist' => 'required',
                'price' => 'required',
                'image' => 'required',
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
        //{"id":"22","name":"M","description":"m","currentExist":"9","minExist":"9","price":"5000","image":"null","idSupplier":1,"idCategory":"1"}
        $json= $request->input('json',null);
        $data=json_decode($json,true);
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'id'=>'required'
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
    public function upload(Request $request){
        $image=$request->file('file0');
        $validate=\Validator::make($request->all(),[
            'file0'=>'required|image|mimes:jpg,png,jpeg'
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

            \Storage::disk('products')->put($image_name,\File::get($image));

            $response=array(
                'status'=>'success',
                'code' => 200,
                'message'=>'Imagen guardada exitosamente',
                'image' => $image_name
            );
        }
        return response()->json($response,$response['code']);
    }
    public function getImage($filename){
        $exist=\Storage::disk('products')->exists($filename);
        if($exist){
            $file=\Storage::disk('products')->get($filename);
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
}
