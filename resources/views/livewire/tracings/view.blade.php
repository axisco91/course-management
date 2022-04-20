@section('title', __('Tracings'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Tracing Listing </h4>
						</div>
						<div wire:poll.60s>
							<code><h5>{{ now()->format('H:i:s') }} UTC</h5></code>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Search Tracings">
						</div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Add Tracings
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.tracings.create')
						@include('livewire.tracings.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Company Id</th>
								<th>Student Id</th>
								<th>Performed Activities</th>
								<th>Performed Hours</th>
								<th>Performed Units</th>
								<th>Follow Up Date</th>
								<th>Final Test</th>
								<th>Questionnaire</th>
								<th>Welcome Message</th>
								<th>Quarter Message</th>
								<th>Half Message</th>
								<th>Three Quarters Message</th>
								<th>Final Message</th>
								<th>Observation</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($tracings as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->company }}</td>
								<td>{{ $row->student }}</td>
								<td>{{ $row->performed_activities }}</td>
								<td>{{ $row->performed_hours }}</td>
								<td>{{ $row->performed_units }}</td>
								<td>{{ $row->follow_up_date }}</td>
								<td>{{ $row->final_test }}</td>
								<td>{{ $row->questionnaire }}</td>
								<td>{{ $row->welcome_message == 1 ? 'Si' : 'No' }}</td>
								<td>{{ $row->quarter_message == 1 ? 'Si' : 'No' }}</td>
								<td>{{ $row->half_message == 1 ? 'Si' : 'No' }}</td>
								<td>{{ $row->three_quarters_message == 1 ? 'Si' : 'No' }}</td>
								<td>{{ $row->final_message == 1 ? 'Si' : 'No' }}</td>
								<td>{{ $row->observation }}</td>
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
					{{ $tracings->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
