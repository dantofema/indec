<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mandarina') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- More Scripts -->
    @yield ('header_scripts')
    
</head>
<body>
    @yield('divs4content')
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <div class="m-0 p-0 text-center" >
                <a class="navbar-brand text-uppercase" href="{{ url('/') }}">
		<img src="/images/mandarina.svg" width="30" height="30" class="d-inline-block align-top" alt="">
{{ config('app.name', 'App sin nombre') }}
		</a>
                <div style="position: relative; top: -15px; height:0px;">{{ Git::branch() }}</div>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto btn">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}"> Inicio </a> </li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/provs') }}"> Provincias </a> </li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/aglos') }}"> Aglomerados </a> </li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/localidades') }}"> Localidades </a> </li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/segmentador') }}"> Cargar </a> </li>
                        <li class="nav-item"><a class="nav-link" href="{{
                        url('https://github.com/bichav/salidagrafica-atlas/archive/master.zip')
                        }}"> Descargar plugin </a> </li>
                    @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdownLogin" class="nav-link
                                dropdown-toggle" href="#logout" role="button"
                                aria-controls=logout
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre> {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div id=logout class="dropdown-menu dropdown-menu-right collapse"
                                aria-labelledby="navbarDropdownLogin">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
		        @include('flash::message')
            @yield('content')
        </main>
        </div>
            @yield('content_main')
        <div id="copyright" class="text-center justify-content-center"
            style="display:block"><hr />© 2023 INDEC - Geoestadística
            <div>{{ Git::branch() }} - {{ Git::version() }} -  {{ Git::lastCommitDate() }}</div>
            </div>
<script>
   $(document).ready( function () {
   $('#flash-overlay-modal').modal();
   $('div.alert').not('.alert-important').delay(5000).fadeOut(500);
});
</script>
    @yield ('footer_scripts')
</body>
</html>
