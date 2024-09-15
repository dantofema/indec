@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> {{ __('messages.Dashboard') }} </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
		    {{ __('messages.Welcome') }}
                </div>
                  <img src="/images/logo_censo2022.jpg" alt="Censo 2022 República Argentina">
            </div>
        </div>
    </div>
</div>
@endsection
