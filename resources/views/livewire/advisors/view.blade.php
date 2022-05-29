<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Asesorias</h4>
        @if (session()->has('message'))
            <input hidden id="toastr" data-type="success" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
    </div>
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <a class="btn btn-sm btn-info" href="{{url('/advisors/create')}}">
                    <i class="fa fa-plus"></i>  Añadir Asesoria
                </a>
            </div>
        </div>
    </div>
    <hr class="my-0" />
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <td>#</td>
                    <th>Nombre</th>
                    <th>CIF</th>
                    <th>Tipo</th>
                    <th>Actividad</th>
                    <th>Correo</th>
                    <th>Telefono</th>
                    <th>Representante Legal</th>
                    <th>Asesoria</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($advisors as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->nif }}</td>
                    <td>{{ $row->type }}</td>
                    <td>{{ $row->activity }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->telephone }}</td>
                    <td>{{ $row->legal_representative }}</td>
                    <td>{{ $row->advisor }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                            <!--<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>-->
                            <a href="{{url('/advisors/edit/'.$row->id)}}" class="dropdown-item"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                            </div>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
        {{ $advisors->links() }}
        </div>
    </div>
</div>
