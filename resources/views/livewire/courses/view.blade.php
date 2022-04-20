@section('title', __('Courses'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Cursos </h4>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Añadir curso
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.courses.create')
                        @include('livewire.courses.update')
                        @include('livewire.courses.registrations')
                        @include('livewire.courses.tracings')
                        @include('livewire.courses.chores')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Nombre</th>
								<th>Grupo</th>
								<th>Tipo</th>
								<th>Docente</th>
								<th>Fecha inicio</th>
								<th>Fecha fin</th>
								<th>Estado</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($courses as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->name }}</td>
								<td>{{ $row->group }}</td>
								<td>{{ $row->course_type }}</td>
								<td>{{ $row->teacher }}</td>
								<td>{{ $row->beginning }}</td>
								<td>{{ $row->end }}</td>
								<td>{{ $row->course_status }}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Acciones
									</button>
									<div class="dropdown-menu dropdown-menu-right">
                                        <!--<a class="dropdown-item edit" href="{{url('/courses/update/'.$row->id)}}" wire:click="edit({{$row->id}})"><i class="fa fa-edit"></i> Editar </a>-->
                                        <a data-bs-toggle="modal" class="dropdown-item" data-bs-target="#updateModal" wire:click="edit({{$row->id}})"><i class="fa fa-edit"></i> Editar</a>
                                        <a data-bs-toggle="modal" data-bs-target="#registrationsModal" class="dropdown-item" wire:click="registrations({{$row->id}})"><i class="fas fa-chalkboard-teacher"></i> Matriculaciones </a>
                                        <a data-bs-toggle="modal" data-bs-target="#tracingsModal" class="dropdown-item" wire:click="tracings({{$row->id}})"><i class="fas fa-chalkboard-teacher"></i> Seguimiento</a>
                                        <a data-bs-toggle="modal" data-bs-target="#choresModal" class="dropdown-item" wire:click="chores({{$row->id}})"><i class="fas fa-chalkboard-teacher"></i> Tareas</a>
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>
					{{ $courses->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
