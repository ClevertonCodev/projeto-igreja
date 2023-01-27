<?php

namespace App\Http\Controllers;

use App\Models\TipoVeiculos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoVeiculosController extends Controller
{   
    public function __construct(TipoVeiculos  $tipoVeiculos)
    {
        $this->TipoVeiculos =  $tipoVeiculos;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipoVeiculos = $this->TipoVeiculos::all();
        return response()->json( $tipoVeiculos, 200);
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
        $request->validate($this->TipoVeiculos->rules(), $this->TipoVeiculos->feedback());
        $tipoVeiculos = $this->TipoVeiculos->create($request->all());
        return response()->json( $tipoVeiculos, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoVeiculos  $tipoVeiculos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $tipoVeiculos = $this->TipoVeiculos->find($id);
        if ($tipoVeiculos == null) {

            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return  response()->json($tipoVeiculos, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoVeiculos  $tipoVeiculos
     * @return \Illuminate\Http\Response
     */
    // public function edit(TipoVeiculos $tipoVeiculos)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoVeiculos  $tipoVeiculos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tipoVeiculos = $this->TipoVeiculos->find($id);

        if ($tipoVeiculos === null) {
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }
        $request->validate($tipoVeiculos->rules(), $tipoVeiculos->feedback());


        $tipoVeiculos->update($request->all());

        return response()->json($$tipoVeiculos, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoVeiculos  $tipoVeiculos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $tipoVeiculos = $this->TipoVeiculos->find($id);

        if ($tipoVeiculos === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $tipoVeiculos->delete();
        return response()->json(['msg' => ' O tipo do veiculo foi removido com sucesso!'], 200);
    }
}
