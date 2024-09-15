<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Provincia;
use App\Model\Departamento;
use App\Model\Localidad;
use App\Model\Radio;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
	    flash('Cantidad de Provincias cargadas: '.Provincia::count());
            flash('Cantidad de Departamentos/Partidos/Comunas cargados: '.Departamento::count());
            flash('Cantidad de Localidades cargados: '.Localidad::count());
//            flash('Cantidad de Entidades cargados: '.Entidades::count());
            flash('Cantidad de Radios cargados: '.Radio::count());
            flash('Cantidad de Radios segmentados: '.Radio::whereNotNull('resultado')->count())->success();
        return view('home');
    }
}
