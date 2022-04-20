@section('title', __('Billings'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Facturas </h4>
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
						@include('livewire.billings.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
                                <th>Nº Factura</th>
								<th>Curso</th>
								<th>Empresa</th>
								<th>Numero Alumnos</th>
                                <th>Factura</th>
								<td>Acciónes</td>
							</tr>
						</thead>
						<tbody>
							@foreach($billings as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
                                <td>{{ $row->billing_number }}</td>
								<td>{{ $row->course }}</td>
								<td>{{ $row->company }}</td>
								<td>{{ $row->number_students }}</td>
                                <td>{{ $row->billing }}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
					{{ $billings->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
