<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Listado;

class ListadoController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $listado = Listado::latest()->paginate();
        return view('listado.all', compact('listado'));
//        $listado = Listado::all()->take(10);
//        return $listado;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($number = 2)
    {
        for($i =1; $i <= 10 ; $i++){
            echo "$i * $number = ". $i* $number ."<br>";
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Listado  $listado
     * @return \Illuminate\Http\Response
     */
    public function show(Listado $listado)
    {
        //
        return view('listado.listado',['listado' => $listado]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Listado  $listado
     * @return \Illuminate\Http\Response
     */
    public function edit(Listado $listado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Listado  $listado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Listado $listado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Listado  $listado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Listado $listado)
    {
        //
    }

}
