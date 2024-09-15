@extends('layouts.app')
@section ('content_main') 
   <!-- Modal -->
   <div class="modal fade" id="agloModal" role="dialog">
    <div class="modal-dialog">
 
     <!-- Modal content-->
     <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Info de Aglomerado</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal-body-aglo">
 
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
     </div>
    </div>
   </div>

 <div class="container">
   <!-- Modal -->
   <div class="modal fade" id="segmentaAgloModal" role="dialog">
    <div class="modal-dialog">
 
     <!-- Modal content-->
     <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Segmentar</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal-body-segmenta">
 
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
     </div>
    </div>
   </div>

   <h2>Listado de Aglomerados</h2>
   <br>
   <div class="row">
    <div class="form-group col-md-6">
    <h5>Código<span class="text-danger"></span></h5>
     <div class="controls">
	<input type="numeric" name="codigo" id="codigo" class="form-control " placeholder="Por favor introduzca un código"> 
        <div class="help-block"></div>
     </div>
    </div>
    <div class="text-left" style="margin-left: 15px;">
     <button type="text" id="btnFiterSubmitSearch" class="btn btn-info">Buscar</button>
    </div>
   </div>
   <div class="row">
   <div class="col-sm-12">
    <table class="table table-striped table-bordered dataTable table-hover order-column " id="laravel_datatable_aglos">
       <thead>
          <tr>
             <th>Id</th>
             <th>Código</th>
             <th>Nombre</th>
             <th>Número de localidades</th>
             <th>Geografía</th>
             <th>Listado</th>
             <th>Acciones</th>
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
      var table =  $('#laravel_datatable_aglos').DataTable({
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
          url: "{{ url('aglos-list') }}",
          type: 'POST',
          data: function (d) {
           d.codigo = $('#codigo').val();
          }
         },
         columns: [
                  { searchable: false, visible: false, data: 'id', name: 'id' },
                  { data: 'codigo', name: 'codigo' },
                  { data: 'nombre', name: 'nombre' },
                  { searchable: false, data: 'localidades_count', name: 'localidades_count' },
                  { orderable: false, searchable: false , data: function ( row, type, val, meta ) {if (row.carto==1) { return '<img width=15 height=15 src=images/ok.png alt=OK>'}else{return '<img width=15 height=15 src=images/no.png alt=NO>'}}, name: 'carto' },
                  { orderable: false, searchable: false , data: function ( row, type, val, meta ) {if (row.listado==1) { return '<img width=15 height=15 src=images/ok.png alt=OK>'}else{return '<img width=15 height=15 src=images/no.png alt=NO>'}}, name: 'listado' },
                  { orderable: false, searchable: false , data: function ( row, type, val, meta ) {
                                var botones='';
                                if ((row.carto==1) && (row.listado == 1)) {
                                    botones =  '<button type="button" class="segmentar btn-sm btn-primary" value="Segmentar"/>Segmentar</button>';
                                    botones = botones+ '<button type="button" disabled= class="muestrear btn-sm btn-primary" value="Muestrear"/>Muestrear</button> ';
                                }else{
                                       if ((row.carto!=1) || (row.listado!=1)) {
                                         botones = botones+ '<button type="button" class="cargar btn-sm btn-primary" value="Cargar"/>Cargar info</button>';
                                        }
                                }
                                if ((row.segmentadolistado==1)) {
                                       botones = botones+ '<input type="button" class="ver_segmenta_listado  btn-sm btn-primary" value="Ver Segmentación"/> ';
                                       botones = botones+ '<input type="button" class="ver_segmenta_listado_grafico  btn-sm btn-primary" value="Ver Gráfico de Segmentación"/> ';
                                }
                                return botones;
                            }
                  },
               ],
      });

