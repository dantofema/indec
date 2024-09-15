@extends('layouts.app')

@section('content')
<div class="container">
	@if(session()->has('status'))
		<p class="alert alert-info">
			{{	session()->get('status') }}
		</p>
	@endif
	<div class="panel panel-default">
		<div class="panel-heading">
			Listado Listing
			{{-- <a href="{{ route('listado.create') }}" class="btn btn-success btn-xs">Add Listado</a> --}}
		</div>
		<div class="panel-body">
			@if (count($listado))
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>	
								<th>id</th>
								<th>Prov</th>
								<th>Depto</th>
								<th>Aglo</th>
								<th>Localidad</th>
								<th>Frac</th>
								<th>Radio</th>
								<th>Mza</th>
								<th>Lado</th>
								<th>Calle</th>
								<th>Numero</th>
								<th>Detalle</th>
								<th>Tipo Vivienda</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($listado as $item_listado)
							<tr>
								<td>{{ $item_listado->id }}</td>
								<td>{{ $item_listado->prov }} - {{ $item_listado->nom_provincia }}</td>
								<td>{{ $item_listado->dpto }} - {{ $item_listado->nom_dpto }}</td>
								<td>{{ $item_listado->codaglo }}</td>
								<td>{{ $item_listado->codloc }} - {{ $item_listado->nom_loc }}</td>
								<td>{{ $item_listado->frac }}</td>
								<td>{{ $item_listado->radio }}</td>
								<td>{{ $item_listado->mza }}</td>
								<td>{{ $item_listado->lado }}</td>
								<td>{{ $item_listado->ccalle }} - {{ $item_listado->ncalle }} ({{ (int)$item_listado->nro_inicial }} - {{ (int)$item_listado->nro_final }})</td>
								<td>{{ $item_listado->nrocatastralredef }}</td>
								<td> @if(strlen(trim($item_listado->pisoredef))>0) Piso: {{ $item_listado->pisoredef }} @endif
								     @if(strlen(trim($item_listado->casa))>0) Casa: {{ $item_listado->casa }} @endif
								     @if(strlen(trim($item_listado->dpto_habitacion))>0) Dpto/Hab: {{ $item_listado->dpto_habitacion }} @endif
								     @if(strlen(trim($item_listado->sector))>0) Sector: {{ $item_listado->sector }} @endif
								     @if(strlen(trim($item_listado->edificio))>0) Edificio: {{ $item_listado->edificio }} @endif
								     @if(strlen(trim($item_listado->entrada))>0) Entrada: {{ $item_listado->entrada }} @endif
								     @if(strlen(trim($item_listado->descripcion))>0) Descripción: {{ $item_listado->descripcion }} @endif
								</td>
								<td>{{ $item_listado->cod_tipo_vivredef }}</td>
								<td>
{{-- 									<a href="{{ route('listado.save', $item_listado->id) }}" class="btn btn-success btn-xs">Save</a>
 									<a href="{{ route('listado.show', $item_listado->id) }}" class="btn btn-info btn-xs">View</a>
									<form action="{{ route('listado.destroy', $item_listado->id) }}" method="POST" style="display:inline-block">
										{{ csrf_field() }}
										{{ method_field('DELETE') }}
										<button class="btn btn-danger btn-xs">
											<span>DELETE</span>
										</button>
									</form>
 --}}
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="text-center">
					{{-- $listado->links() --}}
				</div>
			@else
				<p class="alert alert-info">
					No Listing Found
				</p>
			@endif
		</div>
	</div>
</div>
@endsection
