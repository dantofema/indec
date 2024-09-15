@extends('layouts.default')

@section('title', $provincia->nombre )

@section('content')
<div class=container >
     @include('provinfo')
</div>
@stop
