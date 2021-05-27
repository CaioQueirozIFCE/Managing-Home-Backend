<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $data = $request->only([
                'name', 'email', 'password', 'password_confirmation'
            ]);

            $validator = $this->validator($data);

                if($validator->fails()){
                    return response()->json(['mesage' => 'erro ao validar','validator' => $validator, 'data' => $data]);
                    die();
                }else{
                    $user = new User();
                    $user->name = mb_convert_case($data['name'], MB_CASE_TITLE, 'UTF-8');
                    $user->email = $data['email'];
                    $user->password = Hash::make($data['password']);
                    
                    $user->save();
                    
                    return response()->json(['user' => [$data['name'], $data['email']]]);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
