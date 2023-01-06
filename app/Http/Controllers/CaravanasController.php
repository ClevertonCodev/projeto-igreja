<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\caravanas;
use App\Repositories\CaravanasRepository;
use Illuminate\Http\Request;

class CaravanasController extends Controller
{
    public function __construct(caravanas $caravanas)
    {
        $this->caravanas = $caravanas;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $modeloRepository = new CaravanasRepository($this->caravanas);

        if($request->has('atributos_estacas')) {
            
            $atributos_estacas = 'estacas:id,'.$request->atributos_estacas;
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_estacas);
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
         
        $request->validate($this->caravanas->rules(), $this->caravanas->feedback());
        $type = auth()->user()->type;
        
        if($type == 'secretarios'){
            
            $caravanas = $this->caravanas->create($request->all());
            return response()->json($caravanas, 200);
        }
        return response()->json(['erro' => 'você não tem autorização'], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\caravanas  $caravanas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $caravanas = $this->caravanas->find($id);
        if ($caravanas == null) {

            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return  response()->json($caravanas, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\caravanas  $caravanas
     * @return \Illuminate\Http\Response
     */
    // public function edit(caravanas $caravanas)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\caravanas  $caravanas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $caravanas = $this->caravanas->find($id);



        if ($caravanas === null) {
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {

            $regrasvalidate = array();

            foreach ($caravanas->rules() as $input => $regra) {


                if (array_key_exists($input, $request->all())) {
                    $regrasvalidate[$input] = $regra;
                }
            }
            $request->validate($regrasvalidate, $caravanas->feedback());
        } else {
            $request->validate($caravanas->rules(), $caravanas->feedback());
        }

        $caravanas->update($request->all());

        return response()->json($caravanas, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\caravanas  $caravanas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $caravanas =$this->caravanas->find($id);

        if ($caravanas === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $caravanas->delete();
        return response()->json(['msg' => 'Caravana foi removida com sucesso!'],);
    }
    
}
