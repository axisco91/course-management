@section('title', __('Profitabilitys'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Rentabilidad </h4>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.profitabilities.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Curso</th>
								<th>Empresa</th>
								<th>Alumno</th>
								<th>Observaciones</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($profitabilities as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->course_id }}</td>
								<td>{{ $row->company_id }}</td>
								<td>{{ $row->student_id }}</td>
								<td>{{ $row->price }}</td>
								<td>{{ $row->license }}</td>
								<td>{{ $row->teacher }}</td>
								<td>{{ $row->management }}</td>
								<td>{{ $row->nebrija_title }}</td>
								<td>{{ $row->discount }}</td>
								<td>{{ $row->collaborator_commission }}</td>
								<td>{{ $row->advisor_commission }}</td>
								<td>{{ $row->total }}</td>
								<td>{{ $row->benefits }}</td>
								<td>{{ $row->observations }}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
					{{ $profitabilities->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
