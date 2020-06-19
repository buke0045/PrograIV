<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{
    private $key;
    function __construct(){
        $this->key = '12qsaq24wadsfa321sds';
    }
    public function signin($username,$password){
        $user=User::where([
            'username'=> $username,
            'password'=>hash('sha256',$password)
        ])->first();
        if(is_object($user)){
            $token=array(
                'sub'=>$user->id,
                'name'=>$user->name,
                'last_name'=>$user->last_name,
                'username'=>$user->username,
                'email'=>$user->email,
                'iat'=>time(),
                'exp'=>time()+(12000)
            );
            $data=JWT::encode($token,$this->key,'HS256');
        }
        else{
            $data=array(
                'status'=>'error',
                'code'=>401,
                'message'=>'Datos de autenticacion incorrectos'
            );
        }
        return $data;
    }
    public function checkToken($jwt,$getIdentity=false){
        $auth=false;
        try{
            $decoded=JWT::decode($jwt,$this->key,['HS256']);
        }
        catch(\UnexpectedValueException $ex){
            $auth=false;
        }
        catch(\DomainException $ex){
            $auth=false;
        }
        if(!empty($decoded)&&is_object($decoded)&&isset($decoded->sub)){
            $auth=true;
        }
        if($getIdentity){
            return $decoded;
        }
        return $auth;
    }
}

