<div class="container">
    Información del aglomerado ({{ $aglomerado->codigo }}) 
    <b> {{ $aglomerado->nombre }} </b><br />

<div class="form-horizontal">
<form action="/grafo/{{ $aglomerado->id }}" method="GET" enctype="multipart/form-data">
                @csrf

  <div class="form-group">
    <label class="control-label" for="localidad">Seleccione una Localidad:</label>
    <div class="">
    <ul class="nav row justify-content-around">
            @foreach($localidades as $localidad)
    <li class="btn  btn-outline-primary" style="margin-bottom: 5px" >
    <a href="{{ url('/localidad/'.$localidad->id) }}">
        {{ trim($localidad->codigo) }}: {{ trim($localidad->nombre) }} 
    </a>
    </li>
            @endforeach
    </ul>
    </div>
  </div>
</form>
</div>

</div>
