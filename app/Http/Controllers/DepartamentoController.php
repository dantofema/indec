<?php

namespace App\Http\Controllers;

use App\Model\Departamento;
use App\Model\Provincia;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Provincia $provincia = null)
    {
        //  
    if (is_null($provincia)) {$provincia=8;}
    	return view('deptos', ['provincia' => $provincia]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function edit(Departamento $departamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Departamento $departamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Departamento $departamento)
    {
        //
        //      dd($departamento->localidades) ;//->with('localidades')->get());
        $departamento->delete();//->with('localidades')->get());
        dd($departamento) ;//->with('localidades')->get());
    }


    public function list(Provincia $provincia)
    {   
	    $deptos = $provincia->departamentos()
            ->with('localidades')
            ->withCount(['localidades' =>function ($query) {
                $query->where('codigo','not like','%000');
              }
              ,'fracciones','radios',
              'radios as segmentados' => function ($query) {
                $query->whereNotNull('resultado');
              }])->get(); 
	    //Departamento::where('provincia_id',$provincia)->get(['codigo','nombre']);
        return datatables()->of($deptos)
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
   public function show(Departamento $departamento)
    {
        //
        return view('deptoview',['departamento' =>
        $departamento->loadCount('localidades')]);
    }

    public function show_post(Departamento $departamento)
    {
        //
        //return view('provinfo',['provincia' => Provincia::withCount('departamentos')->findOrFail($provincia)]);
        return view('deptoinfo',['departamento' => $departamento->loadCount('localidades')]);
    }

}
