@extends('layouts.default')
@section('content_main')
@auth
        {{ phpinfo() }}
@endauth

@guest
    Debe estar logueado para ver esta información.
@endguest
@stop
