<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Provincia;
 
class AutoCompleteProvinciaController extends Controller
{
// Autocpmletar para provicnias 
    public function index()
    {	
        return view('searchprov');
    }
 
    public function search(Request $request)
    {
          $search = $request->get('term');
      
          $result = Provincia::where('nombre', 'LIKE', '%'. $search. '%')->get();
          return response()->json($result);
            
    } 
}
