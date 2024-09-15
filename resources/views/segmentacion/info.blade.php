@extends('layouts.app')
@section ('content_main')
   <!-- Modal -->
   <div class="modal fade" id="empModal" role="dialog">
    <div class="modal-dialog">
 
     <!-- Modal content-->
     <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Info de Segmento</h4>
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
   <h3 class="text-center">
@if (isset($aglomerado))
  ({{ $aglomerado->codigo }} )
  {{ $aglomerado->nombre }} 
@endif
</h3>
   <h4 class="text-center">Listado de Segmentos</h4>
   <br>
   <div class="row">
    <div class="form-group col-md-6">
    <div class="text-left" style="
    margin-left: 15px;
    ">
    </div>
    </div>
    <br>
    <table class="table table-bordered  table-striped hover order-column" id="laravel_datatable">
       <thead>
          <tr>
             <th>Id</th>
             <th>Fracción</th>
             <th>Radio</th>
             <th>Segmento</th>
             <th>Descripción</th>
             <th>Vivs</th>
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
//         processing: true,
//         serverSide: true,
//         ajax: {
//          url: "{{ url('provs-list') }}",
//          type: 'GET',
//          data: function (d) {
//          d.codigo = $('#codigo').val();
//          }
         data: {!! $segmentacion !!}
         ,
         columns: [
                  { visible: false, data: 'segmento_id', name: 'id' },
                  { data: 'frac', name: 'frac' },
                  { data: 'radio', name: 'radio' },
                  { data: 'seg', name: 'seg' },
                  { data: 'detalle', name: 'detalle' },
                  { data: 'vivs', name: 'vivs' },
                  { visible: false, data: 'ts', name: 'text_search' },
//                  { searchable: false , data: 'departamentos_count', name: 'departamentos_count' }
               ]
      });

   table.on( 'click', 'tr', function () {
        var data = table.row( this ).data();
        console.log( 'Click en segmento id '+data.segmento_id);
        console.log( data );
        return true;
// AJAX request
   $.ajax({
    url: "{{ url('prov') }}"+"\\"+data.id,
    type: 'post',
    data: {id: data.id,format: 'html'},
    success: function(response){ 
      // Add response in Modal body
      $('.modal-body').html(response);

      // Display Modal
      $('#empModal').modal('show'); 
    }
  });
   });
  $('#btnFiterSubmitSearch').click(function(){
     $('#laravel_datatable').DataTable().draw(true);
  });

} );

</script>
@endsection
