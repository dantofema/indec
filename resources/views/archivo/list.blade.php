@extends('layouts.app')

@section ('content_main')
  <style>
    .badge-checksum {
    color: black;
    background-color: orange;
    }
    .badge-checksum:hover {
    color: black;
    }
    .grid-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    text-align: center;
    justify-content: center;
    } 
    .grid-item {
    padding: 10px;
    text-align: center;
    }
  </style>
  <!-- Modal -->
  <div class="modal fade" id="empModal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Info de Archivo</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">

    </div>
    <div class="modal-footer">
      <button type="button" class="btn-sm btn-primary float-right btn-detalles" data-dismiss="modal">Cerrar</button>
    </div>
    </div>
  </div>
  </div>

  <!-- Modal checksum -->
  <div class="modal fade" id="checksumModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">
        <!-- acá se carga el título -->
      </h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      <!-- acá se carga el mensaje -->
      <h5 id="checksum-message"></h5>
      <br>
      <div style="text-align: center; font-size: 16px">Esto puede implicar:</div> 
      <div class="grid-container"> 
        <div class="grid-item"><i class="bi bi-file-earmark-excel"></i><br><span class="badge badge-pill badge-danger" style="font-size: 13px">Información erronea</span></div>
        <div class="grid-item"><i class="bi bi-copy"></i><br><span class="badge badge-pill badge-warning" style="font-size: 13px">Contenido duplicado</span></div>
      </div>
    </div>
    <div class="modal-footer">
      @can('Administrar Archivos', 'Ver Archivos')
      <!-- al botón se le carga la ruta correspondiente en el script -->
      <button id="checksum-button" type="button" class="btn-sm btn-success" onclick="recalcularChecksum(this)"></button>
      @endcan
      <button type="button" class="btn-sm btn-primary float-right btn-detalles" data-dismiss="modal">Cerrar</button>
    </div>
    </div>
  </div>
  </div>

  <div class="container">
    @if(Session::has('message'))
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{Session::get('message')}}
      </div>
    @endif
    <h2>Listado de Archivos</h2>
    @can('Administrar Archivos', 'Ver Archivos')
    <div id="botones-problemas">
    <!-- Acá se cargan los botones para archivos repetidos y checksums obsoletos -->
    </div>
    @endcan
    <br>

    <div class="col-lg-12">
    <table class="table table-striped table-bordered dataTable table-hover order-column" id="laravel_datatable">
        <thead>
          <tr>
              <th>Id</th>
              <th>Nombre</th>
              <th>Id-Nombre</th>
              <th>Usuario</th>
              <th>Tipo</th>
              <th>Mime</th>
              <th>Checksum</th>
              <th>Tamaño</th>
              <th>Creación</th>
              <th>Cargador</th>
              <th alt="Observadores" >(o)</th>
              <th>Estado</th>
              <th> * </th>
          </tr>
        </thead>
    </table>
    </div>
    </div>
  </div>
