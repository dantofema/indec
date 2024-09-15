@extends('layouts.app')
@section ('content_main') 
   <!-- Modal -->
   <div class="modal fade" id="empModal" role="dialog">
    <div class="modal-dialog">
 
     <!-- Modal content-->
     <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Info de Departamento</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
 
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
     </div>
    </div>
   </div>

 <div class="container">
   <h2>Listado de Departamentos de {{ $provincia->nombre ?? ' *todas* ' }}</h2>
    <table class="table table-bordered table-striped table-hover order-column" id="laravel_datatable">
       <thead>
          <tr>
             <th>Id</th>
             <th>Código</th>
             <th>Nombre</th>
             <th>Localidades</th>
             <th>Fracciones</th>
             <th>Radios</th>
             <th>Radios Segmentados</th>
          </tr>
       </thead>
    </table>
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
        "pageLength": -1,
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
          url: '{{ url("prov/list/{$provincia->id}") }}',
          type: 'GET',
          data: function (d) {
          d.codigo = $('#codigo').val();
          console.log(d);
          }
         },
         columns: [
                  { visible: false, data: 'id', name: 'id' },
                  { data: 'codigo', name: 'codigo' },
                  { data: 'nombre', name: 'nombre' },
		  { searchable: false , data: 'localidades_count', name: 'localidades_count' },
		  { searchable: false , data: 'fracciones_count', name: 'fracciones_count' },
		  { searchable: false , data: 'radios_count', name: 'radios_count' },
		  { searchable: false , data: 'segmentados', name: 'segmentados' }
	          ]
      });

   table.on( 'click', 'tr', function () {
        var data = table.row( this ).data();
// AJAX request
   $.ajax({
    url: "{{ url('depto') }}"+"\\"+data.id,
    type: 'post',
    data: {id: data.id,format: 'html'},
    success: function(response){ 
      // Add response in Modal body
      $('.modal-body').html(response);

      // Display Modal
      $('#empModal').modal('show'); 
    }
  });
        console.log( 'You clicked on '+data.id+'\'s row' );
   });
  $('#btnFiterSubmitSearch').click(function(){
     $('#laravel_datatable').DataTable().draw(true);
  });

} );

</script>
@endsection
