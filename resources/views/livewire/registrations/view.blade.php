@section('title', __('Registrations'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Registration Listing </h4>
						</div>
						<div wire:poll.60s>
							<code><h5>{{ now()->format('H:i:s') }} UTC</h5></code>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Search Registrations">
						</div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Add Registrations
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.registrations.create')
						@include('livewire.registrations.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Course Id</th>
								<th>Company Id</th>
								<th>Student Id</th>
								<th>Tracing Id</th>
								<th>Chore Id</th>
								<th>Price</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($registrations as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->course_id }}</td>
								<td>{{ $row->company_id }}</td>
								<td>{{ $row->student_id }}</td>
								<td>{{ $row->tracing_id }}</td>
								<td>{{ $row->chore_id }}</td>
								<td>{{ $row->price }}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Acciones
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="Editar({{$row->id}})"><i class="fa fa-Edit"></i> Editar </a>
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>
					{{ $registrations->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
