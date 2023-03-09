<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
  public function __construct(User $users)
    {
        $this->User= $users;

    }

  public function login(Request $request)
  {
    $email =  $request->email;
    $results = $this->User->where('email', $email)->get('active');
    $active = $results ->implode('active');

    if($active == 1){

      $credenciais = $request->all(['email', 'password']);
      $token = auth('api')->setTTL(1800)->attempt($credenciais);
      $refreshToken = auth('api')->setTTL(3600)->refresh();

      if($token){
       return response()->json(
        ['token'=> $token,
        'refreshtoken'=>  $refreshToken]);
      }else{
       return response()->json(['erro' => 'Usuário ou senha inválido!'], 403);

      }
    }
    return response()->json(['erro' => 'você não tem autorização'], 404);
  }

  public function logout()
  {
    auth('api')->logout();
    return response()->json(['msg' => 'Lougout foi realizado com sucesso!']);
  }

  public function refresh()
  {
    $token = auth('api')->refresh();
    return response()->json(['token' => $token]);
  }

  public function me()
  {
    return response()->json(auth()->user());

  }
}
