<?php

namespace App\Http\Controllers;

use App\Models\Estacas;
use Illuminate\Http\Request;

class EstacasController extends Controller
{
    public function __construct(Estacas $estacas)
    {
        $this->Estacas = $estacas;
    }
    /**
     * Display a listing of the resource.
     * 
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $estacas = $this->Estacas->all();
        return response()->json($estacas, 200);
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
        $request->validate($this->Estacas->rules(), $this->Estacas->feedback());

        $estacas = $this->Estacas->create($request->all());
        return response()->json($estacas, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estacas  $estacas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estacas = $this->Estacas->find($id);
        if ($estacas == null) {

            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return  response()->json($estacas, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estacas  $estacas
     * @return \Illuminate\Http\Response
     */
    // public function edit(Estacas $estacas)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estacas  $estacas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $estacas = $this->Estacas->find($id);

        if ($estacas === null) {
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }
        if ($request->method() === 'PATCH') {

            $regrasvalidate = array();

            foreach ($estacas->rules() as $input => $regra) {


                if (array_key_exists($input, $request->all())) {
                    $regrasvalidate[$input] = $regra;
                }
            }
            $request->validate($regrasvalidate, $estacas->feedback());
        } else {
            $request->validate($estacas->rules(), $estacas->feedback());
        }



        $estacas->update($request->all());

        return response()->json($estacas, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estacas  $estacas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $estacas = $this->Estacas->find($id);

        if ($estacas === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $estacas->delete();
        return response()->json(['msg' => 'A estaca foi removida com sucesso!'], 200);
    }
}