@endsection
@section('footer_scripts')
 <script>
 $(document).ready( function () {
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      var table =  $('#laravel_datatable').DataTable({
        "pageLength": 10,
         language: //{url:'https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json'},
{
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
         processing: true,
         serverSide: true,
         ajax: {
          url: "{{ url('archivos') }}",
          type: 'GET',
          data: function (d) {
          d.codigo = $('#nombre').val();
          }
         },
         columns: [
                  { visible: false, data: 'id', name: 'id' },
                  { data: 'nombre_original', name: 'nombre_original' },
                  { visible: false, data: 'nombre', name: 'nombre' },
                  { visible: false, data: 'user_id', name: 'user_id' },
                  { data: 'tipo', name: 'tipo' },
                  { visible: false, data: 'mime', name: 'mime' },
                  { visible: false, data: 'checksum', name: 'checksum' },
                  { data: 'size_h', name: 'size'},
                  { data: 'created_at_h', name: 'created_at'},
                  { data: 'usuario', name: 'usuario' },
                  { data: 'viewers_count', name: 'viewers_count' },
                  { data: 'status', name: 'status' },
                  { data: 'action', name: 'action', orderable: false}
        ]
      });

  // funcion abrir info archivo al clickear fila
   table.on( 'click', 'tr', function (e) {
    if ($(e.target).closest('button').length === 0) {
      var data = table.row( this ).data();
      // AJAX request
        $.ajax({
          url: "{{ url('archivo') }}"+"\\"+data.id,
          type: 'post',
          data: {id: data.id,format: 'html'},
          success: function(response){ 
            // Add response in Modal body
            $('#empModal .modal-body').html(response);

            // Display Modal
            $('#empModal').modal('show'); 
          }
        });
        console.log( 'You clicked on '+data.id+'\'s row' );
    }
   });

   // funcion abrir modal de checksum
   $('#checksumModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // botón que activó el modal
        var file_id = button.data('file'); 
        var nombre_original = button.data('name'); 
        var status = button.data('status'); 

        $(this).find('.modal-title').text('Info sobre checksum (' + nombre_original + ')');

        var modalBody = $(this).find('.modal-body');
        var modalfooter = $(this).find('.modal-footer');
        if (status === 'no_check') {
          // actualizo mensajes
          modalBody.find('#checksum-message').text('No se realizó la verificación del checksum de este archivo.');
          modalfooter.find('#checksum-button').text('Validar checksum')
        } else if (status === 'old_check') {
            // actualizo mensajes
            modalBody.find('#checksum-message').text('El checksum de este archivo está obsoleto.');
            modalfooter.find('#checksum-button').text('Recalcular checksum')
        }
        // actualizo ruta
        modalfooter.find('#checksum-button').attr('href', "{{route('recalcular_checksums', ':archivo_id') }}".replace(':archivo_id', file_id));
    });

    $('#checksum-button').on('click', function(event) {
        event.preventDefault(); // evita que el botón diriga directamente a su href
        if (confirm('¿Estás seguro de que deseas calcular el checksum?')) {
            window.location.href = $(this).attr('href');
        }
    });

  table.on('click', '.btn_arch', function () {
    var row = $(this).closest('tr');
    var data = table.row( row ).data();
    console.log('Ver Archivo: '+data.codigo);
      if (typeof data !== 'undefined') {
          url= "{{ url('archivo') }}"+"/"+data.id;
          $(location).attr('href',url);
          };
  });

// Función de botón Procesar.
    table.on('click', '.btn_arch_procesar', function () {
      var row = $(this).closest('tr');
      var data = table.row( row ).data();
      console.log('Procesar Archivo: '+data.codigo);
        if (typeof data !== 'undefined') {
            url= "{{ url('archivo') }}"+"/"+data.id+"/procesar";
            $(location).attr('href',url);
           };
    });

// Función de botón Descarga.
    table.on('click', '.btn_descarga', function () {
      var row = $(this).closest('tr');
      var data = table.row( row ).data();
      console.log('Descargando: '+data.codigo);
        if (typeof data !== 'undefined') {
            url= "{{ url('archivo/') }}"+"/"+data.id+"/descargar";
            $(location).attr('href',url);
           };
    });
  
// Función de botón Borrar.
    table.on('click', '.btn_arch_delete', function () {
      var $ele = $(this).parent().parent();
      var row = $(this).closest('tr');
      var data = table.row( row ).data();
      if (typeof data !== 'undefined') {
      $.ajax({
         url: "{{ url('archivo') }}"+"\\"+data.id,
         type: "DELETE",
	 data: {id: data.id,
                _token:'{{ csrf_token() }}'},
         success: function(response){ 
	     // Add response in Modal body
       if(response=='ok'){
        if(response.statusCode==200){
	          row.fadeOut().remove();
        }
        if(response.statusCode==405){
              alert("Error al intentar borrar");
        }
        if(response.statusCode==500){
              alert("Error al intentar borrar. En el servidor");
          }
        alert("Se eliminó el registro del archivo");
        row.fadeOut().remove();
        $('.modal-body').html(response);
       } else {
        alert("El archivo es utilizado por " + response + " usuario(s). No se eliminará");
       }
           }
      });
      };
    });

  // Función de botón Dejar de ver.
  table.on('click', '.btn_arch_detach', function () {
      var $ele = $(this).parent().parent();
      var row = $(this).closest('tr');
      var data = table.row( row ).data();
      if (typeof data !== 'undefined') {
      $.ajax({
         url: "{{ url('archivo') }}"+"\\"+data.id+"/detach",
         type: "PUT",
	 data: {id: data.id,
                _token:'{{ csrf_token() }}'},
         success: function(response){ 
	     // Add response in Modal body
	     if(response.statusCode==200){
	          row.fadeOut().remove();
	     }
	     if(response.statusCode==405){
	          alert("Error al intentar borrar");
	     }
      if(response.statusCode==500){
            alert("Error al intentar borrar. En el servidor");
        }
      alert("Ya no se visualizará el archivo");
	     row.fadeOut().remove();
	     $('.modal-body').html(response);

           }
      });
      };
    });
  
  $('#btnFiterSubmitSearch').click(function(){
     $('#laravel_datatable').DataTable().draw(true);
  });

} );

    // función mostrar botones archivos repetidos, checksums no calculados y obsoletos
    $(document).ready(function() {
        // Ejecutar después de que Datatables ha terminado de cargar los datos
        $('#laravel_datatable').on('draw.dt', function() {
            var count_archivos_repetidos = 0;
            var null_checksums = 0;
            var deprecated_checksums = 0;

            // Iterar sobre las filas de la tabla
            $('#laravel_datatable tbody tr').each(function() {
                var statusColumn = $(this).find('td:eq(6)'); // La columna 'status' es la numero 6
                console.log(statusColumn);
                var statusText = statusColumn.text();

                // Contar archivos repetidos, checksums no calculados y obsoletos
                if (statusText.includes('Copia')) {
                    count_archivos_repetidos++;
                }
                if (statusText.includes('Checksum no validado')) {
                    null_checksums++;
                }
                if (statusText.includes('Checksum obsoleto')) {
                    deprecated_checksums++;
                }
            });

            // Agregar elementos HTML al div 'botones-problemas'
            var botonesProblemas = $('#botones-problemas');
            botonesProblemas.empty(); // Limpiar contenido previo

            if (deprecated_checksums > 0) {
                var checksumsObsoletosLink = $('<h4><a href="{{ route("checksums_obsoletos") }}" class="badge badge-pill badge-danger">Ver checksums obsoletos (' + deprecated_checksums + ')</a></h4>');
                botonesProblemas.append(checksumsObsoletosLink);
            }
            if (null_checksums > 0) {
                var checksumsObsoletosLink = $('<h4><a href="{{ route("checksums_no_calculados") }}" class="badge badge-pill badge-checksum">Ver checksums no validados (' + null_checksums + ')</a></h4>');
                botonesProblemas.append(checksumsObsoletosLink);
            }
            if (count_archivos_repetidos > 0) {
                var archivosRepetidosLink = $('<h4><a href="{{ route("archivos_repetidos") }}" class="badge badge-pill badge-warning">Ver archivos repetidos (' + count_archivos_repetidos + ')</a></h4>');
                botonesProblemas.append(archivosRepetidosLink);
            }
        });
    });


</script>
@endsection
