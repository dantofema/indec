@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

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
        <!-- <div class="card-header col-20">
          <div class="input-group-prepend">
            <input type="text" class="form-control" placeholder="Buscar por nombre o email">
          </div>  
        </div> -->
        <table class="table table-bordered">
          @if($usuarios[0] !== null)
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Email</th>
              <th>Roles</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($usuarios as $usuario)
            <tr>
              <td>{{$usuario->name}}</td>
              <td>{{$usuario->email}}</td>
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
                      <form action="{{route('admin.editarRolUsuario', $usuario->id)}}" method="put">
                        <div class="modal-body">
                          <table class="table">
                            <tbody>
                              @foreach ($roles as $rol)
                              <tr>
                                <td class="col align-self-center">
                                  <label class="form-check-label" for="{{$rol->name}}">
                                    {{$rol->name}}
                                  </label>
                                  <button type="button" class="btn-sm btn-secondary float-right" data-toggle="modal" data-dismiss="modal" data-target="#detailsModal{{$rol->id}}">
                                    Detalles
                                  </button>
                                </td>                                
                                <td class="col align-self-center">
                                  @if ($usuario->hasRole($rol->name))
                                    <input type="checkbox" checked id="{{$rol->name}}" name="roles[]" value="{{$rol->id}}" data-on="Si" data-off="No" data-toggle="toggle" data-size="sm">
                                  @else
                                    <input type="checkbox" id="{{$rol->name}}" name="roles[]" value="{{$rol->id}}" data-on="Si" data-off="No" data-toggle="toggle" data-size="sm">
                                  @endif
                                </td>
                              </tr> 
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <input type="submit" name="btn"  class="btn btn-primary" value="Guardar Cambios">
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
          @endforeach
        </tr>
      </tbody>
      @else
      <h1>No hay usuarios registrados</h1>
      @endif
    </table>
	</div>
</div>
</div>
</div>
@endsection