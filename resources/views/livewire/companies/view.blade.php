@section('title', __('Companys'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Empresas </h4>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Añadir Empresa
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.companies.create')
						@include('livewire.companies.update')
                        @include('livewire.companies.createObservation')
                        @include('livewire.companies.observations')
                        @include('livewire.companies.updateObservation')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Nombre</th>
								<th>CIF</th>
								<th>Tipo</th>
								<th>Actividad</th>
								<th>Correo</th>
								<th>Telefono</th>
								<th>Representante Legal</th>
								<th>Asesoria</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($companies as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->name }}</td>
								<td>{{ $row->nif }}</td>
								<td>{{ $row->type }}</td>
								<td>{{ $row->activity }}</td>
								<td>{{ $row->email }}</td>
								<td>{{ $row->telephone }}</td>
								<td>{{ $row->legal_representative }}</td>
								<td>{{ $row->advisor }}</td>
								<td width="90">
								<div class="btn-group dropup">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Acciones
									</button>
									<div class="dropdown-menu dropdown-menu-right">
                                        <a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa fa-edit"></i> Editar </a>
                                        @if ($row->is_advisor)
                                            <a class="dropdown-item" onclick="confirm('Confirmar convertir a asesoria: {{$row->name}}?')||event.stopImmediatePropagation()" wire:click="convertAdvisor({{$row->id}})">Convertir Asesoria</a>
                                        @endif
                                        @if ($row->is_provider)
                                            <a class="dropdown-item" onclick="confirm('Confirmar convertir a proveedor: {{$row->name}}?')||event.stopImmediatePropagation()" wire:click="convertProvider({{$row->id}})">Convertir Proveedor</a>
                                        @endif
                                        <a data-bs-toggle="modal" data-bs-target="#createObservationModal" class="dropdown-item" wire:click="newObservation({{$row->id}})"><i class="fa fa-edit"></i> Crear Observación </a>
                                        <a data-bs-toggle="modal" data-bs-target="#observationsModal" class="dropdown-item" wire:click="observations({{$row->id}})"><i class="fa fa-edit"></i> Ver Observaciones </a></a>
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>
					{{ $companies->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
