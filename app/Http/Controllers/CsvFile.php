<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CsvImport;
use App\Imports\CsvExport;
use App\Domicilio;
use Maatwebsite\Excel\Facades\Excel;

class CsvFile extends Controller
{
    //

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
	$domicilios = Domicilio::all()->take(10);
        return view('csv_file_pagination')->with('listado', $domicilios);;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function csv_export()
    {
        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function csv_import(Request $request)
    {
//  ->withOutput($this->output).
//         (new CsvImport)->withOutput($this->output)->import('domicilios.csv');

		(new CsvImport)->import($request->file('file'));
        
        return redirect('/')->with('success', 'All good!');
    }

}
