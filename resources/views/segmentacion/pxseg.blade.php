@extends('layouts.default')

@section('title', 'Tabla de Segmentación aglomerado: {{ $aglomerado->nombre }}')

@section('content')
<div class=container >
     @include('pxseg.info')
</div>
@stop
