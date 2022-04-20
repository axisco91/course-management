@section('title', __('Chores'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Chore Listing </h4>
						</div>
						<div wire:poll.60s>
							<code><h5>{{ now()->format('H:i:s') }} UTC</h5></code>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Search Chores">
						</div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Add Chores
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.chores.create')
						@include('livewire.chores.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Course Id</th>
								<th>Company Id</th>
								<th>Student Id</th>
								<th>Membership Tab Status</th>
								<th>Membership Tab Date</th>
								<th>Economic Proposal Status</th>
								<th>Economic Proposal Date</th>
								<th>Student Tab Status</th>
								<th>Student Tab Date</th>
								<th>Welcome Guid Status</th>
								<th>Welcome Guid Date</th>
								<th>Registration Status</th>
								<th>Registration Status Date</th>
								<th>Diploma Status</th>
								<th>Diploma Status Date</th>
								<th>Start Communication Status</th>
								<th>Start Communication Date</th>
								<th>Close Communication Status</th>
								<th>Close Communication Date</th>
								<th>Invoiced Status</th>
								<th>Invoiced Date</th>
								<th>Bonus Sent Status</th>
								<th>Bonus Sent Date</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($chores as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->course_id }}</td>
								<td>{{ $row->company_id }}</td>
								<td>{{ $row->student_id }}</td>
								<td>{{ $row->membership_tab_status }}</td>
								<td>{{ $row->membership_tab_date }}</td>
								<td>{{ $row->economic_proposal_status }}</td>
								<td>{{ $row->economic_proposal_date }}</td>
								<td>{{ $row->student_tab_status }}</td>
								<td>{{ $row->student_tab_date }}</td>
								<td>{{ $row->welcome_guid_status }}</td>
								<td>{{ $row->welcome_guid_date }}</td>
								<td>{{ $row->registration_status }}</td>
								<td>{{ $row->registration_status_date }}</td>
								<td>{{ $row->diploma_status }}</td>
								<td>{{ $row->diploma_status_date }}</td>
								<td>{{ $row->start_communication_status }}</td>
								<td>{{ $row->start_communication_date }}</td>
								<td>{{ $row->close_communication_status }}</td>
								<td>{{ $row->close_communication_date }}</td>
								<td>{{ $row->invoiced_status }}</td>
								<td>{{ $row->invoiced_date }}</td>
								<td>{{ $row->bonus_sent_status }}</td>
								<td>{{ $row->bonus_sent_date }}</td>
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
					{{ $chores->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
