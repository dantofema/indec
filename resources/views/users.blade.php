@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container">
	<div class="row justify-content-center">
    <div class="card" style="width: 50rem;">
      <div class="card-header">{{ __('Lista de usuarios') }}</div>
      <div class="card-body">
        @if(Session::has('info'))
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('info')}}
          </div>
        @endif
        <table class="table table-bordered" id="tabla-usuarios">
          @if($usuarios[0] !== null)
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Email</th>
              @can('Administrar Permisos')
              <th> 
                Permisos
              </th>
              @endcan
              @can('Administrar Filtros')
              <th> 
                Filtros <a href="{{route('admin.listarFiltros')}}" class="badge badge-pill badge-primary">+</a>
              </th>
              @endcan
              @can('Administrar Roles')
              <th>
                Roles
                <a href="{{route('admin.listarRoles')}}" class="badge badge-pill badge-primary">+</a>
              </th>
              @endcan
            </tr>
          </thead>
          <tbody>
            @foreach ($usuarios as $usuario)
            <tr>
              <td>{{$usuario->name}}</td>
              <td>{{$usuario->email}}</td>
              @can('Administrar Permisos')
              <td>
                <div class="text-center">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn-sm btn-primary text-center" data-toggle="modal" id="btn-trigger-modal-permisos" data-target="#permisosModal{{$usuario->id}}">
                    Administrar Permisos
                  </button>
                </div>

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
                      <form action="{{route('admin.editarPermisoUsuario', $usuario->id)}}" method="put" id="form-permisos{{$usuario->id}}">
                        <div class="modal-body">
                          <table class="table" id="tabla-permisos">
                            <tbody>
                              @php 
                                $user_permissions = $usuario->getPermissionsViaRoles()->pluck('name');
                              @endphp
                              @if ($permisos->count() > 0)
                                @foreach ($permisos as $permiso)
                                <tr>                                         
                                  <td class="col align-self-center">
                                    @if ($user_permissions->contains($permiso->name) or $usuario->hasRole('Super Admin'))
                                      <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" disabled checked id="{{$permiso->name}}" name="permisos[]" value="{{$permiso->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                    @else
                                      @if ($usuario->hasPermissionTo($permiso->name, $permiso->guard_name ))
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" checked id="{{$permiso->name}}" name="permisos[]" value="{{$permiso->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      @else
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" id="{{$permiso->name}}" name="permisos[]" value="{{$permiso->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      @endif
                                    @endif
                                      <label class="form-check-label" for="{{$permiso->name}}">
                                        {{$permiso->name}}
                                      </label>
                                      @if ($user_permissions->contains($permiso->name) or $usuario->hasRole('Super Admin'))
                                        <span class="badge badge-pill badge-danger">Heredado de rol</span>
                                      @endif
                                    </td>
                                </tr> 
                                @endforeach
                              @else
                                No hay permisos cargados.
                              @endif
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <input type="submit" name="btn"  class="btn btn-primary btn-submit-permisos" value="Guardar Cambios" onclick="return confirmarCambios('permisos')">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </td>
              @endcan
              @can('Administrar Filtros')
              <td>
                <div class="text-center">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn-sm btn-primary text-center" data-toggle="modal" id="btn-trigger-modal-filtros" data-target="#filtrosModal{{$usuario->id}}">
                    Administrar Filtros
                  </button>
                </div>

                <!-- Modal filtros del usuario -->
                <div class="modal fade" id="filtrosModal{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="filtroModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="filtroModalLabel">Filtros de {{$usuario->name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{route('admin.editarFiltroUsuario', $usuario->id)}}" method="put" id="form-filtros{{$usuario->id}}">
                        <div class="modal-body">
                          <table class="table" id="tabla-filtros">
                            <tbody>
                              @php 
                                $user_filters = $usuario->getPermissionsViaRoles()->where('guard_name', 'filters')->pluck('name');
                              @endphp
                              @if ($filtros->count() > 0)
                                @foreach ($filtros as $filtro)
                                <tr>                                         
                                  <td class="col align-self-center">
                                    @if ($user_filters->contains($filtro->name))
                                      <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" disabled checked id="{{$filtro->name}}" name="filtros[]" value="{{$filtro->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                    @else
                                      @if ($usuario->hasPermissionTo($filtro->name, $filtro->guard_name ))
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" checked id="{{$filtro->name}}" name="filtros[]" value="{{$filtro->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      @else
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" id="{{$filtro->name}}" name="filtros[]" value="{{$filtro->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      @endif
                                    @endif
                                      <label class="form-check-label" for="{{$filtro->name}}">
                                        {{$filtro->name}}
                                      </label>
                                      @if ($user_filters->contains($filtro->name))
                                        <span class="badge badge-pill badge-danger">Heredado de rol</span>
                                      @endif
                                    </td>
                                </tr> 
                                @endforeach
                              @else
                                No hay filtros cargados.
                              @endif
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <input type="submit" name="btn"  class="btn btn-primary btn-submit-filtros" value="Guardar Cambios" onclick="return confirmarCambios('filtros')">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </td>
              @endcan
              @can('Administrar Roles')
              <td>
                <div class="text-center">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn-sm btn-primary" data-toggle="modal" id="btn-trigger-modal-roles" data-target="#rolesModal{{$usuario->id}}">
                    Administrar Roles
                  </button>
                </div>

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
                      <form action="{{route('admin.editarRolUsuario', $usuario->id)}}" method="put" id="form-roles{{$usuario->id}}">
                        <div class="modal-body">
                          <table class="table" id="tabla-roles">
                            <tbody>
                              @if ($roles->count() > 0)
                                @foreach ($roles as $rol)
                                <tr>
                                  <td class="col align-self-center">
                                    @if ($rol->name == 'Super Admin')
                                      @if ($usuario->hasRole($rol->name))
                                        <!-- No puedo quitarle el superadmin a otro usuario -->
                                        <!-- Si soy el único superadmin no puedo quitarme el rol -->
                                        @if ($usuario->email != Auth::user()->email || $superadmins == 1)
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" disabled checked id="{{$rol->name}}" name="roles[]" value="{{$rol->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                        @else
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" checked id="{{$rol->name}}" name="roles[]" value="{{$rol->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                        @endif

                                        <label class="form-check-label" for="{{$rol->name}}">
                                          {{$rol->name}}
                                        </label>

                                        <!-- Pills informativas para las condiciones comentadas arriba -->
                                        @if ($usuario->email != Auth::user()->email)
                                        <span class="badge badge-pill badge-danger">No se puede quitar este rol</span>
                                        @elseif ($superadmins == 1)
                                        <span class="badge badge-pill badge-danger">Único Super Admin</span>
                                        @endif
                                      @else
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" id="{{$rol->name}}" name="roles[]" value="{{$rol->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                        <label class="form-check-label" for="{{$rol->name}}">
                                          {{$rol->name}}
                                        </label>
                                        @endif
                                    @else
                                      @if ($usuario->hasRole($rol->name))
                                      <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" checked id="{{$rol->name}}" name="roles[]" value="{{$rol->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      @else
                                      <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" id="{{$rol->name}}" name="roles[]" value="{{$rol->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      @endif
                                      <label class="form-check-label" for="{{$rol->name}}">
                                        {{$rol->name}}
                                      </label>
                                    @endif
                                    @if ($rol->guard_name == "web")
                                      <span class="badge badge-pill badge-warning">Permisos</span>
                                    @else
                                      <span class="badge badge-pill badge-info">Filtros</span>
                                    @endif
                                    @if ($rol->permissions->isEmpty() and $rol->name != 'Super Admin') 
                                      <span class="badge badge-pill badge-danger">Vacío</span>
                                    @endif 
                                    <button type="button" class="btn-sm btn-primary float-right btn-detalles" data-toggle="modal" data-dismiss="modal" data-role-id="{{ $rol->id }}" data-user-id="{{ $usuario->id }}" data-target="#detailsModal">
                                      Detalles
                                    </button>
                                  </td>                                
                                </tr> 
                                @endforeach
                              @else
                                No hay roles cargados.
                              @endif
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <input type="submit" name="btn"  class="btn btn-primary btn-submit-roles" value="Guardar Cambios" onclick="return confirmarCambios('roles')">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- Modal de detalles del rol -->
                <div class="modal fade" id="detailsModal" aria-hidden="true" aria-labelledby="detailsModalLabel" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="detailsModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <h4 class='authorization-label'></h4>
                        <table class="authorization-table" style="width: 100%;">
                          <tbody class="modal-authorization-table-body" style="width: 100%;">
                          </tbody>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-primary btn-volver" data-target="#rolesModal{{$usuario->id}}" data-toggle="modal" data-dismiss="modal">Volver</button>
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
	</div>
</div>

@endsection
@section('footer_scripts')
<script type="text/javascript">
  function confirmarCambios(tipo){
    return confirm("¿Estás seguro de que deseas guardar los nuevos " + tipo + "?");
  };
</script>

<script>
  $(document).ready(function(){
      $('.btn-detalles').click(function(){
          var role = $(this).data('role-id');
          var userId = $(this).data('user-id');
          $.ajax({
              url: 'roles/' + role + '/detail',
              type: 'GET',
              dataType: 'json',
              success: function(response){
                  if (response) {
                      // Configuro correctamente el modal al que dirige el boton "Volver"
                      $('#detailsModal .btn-volver').attr('data-target', '#rolesModal' + userId);
                      $('#detailsModal .modal-title').html('Detalles del Rol ' + response.rol.name);
                      if(response.rol.name === 'Super Admin') {
                        console.log("Super Admin");
                        // Muestro unicamente el mensaje para Super Admin
                        $('#detailsModal .authorization-label').hide();
                        $('#detailsModal .modal-authorization-table-body').html('Este rol tiene todos los permisos.');
                      } else {
                        // Vacío el contenido de la tabla autorizaciones antes de mostrar las nuevas
                        $('#detailsModal .modal-authorization-table-body').empty();
                        // Vacío el contenido del label de la tabla autorizaciones antes de mostrar el nuevo
                        $('#detailsModal .authorization-label').empty();
                        // Muestro nuevamente el label de la tabla autorizaciones
                        $('#detailsModal .authorization-label').show();
                        // Actualizo el label
                        if(response.rol.guard_name === 'web'){
                          $('#detailsModal .authorization-label').append('<label class="badge badge-pill badge-warning" for="authorization-table">Permisos</label>');
                        } else if(response.rol.guard_name === 'filters'){
                          $('#detailsModal .authorization-label').append('<label class="badge badge-pill badge-info" for="authorization-table">Filtros</label>');
                        }
                        console.log("No Super Admin");
                        console.log("Autorizaciones: " + response.autorizaciones);
                        if(response.autorizaciones.length > 0){
                          $.each(response.autorizaciones, function(index, autorizacion) {
                            $('#detailsModal .modal-authorization-table-body').append('<tr><td class="col align-self-center">'+autorizacion+'</td></tr>');
                          });
                        } else {
                          console.log(response.autorizaciones_usuario);
                          if(response.rol.guard_name === 'web'){
                            if(response.autorizaciones_usuario.includes('Administrar Roles')) {
                              $('#detailsModal .modal-authorization-table-body').append('<tr class=detail_info_row><td class="col align-self-center">Los permisos pertenecientes a este rol no se encuentran cargados en el sistema.</td></tr>');
                              $('#detailsModal .detail_info_row').append('<td><a href="{{route('admin.listarRoles')}}"">Administrar Roles</a></td>');
                            } else {
                              $('#detailsModal .modal-authorization-table-body').append('<tr><td class="col align-self-center">Los permisos pertenecientes a este rol no se encuentran cargados en el sistema. Comunicarse con un administrador.</td></tr>');
                            }
                          } else if(response.rol.guard_name === 'filters'){
                            if(response.autorizaciones_usuario.includes('Administrar Roles') || response.autorizaciones_usuario.includes('Administrar Filtros')) {
                              $('#detailsModal .modal-authorization-table-body').append('<tr class=detail_info_row><td class="col align-self-center">Los filtros pertenecientes a este rol no se encuentran cargados en el sistema.</td></tr>');
                              if(response.autorizaciones_usuario.includes('Administrar Roles')) {
                                $('#detailsModal .detail_info_row').append('<td><a href="{{route('admin.listarRoles')}}"">Administrar Roles</a></td>');
                              }
                              if(response.autorizaciones_usuario.includes('Administrar Filtros')) {
                                $('#detailsModal .detail_info_row').append('<td><a href="{{route('admin.listarFiltros')}}"">Administrar Filtros</a></td>');
                              }
                            } else {
                              $('#detailsModal .modal-authorization-table-body').append('<tr><td class="col align-self-center">Los filtros pertenecientes a este rol no se encuentran cargados en el sistema. Comunicarse con un administrador.</td></tr>');
                            }
                          }
                        };
                      }
                  } else {
                      console.log('El rol no pudo ser encontrado.');
                  }
              },
              error: function(xhr, status, error) {
                  console.error('Error al obtener detalles del rol:', error);
              }
          });
      });
      // Limpio el contenido del modal cuando se cierra
      $('#detailsModal').on('hidden.bs.modal', function () {
          $('#detailsModal .modal-permissions-table-body').empty();
          $('#detailsModal .modal-filters-table-body').empty();
      });
  });
</script>


<!-- datatables -->
<script>
  $('#tabla-usuarios').DataTable({
    language: {
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
      },
      "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      },
      "buttons": {
          "copy": "Copiar",
          "colvis": "Visibilidad"
      }
    },
    columnDefs: [
      { "orderable": false, "targets": [2,3,4] }
    ]
  });
</script>
@endsection
