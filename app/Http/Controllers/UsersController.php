<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UsersRepository;


class UsersController extends Controller
{
    public function __construct(Users $users)
    {
        $this->Users = $users;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        
        $UsersRepository = new UsersRepository($this->Users);

        if($request->has('atributos_alas')) {
            
            $atributos_users = 'alas:id,'.$request->atributos_users;
            $UsersRepository->selectAtributosRegistrosRelacionados($atributos_users);
        } else {
            
            $UsersRepository->selectAtributosRegistrosRelacionados('alas');
        }

        if($request->has('filtro')) {
            $UsersRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $UsersRepository->selectAtributos($request->atributos);
        } 

        return response()->json($UsersRepository->getResultado(), 200);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->Users->rules(), $this->Users->feedback());

        $user = $request->type;
        $ala = $request->alas_id;


        if ($user == 'super' || $user == "secretarios" || $ala != '' && $user == 'comum' ) {

            $users = $this->Users->create(array_merge(
                $request->only('name', 'email', 'active', 'type', 'rg', 'cpf', 'telefone', 'endereço', 'alas_id'),
                ['password' => Hash::make($request->password)],
            ));

            return response()->json($users, 201);
        }

        return response()->json(['erro' => 'você não tem autorização'], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = $this->Users->find($id);
        if ($users == null) {

            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return  response()->json($users, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    // public function edit(Users $users)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $users = $this->Users->find($id);

        if ($users === null) {
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {

            $regrasvalidate = array();

            foreach ($users->rules() as $input => $regra) {


                if (array_key_exists($input, $request->all())) {
                    $regrasvalidate[$input] = $regra;
                }
            }
            $request->validate($regrasvalidate, $users->feedback());
        } else {
            $request->validate($users->rules(), $users->feedback());
        }

        $users->update($request->all());
        return response()->json($users, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $users = $this->Users->find($id);

        if ($users === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $users->delete();
        return response()->json(['msg' => 'Usuario removido com sucesso!'], 200);
    }
}
