<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        try{
            $d = ['login' => 'caio.cezar@gmail.com', 'password' => '121355']; //fazer consulta ao banco
            $data = $request->only(['login', 'password']);
            $validator = $this->validator($data);
            if($validator -> fails()){
                return response()->json(['error' => $validator, 'aviso' => 'erro']);
            }else{
                if($data['login'] === $d['login'] && $data['password'] === $d['password']){
                    return response()->json('logou');
                }else{
                    return response()->json('não logou');
                }
            }
        }catch(Exception $e){
            return $this->exceptionHandler($e);
        }
    }

    protected function validator(array $data)
    {
       return Validator::make($data, [
           'login' => ['required', 'string', 'max:100'],
           'password' => ['required', 'string', 'min:4']
       ]);
    }
}
