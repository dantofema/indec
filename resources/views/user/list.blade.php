@extends ('layouts.app')
@section ('content_main') 
<div class="container text-center">
<div class="spinner-border text-muted"></div>
<div class="spinner-border text-primary"></div>
<div class="spinner-border text-success"></div>
<div class="spinner-border text-info"></div>
<div class="spinner-border text-warning"></div>
<div class="spinner-border text-danger"></div>
<div class="spinner-border text-secondary"></div>
<div class="spinner-border text-dark"></div>
<div class="spinner-border text-light"></div>
<div class="text-primary">Gracias !</div>
<ul class="card-columns text-center d-flex m-1 p-5 nav nav-center list-inline mx-auto justify-content-center">
@foreach ($users->shuffle() as $user)
<li class= "btn border border-dark m-1 p-1" title="{{ $user->email }} "> 
    {{ $user->name }}
</li>
@endforeach
</ul>
<div class="spinner-border text-muted"></div>
<div class="spinner-border text-primary"></div>
<div class="spinner-border text-success"></div>
<div class="spinner-border text-info"></div>
<div class="spinner-border text-warning"></div>
<div class="spinner-border text-danger"></div>
<div class="spinner-border text-secondary"></div>
<div class="spinner-border text-dark"></div>
<div class="spinner-border text-light"></div>
</div>
@endsection
<?php // </body> </html> ?>