// Función de botón segmentar.
    table.on('click', '.segmentar', function () {
      var row = $(this).closest('tr');
      var data = table.row( row ).data();
      console.log('Segmentar: '+data.codigo);
        if (typeof data !== 'undefined') {
        // AJAX request
           $.ajax({
            url: "{{ url('aglo-segmenta') }}"+"/"+data.id,
            type: 'post',
            data: {id: data.id,format: 'html'},
            success: function(response){ 
              // Add response in Modal body
              $('#modal-body-segmenta').html(response);
              // Display Modal
              $('#segmentaAgloModal').modal('show'); 
            }
           });
        }
    });
  
// Función de botón ver segmentación x listado.
    table.on('click', '.ver_segmenta_listado', function () {
      var row = $(this).closest('tr');
      var data = table.row( row ).data();
      console.log('Ver Segmentación a listado: '+data.codigo);
        if (typeof data !== 'undefined') {
            url= "{{ url('ver-segmentacion') }}"+"/"+data.id;
            $(location).attr('href',url);
           };
    });
  
// Función de botón ver segmentación x lados.
    table.on('click', '.ver_segmenta_lados', function () {
      var row = $(this).closest('tr');
      var data = table.row( row ).data();
      console.log('Ver Segmentación x lados: '+data.codigo);
        if (typeof data !== 'undefined') {
            url= "{{ url('ver-segmentacion-lados') }}"+"/"+data.id;
            $(location).attr('href',url);
           };
    });
  
// Función de botón ver grafico d esegmentación x listado.
    table.on('click', '.ver_segmenta_listado_grafico', function () {
      var row = $(this).closest('tr');
      var data = table.row( row ).data();
        if (typeof data !== 'undefined') {
            url= "{{ url('ver-segmentacion/grafico-resumen') }}"+"/"+data.id;
            $(location).attr('href',url);
           };
    });
  
// Función de botón ver gráfico de segmentación x lados.
    table.on('click', '.ver_segmenta_lados_grafico', function () {
      var row = $(this).closest('tr');
      var data = table.row( row ).data();
        if (typeof data !== 'undefined') {
            url= "{{ url('ver-segmentacion-lados/grafico-resumen') }}"+"/"+data.id;
            $(location).attr('href',url);
           };
    });
  
    table.on('click', '.muestrear', function () {
      var row = $(this).closest('tr');
      var data = table.row( row ).data().codigo;
      console.log('Muestrear: '+data);
    });
  
    table.on('click', '.cargar', function () {
      var row = $(this).closest('tr');
      var data = table.row( row ).data();
      console.log('Cargar: '+data.codigo);
        if (typeof data !== 'undefined') {
            url= "{{ url('segmentador') }}" //+"/"+data.id;
            $(location).attr('href',url);
           };
    });

    table.on( 'click', 'tr', function (e) {
      if ((e.target.value != 'Segmentar') && (e.target.value != 'Muestrear')
         && (e.target.value != 'Ver Segmentación Listado') && (e.target.value != 'Ver Segmentación x lados')
         && (e.target.value != 'Ver Gráfico de Segmentación Listado') && (e.target.value != 'Ver Gráfico de Segmentación x lados')
         && (e.target.value != 'Cargar') && (e.target.value != 'CargarC1')
    ){

        var data = table.row( this ).data();
        if (typeof data !== 'undefined') {
        // AJAX request
           $.ajax({
            url: "{{ url('aglo') }}"+"/"+data.id,
            type: 'post',
            data: {id: data.id,format: 'html'},
            success: function(response){ 
              // Add response in Modal body
              $('#modal-body-aglo').html(response);

              // Display Modal
              $('#agloModal').modal('show'); 
            }
           });
           console.log( 'You clicked on '+data.id+'\'s row' );
        }else{
           console.log( 'You clicked on NO DATA\'s row' );
        }
      }
   });



  $('#btnFiterSubmitSearch').click(function(){
  $('#laravel_datatable_aglos').DataTable().draw(true);
  });

} );

// Funcin para cargar todos los radios en lugar del selector de radios.
function changeAllRadios(_this, id) {
    var status = $(_this).prop('checked') == true ? 1 : 0;
    
}
</script>
 @endsection
<?php // </body> </html> ?>
