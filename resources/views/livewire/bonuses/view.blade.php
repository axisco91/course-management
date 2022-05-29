<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Bonificado</h4>
        @if (session()->has('message'))
            <input hidden id="toastr" data-type="success" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('livewire.bonuses.update')
    </div>
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </div>
    <hr class="my-0" />
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
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
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                               <a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="Editar({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
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
