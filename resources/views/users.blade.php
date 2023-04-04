@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container">
	<div class="row justify-content-center">
    <div class="card">
      <div class="card-header">{{ __('Lista de usuarios') }}</div>
      <div class="card-body">
        @if(Session::has('info'))
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('info')}}
          </div>
        @endif
        <table class="table table-bordered">
          @if($usuarios[0] !== null)
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Email</th>
              @can('Asignar Roles', 'Quitar Roles')
              <th> Permisos </th>
              <th>
                Roles
                <a href="#" class="badge badge-pill badge-primary">+</a>
              </th>
              @endcan
            </tr>
          </thead>
          <tbody>
            @foreach ($usuarios as $usuario)
            <tr>
              <td>{{$usuario->name}}</td>
              <td>{{$usuario->email}}</td>
              @can('Asignar Roles', 'Quitar Roles')
              <td>
                <!-- Button trigger modal -->
                <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#permisosModal{{$usuario->id}}">
                  Administrar Permisos
                </button>

                <!-- Modal permisos del usuario -->
                <div class="modal fade" id="permisosModal{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="permisoModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="permisoModalLabel">Permisos de {{$usuario->name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{route('admin.editarPermisoUsuario', $usuario->id)}}" method="put" id="form-permisos">
                        <div class="modal-body">
                          <table class="table">
                            <tbody>
                              @php 
                                $user_permissions = $usuario->getPermissionsViaRoles()->pluck('name');
                              @endphp
                              @foreach ($permisos as $permiso)
                              <tr>                                         
                                <td class="col align-self-center">
                                  @if ($user_permissions->contains($permiso->name))
                                    <input type="checkbox" disabled checked id="{{$permiso->name}}" name="permisos[]" value="{{$permiso->id}}" data-on="Si" data-off="No" data-toggle="toggle" data-size="sm">
                                  @else
                                    @if ($usuario->hasPermissionTo($permiso->name, $permiso->guard_name ))
                                      <input type="checkbox" checked id="{{$permiso->name}}" name="permisos[]" value="{{$permiso->id}}" data-on="Si" data-off="No" data-toggle="toggle" data-size="sm">
                                    @else
                                      <input type="checkbox" id="{{$permiso->name}}" name="permisos[]" value="{{$permiso->id}}" data-on="Si" data-off="No" data-toggle="toggle" data-size="sm">
                                    @endif
                                  @endif
                                    <label class="form-check-label" for="{{$permiso->name}}">
                                      {{$permiso->name}}
                                    </label>
                                    @if ($user_permissions->contains($permiso->name))
                                      <span class="badge badge-pill badge-danger">Heredado de rol</span>
                                    @endif
                                  </td>
                              </tr> 
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <input type="submit" name="btn"  class="btn btn-primary" value="Guardar Cambios" onclick="return confirmarCambios('permisos')">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              </td>
              <td>
                <!-- Button trigger modal -->
                <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#rolesModal{{$usuario->id}}">
                  Administrar Roles
                </button>

                <!-- Modal roles del usuario -->
                <div class="modal fade" id="rolesModal{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="rolesModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="rolesModalLabel">Roles de {{$usuario->name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{route('admin.editarRolUsuario', $usuario->id)}}" method="put" id="form-roles">
                        <div class="modal-body">
                          <table class="table">
                            <tbody>
                              @foreach ($roles as $rol)
                              <tr>
                                <td class="col align-self-center">
                                  @if ($rol->name == 'Super Admin')
                                    @if ($usuario->hasRole($rol->name))
                                      @if ($usuario->email != Auth::user()->email || $superadmins == 1)
                                      <input type="checkbox" disabled checked id="{{$rol->name}}" name="roles[]" value="{{$rol->id}}" data-on="Si" data-off="No" data-toggle="toggle" data-size="sm">
                                      @else
                                      <input type="checkbox" checked id="{{$rol->name}}" name="roles[]" value="{{$rol->id}}" data-on="Si" data-off="No" data-toggle="toggle" data-size="sm">
                                      @endif
                                    @else
                                      <input type="checkbox" id="{{$rol->name}}" name="roles[]" value="{{$rol->id}}" data-on="Si" data-off="No" data-toggle="toggle" data-size="sm">
                                    @endif
                                  @endif
                                  <label class="form-check-label" for="{{$rol->name}}">
                                    {{$rol->name}}
                                  </label>
                                  @if ($usuario->hasRole($rol->name))
                                    @if ($usuario->email != Auth::user()->email)
                                    <span class="badge badge-pill badge-danger">No se puede quitar este rol</span>
                                    @elseif ($superadmins == 1)
                                    <span class="badge badge-pill badge-danger">Único Super Admin</span>
                                    @endif
                                  @endif
                                  <button type="button" class="btn-sm btn-info float-right" data-toggle="modal" data-dismiss="modal" data-target="#detailsModal{{$rol->id}}">
                                    Detalles
                                  </button>
                                </td>                                
                              </tr> 
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <input type="submit" name="btn"  class="btn btn-primary" value="Guardar Cambios" onclick="return confirmarCambios('roles')">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- Modal de detalles del rol -->
                <div class="modal fade" id="detailsModal{{$rol->id}}" aria-hidden="true" aria-labelledby="detailsModalLabel" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="detailsModalLabel">Permisos del rol {{$rol->name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      <table class="table">
                        <tbody>
                          @foreach ($rol->permissions as $permiso)
                          <tr>
                            <td class="col align-self-center">
                              <label class="form-check-label" for="{{$rol->name}}">
                                {{$permiso->name}}
                              </label>
                            </td>                                
                          @endforeach
                        </tbody>
                      </table>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-primary" data-target="#rolesModal{{$usuario->id}}" data-toggle="modal" data-dismiss="modal">Volver</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              @endcan
          @endforeach
        </tr>
      </tbody>
      @else
      <h1>No hay usuarios registrados</h1>
      @endif
    </table>
    <div class="row justify-content-center">
      {{ $usuarios->links("pagination::bootstrap-4") }}
    </div>
	</div>
</div>

@endsection
@section('footer_scripts')
<script type="text/javascript">
  function confirmarCambios(tipo){
    return confirm("¿Estás seguro de que deseas guardar los nuevos " + tipo + "?");
  };
</script>
@endsection
