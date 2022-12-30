<?php

namespace App\Http\Controllers;

use App\Models\caravanas;
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
    public function index()
    {
        return 'oi';
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
        $caravanas = $this->caravanas->create($request->all());
        return response()->json($caravanas, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\caravanas  $caravanas
     * @return \Illuminate\Http\Response
     */
    public function show(caravanas $caravanas)
    {
        //
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
    public function update(Request $request, caravanas $caravanas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\caravanas  $caravanas
     * @return \Illuminate\Http\Response
     */
    public function destroy(caravanas $caravanas)
    {
        //
    }
}
