@extends('layouts.default')

@section('title', 'Aglomerado {{ $aglomerado->nombre }}')

@section('content')
     @include('radios.index')
@stop
