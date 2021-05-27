<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    protected $relationships = ['usuario'];

    public function index()
    {
    }

    public function showListWallet(Request $request)
    {
        try {
            $data = $request->only(['id']);
            $wallet = Wallet::where('id_user', $data['id'])->get();

            return response()->json([
                'wallet' => $wallet
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro' => $e
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->only(['id_user', 'descricao', 'valor', 'tipo', 'frequencia', 'data']);
            $user = User::find($data['id_user']);
            if (!$user) {
                return response()->json(
                    'Usuário inválido'
                );
            } else {
                $itemWallet = new Wallet();
                $itemWallet->id_user = $data['id_user'];
                $itemWallet->descricao = mb_convert_case($data['descricao'], MB_CASE_TITLE, 'UTF8');
                $itemWallet->valor = $data['valor'];
                $itemWallet->tipo = $data['tipo'] === 'entrada' ? 0 : 1;
                $itemWallet->frequencia = $data['frequencia'] ? 1 : 0;
                $itemWallet->data = $data['data'];

                $itemWallet->save();

                return response()->json([
                    'itemWallet' => $itemWallet
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'erro' => $e
            ]);
        }
    }


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
    public function update(Request $request)
    {
        try {
            $data = $request->only(['id_user', 'id', 'descricao', 'valor', 'tipo', 'frequencia', 'data']);
            $user = User::find($data['id_user']);
            $itemWallet = Wallet::find($data['id']);
            
            if (!$user || !$itemWallet) {
                return response()->json(
                    'Usuário inválido ou item inválido!'
                );
            } else {
                $itemWallet->descricao = mb_convert_case($data['descricao'], MB_CASE_TITLE, 'UTF8');
                $itemWallet->valor = $data['valor'];
                $itemWallet->tipo = $data['tipo'] === 'entrada' ? 0 : 1;
                $itemWallet->frequencia = $data['frequencia'] ? 1 : 0;
                $itemWallet->data = $data['data'];

                $itemWallet->save();

                return response()->json([
                    'itemWallet' => $itemWallet
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'erro' => $e
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) //passar o id do item + o id_user
    {
        //
    }
}
