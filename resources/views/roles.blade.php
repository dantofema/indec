@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="container">
  <div style="display: flex; align-items: center; justify-content: center;">
    <div style="width: 50rem; display: flex; align-items: center;"> 
      <h4><a href="{{route('admin.listarUsuarios')}}" class="badge badge-pill badge-primary">← Volver</a></h4>
    </div>
  </div>
	<div class="row justify-content-center">
    <div class="card" style="width: 50rem;">
      <div class="card-header">{{ __('Lista de roles') }} 
        @can('Crear Roles')
          <button type="button" class="badge badge-pill badge-success float-right" data-toggle="modal" id="btn-trigger-modal-nuevo-rol" data-target="#newRoleModal">+ Nuevo rol</button></div>
        @endcan
      <div class="card-body">
        <div id="alert-container">
          @if(Session::has('info'))
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              {{Session::get('info')}}
            </div>
          @endif
        </div>
        @if(Session::has('error_rename') or Session::has('error_authorization_edit'))
        <script>
            $(function() {
                $('#editRoleModal{{Session::get('id_error')}}').modal('show');
            });
          </script>
        @elseif(Session::has('error_create') or Session::has('error_authorizations_new'))
          <script>
            $(function() {
                $('#newRoleModal').modal('show');
            });
          </script>
        @endif
        <table class="table table-bordered" id="tabla-roles">
          @if($roles[0] !== null)
          <thead>
            <tr>
              <th>Rol</th>
              @canany(['Editar Roles', 'Eliminar Roles'])
              <th>*</th>
              @endcan
            </tr>
          </thead>
          <tbody>
            @foreach ($roles as $rol)
            <tr>
              <td>{{$rol->name}} 
                @if ($rol->guard_name == "web")
                  <span class="badge badge-pill badge-warning">Permisos</span>
                @else
                  <span class="badge badge-pill badge-info">Filtros</span>
                @endif
                @if ($rol->permissions->isEmpty() and $rol->name != 'Super Admin') 
                  <span class="badge badge-pill badge-danger">Vacío</span>
                @endif 
              </td>
              @canany(['Editar Roles', 'Eliminar Roles'])
              <td>
                <div class="text-center">
                  @if ($rol->name != 'Super Admin')
                    @can('Editar Roles')
                    <!-- Button trigger modal -->
                    <button type="button" class="btn-sm btn-primary text-center" data-toggle="modal" id="btn-trigger-modal-edit-role" data-target="#editRoleModal{{$rol->id}}">
                      Editar
                    </button>
                    @endcan
                    @can('Eliminar Roles')
                    <!-- Button eliminar rol -->
                    <button type="button" class="btn-sm btn-danger btn-role-delete" data-role="{{ $rol }}">Eliminar</button>
                    @endcan
                  @else
                    <i class="bi bi-ban"> No se puede editar este rol.</i>
                  @endif
                </div>

                <!-- Modal editar rol -->
                <div class="modal fade" id="editRoleModal{{$rol->id}}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar rol: {{$rol->name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      @if ($rol->name == 'Super Admin')
                        <div class="modal-body">
                          No puede editarse este rol 
                          <span class="badge badge-pill badge-danger">Super Admin</span>
                        </div>
                      @else
                        <form action="{{route('admin.editarRol', $rol->id)}}" method="put" id="form-edit-rol{{$rol->id}}">
                          <div class="modal-body">
                            <label for="renameInput">Nuevo nombre de rol</label>
                            <input type="text" class="form-control" id="renameInput" name="newName" aria-describedby="editarRol">
                            @if(Session::has('error_rename'))
                              <p style="color:red">{{Session::get('error_rename')}}</p>
                            @endif
                            <br>
                            <!-- Switch de cambio de tipo de rol -->
                            <label for="switch-role-type-edit">Tipo de Rol  </label>
                            <!-- Debe ser clase para que el script itere por cada uno de ellos -->
                            <input type="checkbox" data-toggle="toggle" class="switch-role-type-edit" data-on="Filtros" data-rol-id="{{$rol->id}}" data-off="Permisos" data-onstyle="info" data-offstyle="warning" data-size="xs" data-width="20%" data-toggle="toggle" data-style="ios">
                            
                            @if(Session::has('error_authorization_edit'))
                              <p style="color:red">{{Session::get('error_authorization_edit')}}</p>
                            @endif
                            <!-- Tabla de permisos -->
                            <table class="table" id="tabla-permisos-edit-{{$rol->id}}">
                              <tbody>
                                @if ($permisos->count() > 0)
                                  @php 
                                    $role_permissions = $rol->permissions->where('guard_name', 'web');
                                  @endphp
                                  @foreach ($permisos as $permiso)
                                  <tr>                                         
                                    <td class="col align-self-center">
                                      @if ($role_permissions->contains($permiso))
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" checked id="{{$permiso->name}}" name="permisos[]" value="{{$permiso->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      @else
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" id="{{$permiso->name}}" name="permisos[]" value="{{$permiso->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      @endif
                                        <label class="form-check-label" for="{{$permiso->name}}">
                                          {{$permiso->name}}
                                        </label>
                                      </td>
                                  </tr> 
                                  @endforeach
                                @else
                                  <tr>
                                    <td class="col align-self-center">
                                      No hay permisos cargados.
                                    </td>
                                  </tr>
                                @endif
                              </tbody>  
                            </table>
                  
                            <!-- Tabla de filtros (oculta por defecto)-->
                            <table class="table" id="tabla-filtros-edit-{{$rol->id}}" style="display:none;">
                              <tbody>
                                @if ($filtros->count() > 0)
                                  @php 
                                    $role_filters = $rol->permissions->where('guard_name', 'filters');
                                  @endphp
                                  @foreach ($filtros as $filtro)
                                  <tr>                                         
                                    <td class="col align-self-center">
                                      @if ($role_filters->contains($filtro))
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" checked id="{{$filtro->name}}" name="filtros[]" value="{{$filtro->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      @else
                                        <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" id="{{$filtro->name}}" name="filtros[]" value="{{$filtro->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      @endif
                                        <label class="form-check-label" for="{{$filtro->name}}">
                                          {{$filtro->name}}
                                        </label>
                                      </td>
                                  </tr> 
                                  @endforeach
                                @else
                                  <tr>
                                    <td class="col align-self-center">
                                      No hay filtros cargados.
                                    </td>
                                  </tr>
                                @endif
                              </tbody>
                            </table>
                          </div>
                          <!-- Tipo de rol a enviar (valor con el que se inicializa, luego esta variable se cambia en el script según corresponda) -->
                          @if ($rol->guard_name = 'web')
                            <input type="hidden" id="role-type-{{$rol->id}}" name="role_type" value="permisos" />
                          @else
                            <input type="hidden" id="role-type-{{$rol->id}}" name="role_type" value="filtros" />
                          @endif
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <input type="submit" name="btn"  class="btn btn-primary btn-submit-edit-autorizaciones" value="Guardar Cambios" onclick="return confirmarCambios()">
                          </div>
                        </form>
                      @endif
                    </div>
                  </div>
                </div>

                <!-- Modal crear rol -->
                <div class="modal fade" id="newRoleModal" tabindex="-1" role="dialog" aria-labelledby="newRoleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="newRoleModalLabel">Nuevo rol:</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{route('admin.crearRol')}}" method="put" id="form-create-rol">
                        <div class="modal-body">
                          <label for="nameInput">Nombre del nuevo rol</label>
                          <input type="text" class="form-control" id="nameInput" name="newRoleName" aria-describedby="crearRol">
                          @if(Session::has('error_create'))
                            <p style="color:red">{{Session::get('error_create')}}</p>
                          @endif
                          <br>
                          <!-- Switch de cambio de tipo de rol -->
                          <label for="switch-role-type-create">Tipo de Rol  </label>
                          <input type="checkbox" data-toggle="toggle" id="switch-role-type-create" data-on="Filtros" data-off="Permisos" data-onstyle="info" data-offstyle="warning" data-size="xs" data-width="20%" data-toggle="toggle" data-style="ios">

                          @if(Session::has('error_authorizations_new'))
                            <p style="color:red">{{Session::get('error_authorizations_new')}}</p>
                          @endif
                          <!-- Tabla de permisos -->
                          <table class="table" id="tabla-permisos-create">
                            <tbody>
                              @if ($permisos->count() > 0)
                                @foreach ($permisos as $permiso)
                                <tr>                                         
                                  <td class="col align-self-center">
                                      <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" id="{{$permiso->name}}" name="permisos[]" value="{{$permiso->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      <label class="form-check-label" for="{{$permiso->name}}">
                                        {{$permiso->name}}
                                      </label>
                                    </td>
                                </tr> 
                                @endforeach
                              @else
                                <tr>
                                  <td class="col align-self-center">
                                    No hay permisos cargados.
                                  </td>
                                </tr>
                              @endif
                            </tbody>
                          </table>
                            
                          <!-- Tabla de filtros (oculta por defecto)-->
                          <table class="table" id="tabla-filtros-create" style="display:none;">
                            <tbody>
                              @if ($filtros->count() > 0)
                                @foreach ($filtros as $filtro)
                                <tr>                                         
                                  <td class="col align-self-center">
                                      <input type="checkbox" data-toggle="toggle" class="toggle-checkbox" id="{{$filtro->name}}" name="filtros[]" value="{{$filtro->id}}" data-on=" " data-off=" " data-offstyle="secondary" data-width="10" data-toggle="toggle" data-size="xs" data-style="ios">
                                      <label class="form-check-label" for="{{$filtro->name}}">
                                        {{$filtro->name}}
                                      </label>
                                    </td>
                                </tr> 
                                @endforeach
                              @else
                                <tr>
                                  <td class="col align-self-center">
                                    No hay filtros cargados.
                                  </td>
                                </tr>
                              @endif
                            </tbody>
                          </table>
                          <!-- Tipo de rol a enviar (por defecto, luego se cambia en el script según corresponda) -->
                          <input type="hidden" id="role-type-create" name="role_type" value="permisos" />
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <input type="submit" name="btn"  class="btn btn-primary btn-submit-edit-autorizaciones" value="Confirmar" onclick="return confirmarCreacion()">
                          </div>
                        </div>
                        </form>
                    </div>
                  </div>
                </div>
              </td>
              @endcan
          @endforeach
        </tr>
      </tbody>
      @else
      <h1>No hay roles cargados</h1>
      @endif
    </table>
	</div>
</div>

@endsection
@section('footer_scripts')
<script type="text/javascript">
  function confirmarCambios(){
    return confirm("¿Estás seguro de que deseas modificar el rol?");
  };
  function confirmarCreacion(){
    return confirm("¿Estás seguro de que deseas crear el nuevo rol \"" + document.getElementById('nameInput').value +"\" ?");
  };
</script>

<script>
    $(document).ready(function() {
        $('#switch-role-type-create').prop('checked', false).change();
        $('#switch-role-type-create').change(function() {
            if ($(this).prop('checked')) {
                // Mostrar la tabla de filtros y ocultar la tabla de permisos
                console.log('#tabla-filtros-create');
                $('#tabla-filtros-create').show();
                $('#tabla-permisos-create').hide();

                $('#role-type-create').val('filtros');
            } else {
                // Mostrar la tabla de permisos y ocultar la tabla de filtros
                $('#tabla-permisos-create').show();
                $('#tabla-filtros-create').hide();

                $('#role-type-create').val('permisos');
            }
        });
    });
</script>

<script>
  $(document).ready(function() {
    $('.switch-role-type-edit').change(function() {
        // Obtener el ID del rol correspondiente al switch
        var rolId = $(this).data('rol-id');
        console.log("ID del Rol: " + rolId);

        // Obtener las tablas correspondientes al rol actual
        var tablaPermisos = $('#tabla-permisos-edit-' + rolId);
        var tablaFiltros = $('#tabla-filtros-edit-' + rolId);

        if ($(this).prop('checked')) {
            // Mostrar la tabla de filtros y ocultar la tabla de permisos
            console.log("Mostrar filtros");
            tablaFiltros.show();
            tablaPermisos.hide();

            $('#role-type-' + rolId).val('filtros');
        } else {
            // Mostrar la tabla de permisos y ocultar la tabla de filtros
            console.log("Mostrar Permisos");
            tablaPermisos.show();
            tablaFiltros.hide();

            $('#role-type-' + rolId).val('permisos');
        }
    });

    // Desmarcar todos los switches por defecto
    $('.switch-role-type-edit').prop('checked', false).change();
  });
</script>

<script>
  $(document).ready(function(){
    $('.btn-role-delete').click(function(){
      var role = $(this).data('role');
      var row = $(this).closest('tr');
      if ((confirm('Está seguro de que desea eliminar el rol “' + role.name + '"?'))) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
          url: 'roles/' + role.id,
          type: "DELETE",
          data: {
            '_token': csrfToken
          },
          success: function(response) {
              var alertClass = (response.statusCode == 200) ? 'alert-success' : 'alert-danger';
              var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible" role="alert">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                response.message +
                              '</div>';
              $('#alert-container').html(alertHtml);
              if (response.statusCode == 200) {
                  row.fadeOut().remove();
              }
              console.log(response);
          }
        });
      };
    });
  });
</script>

<!-- datatables -->
<script>
  $('#tabla-roles').DataTable({
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
      { "orderable": false, "targets": [-1] }
    ]
  });
</script>
@endsection
