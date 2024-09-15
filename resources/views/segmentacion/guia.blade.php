@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Guía práctica para una segmentación eficiente.</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p class="text-justify">
Este instructivo es una guía para facilitar la segmentación  y evitar consumir excesivamente tiempo probando diferentes valores para los parámetros de cantidad de viviendas deseada, máxima y mínima por segmento. 
Lo más importante en este proceso es subir la mayor cantidad de localidades disponibles y controladas a <i>“Producción”</i>, y tratar de segmentar la totalidad de lo subido, dejando para resolver más adelante aquellos casos que presenten dificultades en la segmentación automática.
Dado que los valores establecidos para cada provincia son valores deseados, es aceptable que la cantidad de viviendas del segmento varíe entre un máximo y un mínimo que dependerá de lo que cada provincia establezca como aceptable.
Para agilizar la segmentación, proponemos 3 formas de cargar los parámetros que permitirán ajustar los tamaños de los segmentos de acuerdo a los valores <b>deseados</b> establecidos por La Dir. de Estadísticas Sociales y Población.</p> 
<div class="m-2 p-2">
<li>Por un lado, se debe tener en cuenta la cantidad de manzanas que tiene el radio y el parámetro Mantener manzana indivisible con menos de tantas viviendas para dividir manzanas en lados.</li>
<li>Por el otro, se van a considerar los valores de cantidad de viviendas <i>deseadas, máximas y mínimas.</i></li>
</div>
<div class="m-3 p-4 border rounded">
En el 1° caso,  si el radio tiene:
<li><b>menos de 20 manzanas</b> el valor del parámetro manzana indivisible puede quedar en  <b>5;</b></li>
<li>entre <b>20 y 30 manzanas</b>, el valor del parámetro manzana indivisible puede aumentarse a <b>10</b> si se desea que el proceso demore menos tiempo de ejecución;</li>
<li><b>más de 30 manzanas</b>, se sugiere que el valor del parámetro manzana indivisible se incremente y <b>sea igual al valor deseado de segmentación (“X”)</b>.</li>
</div>
<p class="text-justify">
Ajustar estos valores de acuerdo a la cantidad de manzanas, permitirá optimizar los tiempos de segmentación y evitar procesamientos excesivamente largos.</p>
<div class="m-2 p-3 border rounded">
En el 2° caso, se recomienda probar distintos valores de deseado, máximo y mínimo, intentando que en alguna de esas pruebas el tamaño de los segmentos queden dentro de los márgenes de lo esperado.
<li style="margin:10px">Probar con los parámetros:
    <div class="code text-center">DESEADO: <b>“X”</b> , MÁXIMO: <b>“X + 2”</b> , MÍNIMO: <b>“X - 4”</b></div>
(X es el valor “deseado” asignado a cada provincia)
</li>
<li style="margin:10px">Si hay segmentos excedidos, segmentar con los parámetros:
 <div class="code text-center"> DESEADO: <b>“X”</b> , MÁXIMO: <b>“X”</b>  , MÍNIMO: <b>“X - 4”</b></div>
</li>
<li style="margin:10px">Si sigue habiendo segmentos excedidos, probar con los parámetros:
 <div class="code text-center"> DESEADO: <b>“X - 3”</b> , MÁXIMO: <b>“X - 3”</b>  , MÍNIMO: <b>“X - 4”</b></div>
</li>
<li>Si a pesar de estos 3 intentos aún continúan existiendo segmentos excedidos, se deberá tomar nota del caso (localidad, frac y radio correspondiente), agregar un issue en <a href="https://gitlab.indec.gob.ar/proyecto-mandarina/mandarina/issues">gitlab</a>  y seguir segmentando otros radios dejando estos casos particulares para retomar en una etapa posterior. </li>
</div>
<p class="text-justify">
Una vez que se ha avanzado en la segmentación de la <u>gran mayoría</u> de los radios, se <b>retomarán</b> aquellos casos que quedaron excedidos y que deben ajustarse los parámetros.
</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
