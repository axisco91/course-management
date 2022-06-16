<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Empresas</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('companies.info')
        @include('livewire.companies.createObservation')
        @include('livewire.companies.observations')
        @include('livewire.companies.updateObservation')
    </div>
    <!--Search Form -->
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <label class="form-label">Nombre:</label>
                <input wire:model="search_name" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
            </div>
            <div class="col-md-4">
                <label class="form-label">CIF:</label>
                <input wire:model="search_nif" type="text" class="form-control dt-input" data-column="2" placeholder="CIF" data-column-index="1" />
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <a class="btn btn-sm btn-info" href="{{url('/companies/create')}}">
                    <i data-feather="plus-circle" class="me-50"></i>  Añadir Empresa
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
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($companies as $row)
                <tr>
                    <td data-bs-toggle="modal" data-bs-target="#companiesTabModal" wire:click="general({{$row->id}})">{{ $loop->iteration }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#companiesTabModal" wire:click="general({{$row->id}})">{{ $row->name }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#companiesTabModal" wire:click="general({{$row->id}})">{{ $row->nif }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#companiesTabModal" wire:click="general({{$row->id}})">{{ $row->type }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#companiesTabModal" wire:click="general({{$row->id}})">{{ $row->activity }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#companiesTabModal" wire:click="general({{$row->id}})">{{ $row->email }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#companiesTabModal" wire:click="general({{$row->id}})">{{ $row->telephone }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#companiesTabModal" wire:click="general({{$row->id}})">{{ $row->legal_representative }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#companiesTabModal" wire:click="general({{$row->id}})">{{ $row->advisor }}</td>
                    <td><span class="badge rounded-pill badge-light-{{$row->active == 0 ?'danger' : 'success'}} me-1">{{$row->active == 0 ? 'Inactivo' : 'Activo'}}</span></td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                            <!--    <a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>-->
                                <a href="{{url('/companies/edit/'.$row->id)}}" class="dropdown-item edit"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                <a data-bs-toggle="modal" data-bs-target="#companiesTabModal" class="dropdown-item" wire:click="general({{$row->id}})"><i class="fa fa-watch"></i> Ver </a>
                                @if ($row->is_advisor)
                                    <a class="dropdown-item" onclick="confirm('Confirmar convertir a asesoria: {{$row->name}}?')||event.stopImmediatePropagation()" wire:click="convertAdvisor({{$row->id}})">Convertir Asesoria</a>
                                @endif
                                @if ($row->is_provider)
                                    <a class="dropdown-item" onclick="confirm('Confirmar convertir a proveedor: {{$row->name}}?')||event.stopImmediatePropagation()" wire:click="convertProvider({{$row->id}})">Convertir Proveedor</a>
                                @endif
                                @if ($row->activo == 0)
                                    <a class="dropdown-item" onclick="confirm('¿Quieres volver a activar a {{$row->name}}?')||event.stopImmediatePropagation()" wire:click="changeState({{$row->id}})"><i class="fa fa-active"></i> Activar </a>
                                @else
                                    <a class="dropdown-item" onclick="confirm('¿Quieres desactivar a {{$row->name}}?')||event.stopImmediatePropagation()" wire:click="changeState({{$row->id}})"><i class="fa fa-active"></i> Desactivar </a>
                                @endif
                                <a data-bs-toggle="modal" data-bs-target="#createObservationModal" class="dropdown-item" wire:click="newObservation({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Crear Observación </a>
                                <a data-bs-toggle="modal" data-bs-target="#observationsModal" class="dropdown-item" wire:click="observations({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Ver Observaciones </a></a>
                            </div>
                        </div>
                    </td>
            @endforeach
            </tbody>
        </table>
        {{ $companies->links() }}
    </div>
</div>
