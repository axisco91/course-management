<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Usuarios</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('livewire.users.create')
        @include('livewire.users.update')
        @include('livewire.users.changePassword')
    </div>
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <div wire:ignore class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
                    <i data-feather="plus-circle" class="me-50"></i> Añadir Usuario
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
                    <th>Apellidos</th>
                    <th>usuario</th>
                    <th>Correo</th>
                    <td>Acciones</td>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->surname }}</td>
                    <td>{{ $row->username }}</td>
                    <td>{{ $row->email }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                <a data-bs-toggle="modal" data-bs-target="#passwordModal" class="dropdown-item" wire:click="changePassword({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Cambiar Contraseña</a>
                            </div>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
        </div>
    </div>
</div>
