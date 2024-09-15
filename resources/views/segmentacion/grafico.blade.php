@extends('layouts.app')
@section('content')
<div class="text-center">
<h2 class="text-center">Segmentación </h2>
@if (isset($aglomerado))
<h3 class="text-center">Aglomerado ({{ $aglomerado->codigo }}) {{ $aglomerado->nombre }}</h3>
@endif
@if (isset($localidad))
<h4 class="text-center">Localidad ({{ $localidad->codigo }}) {{ $localidad->nombre }}</h4>
@endif
<div id ="resumen"></div>
<canvas id="canvas" style="padding: 20px 50px 20px 50px; max-height: 500px; " height="280" width="600"></canvas>
</div>
@endsection
@section('footer_scripts')
       <!--script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js" charset="utf-8"></script-->
        <script>
        var myChart = '';
        var SegmentosCantidad = new Array();
      @if (isset($aglomerado))
        var url = "{{url('ver-segmentacion-grafico-resumen')}}/{{$aglomerado->id}}";
      @endif
      @if (isset($localidad))
        var url = "{{url('localidad')}}/{{$localidad->id}}/grafico";
      @endif
        var Segmentos = new Array();
        var Labels = new Array();
        var Viviendas = new Array();
        var Detalle = new Array();
        $(document).ready(function(){
          $.post(url, {"_token": "{{ csrf_token() }}"},function(response){
            var sum = 0;
            var n_segs= 0;
            response.forEach(function(data){
                SegmentosCantidad.push(data.cant_segmentos);
                Viviendas.push(data.vivs);
                sum += Number(data.vivs)*Number(data.cant_segmentos);
                n_segs += Number(data.cant_segmentos);
                Detalle.push(data.en_lados);
            });
            var mensaje = n_segs+' segmentos para '+sum+' viviendas, con un promedio de '+Math.round (100*sum/n_segs)/100+' viviendas x segmento';
            document.getElementById("resumen").innerHTML=mensaje;
            var ctx = document.getElementById("canvas").getContext('2d');
                var myChart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                      labels: Viviendas,
                      datasets: [{
                          label: 'Cantidad de Segmentos',
                          data: SegmentosCantidad,
                          borderWidth: 2,
                          backgroundColor: 'rgb(36, 125, 173)',
                          borderColor: 'rgb(66, 155, 213)'
                      }]
                  },
                  options: {
                      responsive: true,
                      borderRadius: 10,
                      scales: {
                          y: {
                              title: 'Cantidad de segmentos',
                              grid: {
                                  drawBorder: true,
                                  color:
                                  function (context) {
                                   const colores = [
                                      'rgb(255, 99, 132)',
                                      'rgb(255, 159, 64)',
                                      'rgb(255, 205, 86)',
                                      'rgb(75, 192, 192)',
                                      'rgb(54, 162, 235)',
                                      'rgb(153, 102, 255)',
                                      'rgb(231,233,237)'
                                    ];
                                   return colores[context.tick.value % 7];
                                  }
                              },
                              ticks: {
                                  // valores enteros
                                  precision: 0, 
                                  beginAtZero:true
                              },
                              title: {
                                display: true,
                                text: 'Segmentos'
                              }
                          },
                          x: {
                              title: {
                                display: true,
                                text: 'Viviendas'
                              }
                          }
                      }
                  }
              });
          });
        });
        </script>
@endsection
