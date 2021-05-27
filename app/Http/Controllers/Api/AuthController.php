<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('apiJwt', ['except' => ['login']]);
        $this->middleware('auth:api', ['except' => ['login', 'createAccount']]);
    }
    public function login(Request $request)
    {
        try{
            $credentials = $request->only(['email', 'password']);
            if (!$token = $this->guard()->attempt($credentials)) {
                return response()->json(['error' => 'Campos inválidos.'], Response::HTTP_UNAUTHORIZED);
            }
            $usuario = $this->guard()->user();
            return $this->respondWithToken($token, $usuario);
        }catch(Exception $e){
            return response()->json(
                [
                    'mensagem' => $e->getMessage(),
                    'sucesso_request' => false,
                    'dados' => null
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function createAccount(Request $request){
        try{
            $data = $request->only(['name', 'email', 'password', 'password_confirmation']);
            $validator = $this->validator($data);
            
            if($validator->fails()){
                return response()->json(
                    [
                        'sucesso_request' => false,
                        'dados' => null,
                        'validator' => $validator->errors(),
                    ],
                );
            }else{
                $user = new User();
                $user->name = mb_convert_case($data['name'], MB_CASE_TITLE, 'UTF-8');
                $user->email = $data['email'];
                $user->password = Hash::make($data['password']);
                
                $user->save();
                if(!$token = $this->guard()->attempt($data)){
                    return response()->json(['error' => 'Campos inválidos.'], Response::HTTP_UNAUTHORIZED);
                }
                $usuario = $this->guard()->user();
                return $this->respondWithToken($token, $usuario);
            }
        }catch(Exception $e){
            return response()->json(
                [
                    'mensagem' => $e->getMessage(),
                    'sucesso_request' => false,
                    'dados' => null
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function refresh()
    {
       try{
           $newToken= $this->guard()->refresh(true, true);
           return $this->respondWithToken($newToken);
       }catch(Exception $e){
            return response()->json(['mensagem' => 'Usuário não autorizado.'], Response::HTTP_UNAUTHORIZED);
       }
    }

    public function logout()
    {
        try{
            $this->guard()->logout(true);
            return response()->json(['mensagem' => 'logout']);
        }catch(Exception $e){
            return response()->json(['mensagem' => 'Usuário não autorizado.'], Response::HTTP_UNAUTHORIZED);
        }
    }


    protected function respondWithToken(string $token, $usuario = [])
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'usuario' => $usuario
        ]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);
    }

 


}
