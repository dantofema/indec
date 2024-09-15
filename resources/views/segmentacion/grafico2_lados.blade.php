@extends('layouts.app')
@section('content')
<div class="text-center">
<h2 class="text-center">Segmentación a lados completos</h2>
<h3 class="text-center">Aglomerado ({{ $aglomerado->codigo }}) {{ $aglomerado->nombre }}</h3>
<div id ="resumen"></div>
<canvas id="canvas" style="padding: 20px 50px 20px 50px; max-height: 600px; " height="280" width="600"></canvas>
</div>
@endsection
@section('footer_scripts')
       <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js" charset="utf-8"></script>
        <script>
        var url = "{{url('ver-segmentacion-lados-grafico-resumen')}}/{{$aglomerado->id}}";
        var SegmentosCantidad = new Array();
        var Labels = new Array();
        var Viviendas = new Array();
        $(document).ready(function(){
          $.post(url, {"_token": "{{ csrf_token() }}"},function(response){
            var sum = 0;
            var n_segs= 0; 
            response.forEach(function(data){
                SegmentosCantidad.push(data.cant_segmentos);
                Viviendas.push(data.vivs);
                sum += Number(data.vivs)*Number(data.cant_segmentos);
                n_segs += Number(data.cant_segmentos);
            });
            var mensaje = n_segs+' segmentos para '+sum+' viviendas, con un promedio de '+sum/n_segs+' viviendas x segmento';
            document.getElementById("resumen").innerHTML=mensaje;
            
            var ctx = document.getElementById("canvas").getContext('2d');
                var myChart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                      labels: Viviendas,
                      datasets: [{
                          label: 'Número de Segmentos',
                          data: SegmentosCantidad,
                          borderWidth: 1,
                          backgroundColor: 'rgb(36, 125, 173)',
                          borderColor: 'rgb(66, 155, 213)'
                      }]
                  },
                  options: {
                      tooltips: {
                        callbacks: {
                            title: function(tooltipItem, data) { return 'Cantidad de Viviendas: '+tooltipItem[0].xLabel }
                        }
                       },
                      responsive: true,
                      scales: {
                          yAxes: [
                              {
                              gridLines: {
                                  drawBorder: true,
                                  color: ['pink', 'red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'purple']
                              },
                              ticks: {
                                  beginAtZero:true
                              }
                          }]
                      }
                  }
              });
          });
        });
        </script>
@endsection
