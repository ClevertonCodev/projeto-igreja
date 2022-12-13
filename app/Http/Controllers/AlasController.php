<?php

namespace App\Http\Controllers;

use App\Models\Alas;
use Illuminate\Http\Request;

class AlasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alas = Alas::all();
        return $alas;
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
        $alas = Alas::create($request->all());
        return $alas;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alas  $alas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alas = Alas::find($id);
        if ($alas == null) {

            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return  $alas;
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
        $alas = Alas::find($id);

        if ($alas === null) {
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }

        $alas->update($request->all());
        return $alas;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alas  $alas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $alas = Alas::find($id);

        if ($alas === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $alas->delete();
        return response()->json(['msg' => 'Ala foi removida com sucesso!'],);
    }
}
