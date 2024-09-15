<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use App\Model\Provincia;
use App\User;
use Auth;

class FilterController extends Controller
{
    public function listarFiltros(){
        if (Auth::user()->can('Administrar Filtros')){
            $filtros = Permission::where('guard_name', 'filters')->get();
            return view('filters', compact('filtros'));
        } else {
            flash('No tienes permiso para hacer eso.')->error();
            return back(); 
        }
      }
    
    public function renombrarFiltro(Request $request, Permission $filter){
        if (Auth::user()->can(['Administrar Filtros', 'Editar Filtros'])){
            $filtro = Permission::where('id', $filter->id)->where('guard_name', 'filters')->first();
            if($filtro) {
                if ($request->newName) {
                    $nuevo = Permission::where('name', $request->newName)->where('guard_name', 'filters')->first();
                    if($nuevo) {
                        return redirect()->back()->with('error_rename','Ya existe el filtro!')->with('id_error', $filter->id);
                    } else {
                        $filtro->name = $request->newName;
                        $filtro->save();
                        return redirect()->route('admin.listarFiltros')->with('info','Filtro actualizado!');
                    }
                } else {
                    return redirect()->back()->with('error_rename','El nombre del filtro no puede estar vacío.')->with('id_error', $filter->id);
                }
            }
        } else {
            flash('No tienes permiso para hacer eso.')->error();
            return back(); 
        }
    }

    public function crearFiltro(Request $request){
        if (Auth::user()->can(['Administrar Filtros', 'Crear Filtros'])){
            if($request->newFilterName){
                $filtro = Permission::where('name', $request->newFilterName)->where('guard_name', 'filters')->first();
                if($filtro) {
                    return redirect()->back()->with('error_create','Ya existe el filtro!')->with('id', $filtro->id);
                } else {
                    Permission::create(['name' => $request->newFilterName, 'guard_name' => 'filters']);
                    return redirect()->route('admin.listarFiltros')->with('info','Filtro creado!');
                }
            } else {
                return redirect()->back()->with('error_create','El nombre del filtro no puede estar vacío.');
            }   
        } else {
            flash('No tienes permiso para hacer eso.')->error();
            return back(); 
        }
    }

    public function eliminarFiltro(Request $request, $filter) {
        if (Auth::user()->can('Administrar Filtros')){
            $filtro = Permission::where('id', $filter)->where('guard_name', 'filters')->first();
            $nombre = $filtro->name;
            if (Auth::user()->can('Eliminar Filtros')) {
                // tengo que usar esta consulta ya que spatie no tiene implementado el User::permission(permission_name)->get() para multiples guards
                $users = User::whereHas('permissions', function ($query) use ($filtro) {
                    $query->where('name', $filtro->name)->where('guard_name', 'filters');
                })->get();
                // tengo que cambiar el guard del filtro antes de quitarselo a los usuarios y eliminarlo ya que spatie no tiene implementadas estas funciones para multiples guards
                $filtro->setAttribute('guard_name', 'web');
                $filtro->save();
                foreach ($users as $user) {
                    $user->revokePermissionTo($filtro->name);
                }
                $filtro->delete();
                $respuesta = ['statusCode'=> 200,'message' => 'Se eliminó el filtro "'.$nombre.'"!'];
            } else {
                $respuesta = ['statusCode'=> 304,'message' => 'No tenés permiso para eliminar filtros.'];
            }
            return response()->json($respuesta);
        } else {
            flash('No tienes permiso para hacer eso.')->error();
            return back(); 
        }
    }

    public function listarFiltrosProvs(Request $request){
        $filtros = Permission::where('guard_name', 'filters')->get()->pluck('name');
        $provincias = Provincia::all();
        return response()->json(['filtros' => $filtros, 'provincias' => $provincias]);
    }

    public function editarFiltrosProvs(Request $request){
        if (Auth::user()->can(['Administrar Filtros', 'Crear Filtros','Eliminar Filtros'])){
            $nuevos = ($request->provincias == null) ? [] : $request->provincias;
            $filtros = Permission::where('guard_name', 'filters')->get()->pluck('name');
            // si el cod de alguna provincia de la colección enviada desde la view no está en la lista de filtros, creo el filtro
            foreach ($nuevos as $cod_provincia) {
                if (!$filtros->contains($cod_provincia)){
                    Permission::create(['name' => $cod_provincia, 'guard_name' => 'filters']);
                }
            }
            // tomo todos los codigos de las provincias de la base y para cada uno de estos codigos verifico:
            $provincias = Provincia::all()->pluck('codigo');
            // si existe un filtro cuyo nombre = el codigo y el código no está en la colección de provincias enviada desde la view, elimino el filtro
            foreach ($provincias as $cod_provincia) {
                if ($filtros->contains($cod_provincia) and (empty($nuevos) or !in_array($cod_provincia, $nuevos))){
                    $filtro = Permission::where('name', $cod_provincia)->where('guard_name', 'filters')->first();
                    // tengo que usar esta consulta ya que spatie no tiene implementado el User::permission(permission_name)->get() para multiples guards
                    $users = User::whereHas('permissions', function ($query) use ($filtro) {
                        $query->where('name', $filtro->name)->where('guard_name', 'filters');
                    })->get();
                    // tengo que cambiar el guard del filtro antes de quitarselo a los usuarios y eliminarlo ya que spatie no tiene implementadas estas funciones para multiples guards
                    $filtro->setAttribute('guard_name', 'web');
                    $filtro->save();
                    foreach ($users as $user) {
                        $user->revokePermissionTo($filtro->name);
                    }
                    $filtro->delete();
                }
            }
            return redirect()->route('admin.listarFiltros')->with('info','Filtros de Provincias actualizados!');
        } else {
            flash('No tienes permiso para hacer eso.')->error();
            return back(); 
        }
    }
}
