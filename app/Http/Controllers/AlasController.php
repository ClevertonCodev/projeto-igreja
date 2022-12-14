<?php

namespace App\Http\Controllers;

use App\Models\Alas;
use Illuminate\Http\Request;
use App\Repositories\AlasRepository;

class AlasController extends Controller
{   
    public function __construct(Alas $alas)
    {
        $this->Alas = $alas;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $modeloRepository = new AlasRepository($this->Alas);

        if($request->has('atributos_estacas')) {
            
            $atributos_estacas = 'estacas:id,'.$request->atributos_estacas;
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_estacas);
            dd($modeloRepository);
        } else {
            
            $modeloRepository->selectAtributosRegistrosRelacionados('estacas');
        }

        if($request->has('filtro')) {
            $modeloRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $modeloRepository->selectAtributos($request->atributos );
        } 

        return response()->json($modeloRepository->getResultado(), 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
        
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $alas = $this->Alas->create($request->all());
        return response()->json($alas, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alas  $alas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alas = $this->Alas->find($id);
        if ($alas == null) {

            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return  response()->json($alas, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alas  $alas
     * @return \Illuminate\Http\Response
     */
    // public function edit(Alas $alas)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alas  $alas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $alas = $this->Alas->find($id);

        if ($alas === null) {
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {

            $regrasvalidate = array();

            foreach ($alas->rules() as $input => $regra) {


                if (array_key_exists($input, $request->all())) {
                    $regrasvalidate[$input] = $regra;
                }
            }
            $request->validate($regrasvalidate, $alas->feedback());
        } else {
            $request->validate($alas->rules(), $alas->feedback());
        }

        $alas->update($request->all());

        return response()->json($alas, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alas  $alas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $alas =$this->Alas->find($id);

        if ($alas === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $alas->delete();
        return response()->json(['msg' => 'Ala foi removida com sucesso!'],);
    }
}
