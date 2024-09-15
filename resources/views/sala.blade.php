@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center h-100">
        <div class="col-md-12 h-100 ">
            <div class="card h-100">
                <div class="card-header">Sala Mandarina</div>

                    <div class="card-body" style="height: 600px; ">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <iframe allow="camera; microphone; fullscreen; display-capture"
                            src="https://meet.jit.si/mandarina"
                            style="height: 100%; width: 100%; border: 0px; ">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
