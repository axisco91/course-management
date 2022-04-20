@section('title', __('Areas Teacher Areas'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Areas Teacher Area Listing </h4>
						</div>
						<div wire:poll.60s>
							<code><h5>{{ now()->format('H:i:s') }} UTC</h5></code>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Search Areas Teacher Areas">
						</div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Add Areas Teacher Areas
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.areasTeacherAreas.create')
						@include('livewire.areasTeacherAreas.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Teacher Id</th>
								<th>Teacher Area Id</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($areasTeacherAreas as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->teacher_id }}</td>
								<td>{{ $row->teacher_area_id }}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Acciones
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="Editar({{$row->id}})"><i class="fa fa-Edit"></i> Editar </a>
									<a class="dropdown-item" onclick="confirm('Confirm eliminar Areas Teacher Area id {{$row->id}}? \nNo se podra restaurar y se perderar todo la información donde se utilize!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"><i class="fa fa-trash"></i> Eliminar </a>
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>
					{{ $areasTeacherAreas->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
