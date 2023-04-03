<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UsersRepository;

use function PHPSTORM_META\type;

class UsersController extends Controller
{
    public function __construct(User $users)
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

        $modeloRepository = new UsersRepository($this->Users);

        if ($request->has('atributos_alas')) {

            $atributos_alas = 'alas:id,' . $request->atributos_alas;
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_alas);
        } else {

            $modeloRepository->selectAtributosRegistrosRelacionados('alas');
        }

        if ($request->has('filtro')) {
            $modeloRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $modeloRepository->selectAtributos($request->atributos);
        }


        return response()->json($modeloRepository->getResultado(), 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

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
        $type = auth()->user()->type;

        $validUserTypes = [
            'super' => ['super', 'secretarios', 'comum'],
            'secretarios' => ['secretarios', 'comum'],
            'comum' => ['comum']
        ];

        if (!isset($validUserTypes[$type])) {
            return response()->json(['erro' => 'você não tem autorização'], 404);
        }

        if (!in_array($user, $validUserTypes[$type])) {
            return response()->json(['erro' => 'você não tem autorização'], 404);
        }

        if ($user == 'comum' && empty($ala)) {
            return response()->json(['erro' => 'Ala é obrigatório'], 400);
        }

        $users = User::create(array_merge(
            $request->only('name', 'email', 'active', 'type', 'rg', 'cpf', 'telefone', 'endereço', 'alas_id'),
            ['password' => Hash::make($request->password)],
        ));

        return response()->json($users, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $users
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
     * @param  \App\Models\User  $users
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
     * @param  \App\Models\User  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $users = $this->Users->find($id);

        if ($users === null) {
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }
        if ($request->has('password')) {
            $users->update(['password' => bcrypt($request->password)]);
            return response()->json($users, 200);
        }
        $request->validate($users->rules(), $users->feedback());

        $users->update(array_merge(
            $request->only('name', 'email', 'active', 'type', 'rg', 'cpf', 'telefone', 'endereço', 'alas_id')
        ));



        return response()->json($users, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $users
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
