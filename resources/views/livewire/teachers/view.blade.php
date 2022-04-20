@section('title', __('Teachers'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Teacher Listing </h4>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Añadir Docente
						</div>
					</div>
				</div>
				<div class="card-body">
						@include('livewire.teachers.create')
						@include('livewire.teachers.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Name</th>
								<th>Surname</th>
								<th>Dni</th>
								<th>Email</th>
								<th>Telephone</th>
								<th>User</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($teachers as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->name }}</td>
								<td>{{ $row->surname }}</td>
								<td>{{ $row->dni }}</td>
								<td>{{ $row->email }}</td>
								<td>{{ $row->telephone }}</td>
								<td>{{ $row->user }}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
									Acciones
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa fa-edit"></i> Editar </a>
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>
					{{ $teachers->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
