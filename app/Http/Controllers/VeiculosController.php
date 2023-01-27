<?php

namespace App\Http\Controllers;
use App\Models\Veiculos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\VeiculosRepository;

class VeiculosController extends Controller
{
    public function __construct(Veiculos $veiculos)
    {
        $this->Veiculos = $veiculos;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modeloRepository = new VeiculosRepository($this->Veiculos);

        $modeloRepository->selectAtributosRegistrosRelacionados('tipo_veiculos');
        $modeloRepository->selectAtributosRegistrosRelacionados('caravanas');
      
        

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
        $request->validate($this->Veiculos->rules(), $this->Veiculos->feedback());
        $veiculos = $this->Veiculos->create($request->all());
        return response()->json($veiculos, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Veiculos  $veiculos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  $veiculos = $this->Veiculos->find($id);
        if ($veiculos == null) {

            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return  response()->json($veiculos, 200);//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Veiculos  $veiculos
     * @return \Illuminate\Http\Response
     */
    // public function edit(Veiculos $veiculos)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Veiculos  $veiculos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $veiculos = $this->Veiculos->find($id);

        if ($veiculos === null) {
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }
        $request->validate($veiculos->rules(), $veiculos->feedback());


        $veiculos->update($request->all());

        return response()->json($veiculos, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Veiculos  $veiculos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $veiculos = $this->Veiculos->find($id);

        if ($veiculos === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $veiculos->delete();
        return response()->json(['msg' => ' O veiculo foi removido com sucesso!'], 200);
    }
}
