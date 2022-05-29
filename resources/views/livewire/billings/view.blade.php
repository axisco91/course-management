<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Facturas</h4>
        @if (session()->has('message'))
            <input hidden id="toastr" data-type="success" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('livewire.billings.info')
    </div>
    <div class="card-footer">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">

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
                    <th>Nº Factura</th>
                    <th>Acción</th>
                    <th>Grupo</th>
                    <th>Curso</th>
                    <th>Empresa</th>
                    <th>Numero Alumnos</th>
                    <th>Factura</th>
                    <th>Acciónes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($billings as $row)
                <tr>
                    <td><a data-bs-toggle="modal" data-bs-target="#billingsTabModal" class="dropdown-item" wire:click="general({{$row->id}})">{{ $loop->iteration }}</a></td>
                    <td>{{ $row->billing_number }}</td>
                    <td>{{ $row->training_action }}</td>
                    <td>{{ $row->group}}</td>
                    <td>{{ $row->course }}</td>
                    <td>{{ $row->company }}</td>
                    <td>{{ $row->number_students }}</td>
                    <td>{{ $row->billing }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!--<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>-->
                                <a href="{{url('/billings/edit/'.$row->id)}}" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
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
