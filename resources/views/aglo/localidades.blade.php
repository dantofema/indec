<div class="container">
    @foreach($aglomerado->localidades as $localidad)
      	@foreach($localidad->departamentos as $departamento)
	    @php ($provincias[$departamento->provincia->codigo]=$departamento->provincia)
	    @php ($departamentos[$departamento->codigo]=$departamento)
	    @php ($localidades[$localidad->codigo]=$localidad)
	@endforeach
    @endforeach
    @if ( isset($provincias) )
      @foreach ($provincias as $provincia)
		@if ($provincia)
                  <li class="btn  btn-outline-secondary" style="margin-bottom: 2px" >
                     <a href="{{ url("/prov/{$provincia->id}") }}" >({{ $provincia->codigo }})
                     <b> {{ $provincia->nombre }} </b></a>
                 </li>
               @endif
       @endforeach
    @endif
    <h3>Aglomerado ({{ $aglomerado->codigo }}) 
    <b> {{ $aglomerado->nombre }} </b></h3>
    @if ( isset($departamentos) )
        @foreach($departamentos as $departamento)
          @if($loop->first)
	     En {{count($departamentos)}}
             @if ($departamento->denominacion)
                {{ $departamento->denominacion }}
             @else
                Departamento / Partido / Comuna
             @endif
	  @endif
	 <a href="{{ url('/depto/'.$departamento->id) }}">
({{ $departamento->codigo }}) {{ $departamento->nombre }} </a>
       @endforeach
    @endif

<div class="form-horizontal">
<form action="/grafo/{{ $aglomerado->id }}" method="GET" enctype="multipart/form-data">
                @csrf

  <div class="form-group">
    <label class="control-label" for="localidad">
	 {{$aglomerado->localidades->count()}} localidades: </label>
   @if ( isset($localidades) )
   <div class="">
    <ul class="nav justify-content-around">
    @foreach ($localidades as $localidad)
    <li class="btn  btn-outline-primary" style="margin-bottom: 5px" >
    <a href="{{ url('/localidad/'.$localidad->id) }}">
	{{ trim($localidad->codigo) }}: {{ trim($localidad->nombre) }}</br >

    </a>(
	    @foreach ($localidad->departamentos()->orderBy('codigo')->get() as $departamento)
    		<a href="{{ url('/depto/'.$departamento->id) }}">
		       {{ trim($departamento->nombre) }} 
		    </a>
            @if (!$loop->last)
             |
            @endif
        @endforeach
        )
    </li>
    @endforeach
    </ul>
    </div>
    @else
        No se encontraron localidades.
    @endif
  </div>
</form>
</div>

</div>
