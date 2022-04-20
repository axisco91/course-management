@section('title', __('Bonuss'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Bonus Listing </h4>
						</div>
						<div wire:poll.60s>
							<code><h5>{{ now()->format('H:i:s') }} UTC</h5></code>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Search Bonuss">
						</div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Add Bonuss
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.bonuses.create')
						@include('livewire.bonuses.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Course Id</th>
								<th>Company Id</th>
								<th>Course Status Id</th>
								<th>Number Students</th>
								<th>Billing</th>
								<th>Bonus</th>
								<th>Total Training Activity</th>
								<th>Organization Expenses</th>
								<th>Only Organizing Entity</th>
								<th>Average Template</th>
								<th>Salary Cost</th>
								<th>Payment Id</th>
								<th>Start Communication Date</th>
								<th>Close Communication Date</th>
								<th>Invoiced</th>
								<th>Invoice Number</th>
								<th>Invoice Date</th>
								<th>Collection Date</th>
								<th>Status Bonus</th>
								<th>Date</th>
								<th>Company Bonus</th>
								<th>Observations</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($bonuses as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->course_id }}</td>
								<td>{{ $row->company_id }}</td>
								<td>{{ $row->course_status_id }}</td>
								<td>{{ $row->number_students }}</td>
								<td>{{ $row->billing }}</td>
								<td>{{ $row->bonus }}</td>
								<td>{{ $row->total_training_activity }}</td>
								<td>{{ $row->organization_expenses }}</td>
								<td>{{ $row->only_organizing_entity }}</td>
								<td>{{ $row->average_template }}</td>
								<td>{{ $row->salary_cost }}</td>
								<td>{{ $row->payment_id }}</td>
								<td>{{ $row->start_communication_date }}</td>
								<td>{{ $row->close_communication_date }}</td>
								<td>{{ $row->invoiced }}</td>
								<td>{{ $row->invoice_number }}</td>
								<td>{{ $row->invoice_date }}</td>
								<td>{{ $row->collection_date }}</td>
								<td>{{ $row->status_bonus }}</td>
								<td>{{ $row->date }}</td>
								<td>{{ $row->company_bonus }}</td>
								<td>{{ $row->observations }}</td>
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
					{{ $bonuses->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
