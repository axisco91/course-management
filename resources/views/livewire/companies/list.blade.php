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
        @include('livewire.companies.createCredit')
        @include('livewire.companies.credits')
        @include('livewire.companies.updateCredit')
    </div>
    <!--Search Form -->
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label">Nombre:</label>
                <input wire:model="search_name" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label">CIF:</label>
                <input wire:model="search_nif" type="text" class="form-control dt-input" data-column="2" placeholder="CIF" data-column-index="1" />
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="search_type_id">Tipo</label>
                    <select wire:model.lazy="search_type_id" class="form-select select2" id="search_type_id">
                        <option value="">Seleccione un tipo</option>
                        @foreach($company_types as $type)
                            <option value="{{$type['id']}}">{{$type['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row g-1 mb-md-1">
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="search_activity_id">Actividad</label>
                    <select wire:model.lazy="search_activity_id" class="form-select select2" id="search_activity_id">
                        <option value="">Seleccione una actividad</option>
                        @foreach($company_activities as $activity)
                            <option value="{{$activity['id']}}">{{$activity['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label" for="search_advisor_id">Asesoria</label>
                <div wire:ignore>
                    <select wire:model.lazy="search_advisor_id" class="form-select select2" id="search_advisor_id" placeholder="Advisor Id">
                        <option value="">Selección una Asesoria</option>
                        @foreach($advisors as $advisor)
                            <option value="{{$advisor['id']}}">{{$advisor['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="search_province_id">Provincia</label>
                    <select wire:model.lazy="search_province_id" class="form-select select2" id="search_province_id">
                        <option value="">Seleccione una provincia</option>
                        @foreach($provinces as $province)
                            <option value="{{$province['id']}}">{{$province['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="inactiveFilter"><input wire:model="inactiveFilter" id="inactiveFilter" type="checkbox"> Mostrar inactivos</label>
            </div>
            <div class="col-md-4">
                <a wire:ignore class="btn btn-sm btn-info" href="{{url('/companies/create')}}">
                    <i data-feather="plus-circle" class="me-50"></i>  Añadir Empresa
                </a>
                <button wire:ignore class="btn btn-sm btn-success" wire:click.prevent="downloadExcel()">
                    <i class="fa-solid fa-download"></i>  Descargar Excel
                </button>
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
                                <a data-bs-toggle="modal" data-bs-target="#createCreditModal" class="dropdown-item" wire:click="newCredit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Crear Credito </a></a>
                                <a data-bs-toggle="modal" data-bs-target="#creditsModal" class="dropdown-item" wire:click="credits({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Ver Creditos </a></a>
                                @if (!$row->used)
                                    <a class="dropdown-item eliminar" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                                @endif
                            </div>
                        </div>
                    </td>
            @endforeach
            </tbody>
        </table>
        {{ $companies->links() }}
    </div>
@section('vendor-script')
    <!-- vendor files -->
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
        <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    @endsection
    <script>
        document.addEventListener('livewire:load', function() {
            $('body').on('click', '.eliminar', function () {
                button = $(this)
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: '¿Estas seguro?',
                    text: "Eliminaras a la empresa!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, eliminalo!',
                    cancelButtonText: 'No, cancela!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        id = $(this).data('id');
                        Livewire.emit('destroy', id)
                        window.addEventListener('eliminated', e=>{
                            if (e.detail.value != ''){
                                swalWithBootstrapButtons.fire(
                                    'Eliminado!',
                                    'Eliminado con exito.',
                                    'success'
                                )
                            } else{
                                swalWithBootstrapButtons.fire(
                                    'Error',
                                    'Fallo al eliminar.',
                                    'error'
                                )
                            }
                        });

                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cacelado',
                            'No se ha podido eliminar.',
                            'error'
                        )
                    }
                })
            })
            initializeSelect2()
            $('.select2').on('change', function(){
            @this.set(this.id, this.value)
            })
        })
    </script>
</div>
