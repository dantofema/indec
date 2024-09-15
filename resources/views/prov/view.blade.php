@extends('layouts.default')

@section('title', 'Provincia {{ $provincia->nombre }}')

@section('content')
     @include('prov.info')
@stop
