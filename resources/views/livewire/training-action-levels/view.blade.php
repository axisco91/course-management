@section('title', __('Training Action Levels'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
                                Niveles Acciónes Formativas </h4>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Añadir Nivel
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.training-action-levels.create')
						@include('livewire.training-action-levels.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Nivel</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($trainingActionLevels as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->name }}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Acciones
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa fa-edit"></i> Editar </a>
                                    @if (!$row->used)
									    <a class="dropdown-item" onclick="confirm('Confirmar eliminar nivel: {{$row->name}}? \nNo se podra restaurar y se perderar todo la información donde se utilize!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"><i class="fa fa-trash"></i> Eliminar </a>
                                    @endif
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>
					{{ $trainingActionLevels->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
