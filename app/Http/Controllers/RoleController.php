<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use App\User;
use Auth;

class RoleController extends Controller
{
    public function listarRoles(){
        if (Auth::user()->can('Administrar Roles')){
            $roles = Role::all();
            $permisos = Permission::where('guard_name', 'web')->get();
            $filtros = Permission::where('guard_name', 'filters')->get();
            return view('roles', compact('roles','permisos','filtros'));
        } else {
            flash('No tienes permiso para hacer eso.')->error();
            return back(); 
        }
    }

    public function editarRol(Request $request, Role $role){
        if (Auth::user()->can(['Administrar Roles', 'Editar Roles'])){
            $rol = Role::find($role)->first();
            if($rol) {
                if($request->newName){
                    $nuevo = Role::where('name', $request->newName)->first();
                    if($nuevo) {
                        return redirect()->back()->with('error_rename','Ya existe el rol!')->with('id_error', $role->id);
                    } else {
                        $rol->name = $request->newName;
                        $rol->save();
                    }      
                }
                if($request->role_type == "permisos"){
                    if($request->permisos){
                        // actualizo el guard antes de hacer la sincronizacion
                        $rol->setAttribute('guard_name', 'web');
                        $rol->save();
                        $rol->syncPermissions($request->permisos);
                        return redirect()->route('admin.listarRoles')->with('info','Rol actualizado!');
                    } else {
                        return redirect()->back()->with('error_authorization_edit','Debe asignar al menos un permiso/filtro')->with('id_error', $role->id);
                    }
                } elseif($request->role_type == "filtros") {
                    if($request->filtros){
                        // tomo los modelos ya que si a syncPermissions() le mando una lista de ids intentara sincronizar usando el guard default (web)
                        $filtros = Permission::find($request->filtros);
                        // actualizo el guard antes de hacer la sincronizacion
                        $rol->setAttribute('guard_name', 'filters');
                        $rol->save();
                        $rol->syncPermissions($request->filtros);
                        return redirect()->route('admin.listarRoles')->with('info','Rol actualizado!');
                    } else {
                        return redirect()->back()->with('error_authorization_edit','Debe asignar al menos un permiso/filtro')->with('id_error', $role->id);
                    }
                }
            }
        } else {
            flash('No tienes permiso para hacer eso.')->error();
            return back(); 
        }
    }
    
    public function crearRol(Request $request){
        if (Auth::user()->can(['Administrar Roles', 'Crear Roles'])){
            if($request->newRoleName){
                $rol = Role::where('name', $request->newRoleName)->first();
                if($rol) {
                    return redirect()->back()->with('error_create','Ya existe el rol!')->with('id', $rol->id);
                } else {
                    if($request->role_type == "permisos"){
                        if($request->permisos){
                            $nuevoRol = Role::create(['guard_name' => 'web','name' => $request->newRoleName]);
                            $nuevoRol->syncPermissions($request->permisos);
                            return redirect()->route('admin.listarRoles')->with('info','Rol creado!');
                        } else {
                            return redirect()->back()->with('error_authorizations_new','Debe asignar al menos un permiso/filtro');
                        }
                    } elseif($request->role_type == "filtros") {
                        $nuevoRol = Role::create(['guard_name' => 'filters','name' => $request->newRoleName]);
                        // tomo los modelos ya que si a syncPermissions() le mando una lista de ids intentara sincronizar usando el guard default (web)
                        $filtros = Permission::find($request->filtros);
                        $nuevoRol->syncPermissions($request->filtros);
                        return redirect()->route('admin.listarRoles')->with('info','Rol creado!');
                    } else {
                        return redirect()->back()->with('error_authorizations_new','Debe asignar al menos un permiso/filtro');
                    }       
                }
            } else {
                return redirect()->back()->with('error_create','El nombre del rol no puede estar vacío.');
            }
        } else {
            flash('No tienes permiso para hacer eso.')->error();
            return back(); 
        }
    }

    public function detallesRol(Request $request, $role) {
        $rol = Role::find($role);
        $autorizaciones = $rol->permissions()->pluck('name');
        if (Auth::user()->hasRole('Super Admin')) {
            $autorizaciones_usuario = Permission::all();
        } else {
            $autorizaciones_usuario = Auth::user()->permissions;
        }
        return response()->json([
            'rol' => $rol,
            'autorizaciones' => $autorizaciones,
            'autorizaciones_usuario' => $autorizaciones_usuario->pluck('name')
        ]);
    }

    public function eliminarRol(Request $request, $role) {
        if (Auth::user()->can('Administrar Roles')){
            $rol = Role::find($role);
            $nombre = $rol->name;
            if ($rol->name != 'Super Admin') {
                if (Auth::user()->can('Eliminar Roles')) {
                    if ($rol->guard_name == 'filters') {
                        // tengo que usar esta consulta ya que spatie no tiene implementado el User::role(role_name)->get() para multiples guards
                        $users = User::whereHas('roles', function ($query) use ($rol) {
                            $query->where('name', $rol->name)->where('guard_name', 'filters');
                        })->get();
                        // tengo que cambiar el guard del rol antes de quitarselo a los usuarios y eliminarlo ya que spatie no tiene implementadas estas funciones para multiples guards
                        $rol->setAttribute('guard_name', 'web');
                        $rol->save();
                    } else {
                        $users = User::role($rol->name)->get();
                    }
                    foreach ($users as $user) {
                        $user->removeRole($rol->name);
                    }
                    $rol->syncPermissions([]);
                    $rol->delete();
                    $respuesta = ['statusCode'=> 200,'message' => 'Se eliminó el rol "'.$nombre.'"!'];
                } else {
                    $respuesta = ['statusCode'=> 304,'message' => 'No tenés permiso para eliminar roles.'];
                }
            } else {
                $respuesta = ['statusCode'=> 304,'message' => 'No se puede eliminar el rol Super Admin.'];
            }
            return response()->json($respuesta);
        } else {
            flash('No tienes permiso para hacer eso.')->error();
            return back(); 
        }
    }
}