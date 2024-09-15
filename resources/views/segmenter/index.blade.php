@extends('layouts.app')

@section('content')
<div class="container">
   <h4 class="center"> Carga de base geógrafica, listado de viviendas y PxRad.</h4>
    <div class="row justify-content-center">
        <div class="center-block">
            @isset($data)
                <div class="alert alert-primary" role="alert">
                    <ul>
      @foreach ($data as $index => $value)
				@if (isset($data['file']))
                   	<p>El usuario {{Auth::user()->name}} subió los siguientes archivos:</p>
                       	@foreach ($value as $index_file => $value_file)
  	                    		<li>{{$index_file}} -> {{$value_file}}</li>
           				@endforeach
				@else 
	                    <p>Y estas otras cosas... :</p>
               	        <li>{{$index}} -> {{$value}}</li>
				@endif
        @if ($loop->last)
        	The current UNIX timestamp is {{ time() }}. {{ date('Y-m-d H:m:s') }} UTC.
        @endif
     @endforeach
                    </ul>
                </div>
            @endisset
            <form action="/segmentador/guardar" method="POST" enctype="multipart/form-data" class="form-horizontal ">
                @csrf
		  @if (isset($epsgs))
                   <div class="form-group row bg-info" >
                    <label for="epsg" class="col-sm-2 control-label">Sistema de Referencia:</label>
                    <div class="col-sm-10 " style="padding-top:5px;">
                            <select id="epsg" name="epsg_id" class="form-control " >
			     <option disabled selected value>-- Seleccione el Sistema de Referencia de los datos --</option>
			        @foreach($epsgs as $id => $epsg)
					<option value="{{$id}}"> {{$epsg}} </option>
			        @endforeach
			    </select>
                      <span style="color: red;">@error ('epsg'){{$message}} @enderror</span>
		    </div>
		   </div>
		  @endif
<br />
                <div class="row border " style="background-color:lemonchiffon">
                 <div class="col-sm-6 border ">
                  <div class="form-group row border bg-info ">
                    <label for="shp" class="col-sm-4 col-form-label ">Base geográfica (e00/SHP)</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="shp" name="shp">
                    </div>
		  </div>
                  <div class="form-group row">
                    <label for="shx" class="col-sm-4 col-form-label ">SHX</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="shx" name="shx">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="dbf" class="col-sm-4 col-form-label ">DBF</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="dbf" name="dbf">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="prj" class="col-sm-4 col-form-label ">PRJ</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="prj" name="prj">
                    </div>
                  </div>
                 </div>
                 <div class="col-sm-6 border">
                  <div class="form-group row">
                    <label for="shpi_lab" class="col-sm-4 col-form-label ">(Etiquetas) SHP</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="shpi_lab" name="shp_lab">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="shx_lab" class="col-sm-4 col-form-label ">(Etiquetas) SHX</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="shx_lab" name="shx_lab">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="dbf_lab" class="col-sm-4 col-form-label ">(Etiquetas) DBF</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="dbf_lab" name="dbf_lab">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="prj_lab" class="col-sm-4 col-form-label ">(Etiquetas) PRJ</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="prj_lab" name="prj_lab">
                    </div>
                  </div>
                 </div>
                </div>
<br />
                <div class="form-group row bg-info ">
                    <label for="c1" class="col-sm-4 col-form-label text-rigth">C1</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file text-left" id="c1" name="c1">
                    </div>
                </div>
<br />
                <div class="form-group row bg-info ">
                    <label for="pxrad" class="col-sm-4 col-form-label ">PxxRad (del departamento)</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="pxrad" name="pxrad">
                    </div>
		            </div>
                <div class="form-group row bg-success">
                    <label for="tabla_segmentos" class="col-sm-4 col-form-label ">tablaSegxxcompleta (provincial)</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="tabla_segmentos" name="tabla_segmentos">
                    </div>
            		</div>
		<div class="form-group">
		  <div class="text-center">
                     <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
		</div>
            </form>
        </div>
    </div>
</div>
@endsection
