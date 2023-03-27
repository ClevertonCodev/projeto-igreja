<?php

namespace App\Http\Controllers;

use App\Models\caravanas;
use App\Models\Caravanas_veiculos;
use App\Models\Veiculos;
use App\Repositories\CaravanasRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        if ($request->has('atributos_estacas')) {

            $atributos_estacas = 'estacas:id,' . $request->atributos_estacas;
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_estacas);
        } else {

            $modeloRepository->selectAtributosRegistrosRelacionados('estacas');
            $modeloRepository->selectAtributosRegistrosRelacionados('veiculos');
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

        if ($type == 'secretarios') {
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

        $caravanas->load('veiculos');
        $veiculos = $caravanas->veiculos;
        $veiculos->load('tipo_veiculos');
        return  response()->json($caravanas, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\caravanas  $caravanas
     * @return \Illuminate\Http\Response
     */

    public function caravanashasveiculoslivres($id)
    {
        $caravanas = $this->caravanas->find($id);
        if ($caravanas == null) {

            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        $dataHoraPartida = $caravanas->DataHora_partida;
        $dataHoraRetorno = $caravanas->DataHora_retorno;
        $veiculos = Veiculos::all();
        $veiculos->load('tipo_veiculos');
        $veiculosDisponiveis = [];

        foreach ($veiculos as $veiculo) {

            $caravanaVeiculo = Caravanas_veiculos::where('veiculos_id', $veiculo->id)->get();

            $caravanasValidas = [];

            foreach ($caravanaVeiculo as $cv) {
                $caravana = $this->caravanas->find($cv->caravanas_id);
                if ($caravana && $caravana->Status == 'Ativa' && $caravana->DataHora_partida <= $dataHoraRetorno && $caravana->DataHora_retorno >= $dataHoraPartida) {
                    $caravanasValidas[] = $caravana;
                }
            }
            if (count($caravanasValidas) == 0) {
                $veiculosDisponiveis[] = $veiculo;
            }


        }

        return response()->json($veiculosDisponiveis, 200);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\caravanas  $caravanas
     * @return \Illuminate\Http\Response
     */

    public function getone($id)
    {
        $caravanas = $this->caravanas->find($id);
        if ($caravanas == null) {

            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        $caravanas->load('veiculos');
        $veiculos = $caravanas->veiculos;
        $veiculos->load('tipo_veiculos');

        return  response()->json($veiculos, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\caravanas  $caravanas
     * @return \Illuminate\Http\Response
     */
    public function adicionarveiculos(Request $request, $id)
    {

        $caravanas = $this->caravanas->find($id);

        if ($caravanas == null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        $veiculos = $request->veiculo;

        if ($veiculos == null) {
            return response()->json(['erro' => 'Entidade veiculo não processável'], 422);
        }
        $Cadastrarveiculos = explode(',', $veiculos);

        $conflitos = collect();

        foreach ($Cadastrarveiculos as $veiculo_id) {
            $veiculo = Veiculos::find($veiculo_id);
            if ($veiculo == null) {
                return response()->json(['erro' => 'Veículo não encontrado'], 404);
            }
            $caravanas_em_conflito = $veiculo->caravanas()->where('DataHora_retorno', '>', $caravanas->DataHora_partida)->where('DataHora_partida', '<', $caravanas->DataHora_retorno)->get();
            if ($caravanas_em_conflito->count() > 0) {
                $conflitos = $conflitos->merge($caravanas_em_conflito);
            }
        }

        if ($conflitos->count() > 0) {
            return response()->json(['erro' => 'O veiculo está em uso'], 404);
        }

        $caravanas->Veiculos()->attach($Cadastrarveiculos);
        if ($caravanas) {
            $caravanas->load('veiculos');
            return response()->json($caravanas, 200);
        } else {
            return response()->json(['erro' => 'Algo deu errado'], 404);
        }
    }

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
        $caravanas = $this->caravanas->find($id);

        if ($caravanas === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }
        $caravanas->Veiculos()->detach();
        $caravanas->delete();
        return response()->json(['msg' => 'Caravana foi removida com sucesso!'],);
    }
}
