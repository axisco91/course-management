<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Empresas Potenciales</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('new-potential-companies.info')
        @include('livewire.new-potential-companies.send-email')
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
                <div wire:ignore class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
                    <i data-feather="mail" class="me-50"></i> Enviar Correo
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
                <th>CIF</th>
                <th>Tipo</th>
                <th>Actividad</th>
                <th>Correo</th>
                <th>Telefono</th>
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
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                            <!--    <a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>-->
                                <a href="{{url('/companies/convert/'.$row->id)}}" class="dropdown-item edit"><i class="fa-regular fa-pen-to-square"></i> Convertir </a>
                                <a class="dropdown-item eliminate" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
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
            $('body').on('click', '.eliminate', function () {
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
