@section('title', __('Students'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Alumnos </h4>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
                        <div>
                            <label for="inactiveFilter"><input wire:model="inactiveFilter" id="inactiveFilter" type="checkbox"> Mostrar inactivos</label>
                        </div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Añadir Alumno
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.students.create')
						@include('livewire.students.update')
                        @include('livewire.students.info')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Nombre</th>
								<th>Apellidos</th>
								<th>Dni</th>
								<th>Telephono</th>
								<th>Correo</th>
								<th>Empresa</th>
								<th>Usuario</th>
                                <th>Estado</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($students as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
                                <td><a data-bs-toggle="modal" data-bs-target="#studentsTabModal" class="dropdown-item" wire:click="general({{$row->id}})">{{ $row->name }}</a></td>
								<td>{{ $row->surname }}</td>
								<td>{{ $row->dni }}</td>
								<td>{{ $row->telephone }}</td>
								<td>{{ $row->email }}</td>
								<td>{{ $row->company }}</td>
                                <td>{{ $row->user }}</td>
                                <td>{{$row->inactive == 1 ? 'Inactivo' : 'Activo'}}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Acciones
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<!--<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item edit" wire:click="edit({{$row->id}})"><i class="fa fa-edit"></i> Editar </a>-->
                                        <a href="{{url('/students/edit/'.$row->id)}}" class="dropdown-item edit"><i class="fa fa-edit"></i> Editar </a>
                                        <a data-bs-toggle="modal" data-bs-target="#studentsTabModal" class="dropdown-item" wire:click="general({{$row->id}})"><i class="fa fa-watch"></i> Ver </a>
                                        @if ($row->inactivo == 1)
                                            <a class="dropdown-item" onclick="confirm('¿Quieres volver a activar a {{$row->name}} {{$row->surname}}?')||event.stopImmediatePropagation()" wire:click="changeState({{$row->id}})"><i class="fa fa-active"></i> Activar </a>
                                        @else
                                            <a class="dropdown-item" onclick="confirm('¿Quieres desactivar a {{$row->name}} {{$row->surname}}?')||event.stopImmediatePropagation()" wire:click="changeState({{$row->id}})"><i class="fa fa-active"></i> Desactivar </a>
                                        @endif
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>
					{{ $students->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
