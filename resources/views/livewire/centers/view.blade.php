<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Centros</h4>
        @if (session()->has('message'))
            <input hidden id="toastr" data-type="success" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('livewire.centers.create')
        @include('livewire.centers.update')
        @include('livewire.centers.info')
    </div>
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
                    <i class="fa fa-plus"></i>  Añadir Centro
                </div>
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
                    <th>Dirección</th>
                    <th>Correo</th>
                    <th>Telefono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($centers as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a data-bs-toggle="modal" data-bs-target="#centersTabModal" class="dropdown-item" wire:click="general({{$row->id}})">{{ $row->name }}</a></td>
                    <td>{{ $row->address }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->telephone }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                @if (!$row->used)
                                    <a class="dropdown-item" onclick="confirm('Confirmar eliminar centro: {{$row->name}}? \nNo se podra restaurar y se perderar todo la información donde se utilize!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"><i class="fa fa-trash"></i> Eliminar </a>
                                @endif
                            </div>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
        {{ $centers->links() }}
        </div>
    </div>
</div>
