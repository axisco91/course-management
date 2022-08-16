<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Alumnos</h4>
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">

        @include('students.info')
    </div>
    <!--Search Form -->
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <label class="form-label">Nombre:</label>
                <input wire:model="search_name" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
            </div>
            <div class="col-md-4">
                <label class="form-label">Apellidos:</label>
                <input wire:model="search_surname" type="text" class="form-control dt-input" data-column="2" placeholder="Apellidos" data-column-index="1" />
            </div>
            <div class="col-md-4">
                <label class="form-label">DNI:</label>
                <input wire:model="search_dni" type="text" class="form-control dt-input" data-column="3" placeholder="DNI" data-column-index="2" />
            </div>
        </div>
        <div class="row g-1">
            <div class="col-md-4">
                <label class="form-label">Telephono:</label>
                <input wire:model="search_telephone" type="text" class="form-control dt-input" data-column="4" placeholder="Telephono" data-column-index="3" />
            </div>
            <div class="col-md-4">
                <label class="form-label">Correo:</label>
                <input wire:model="search_email" type="text" class="form-control dt-input" data-column="5" placeholder="Correo" data-column-index="4" />
            </div>
            <div class="col-md-4">
                <label class="form-label">Empresa:</label>
                <input wire:model="search_company" type="text" class="form-control dt-input" data-column="6" placeholder="Empresa" data-column-index="5" />
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
                <a wire:ignore class="btn btn-sm btn-info" href="{{url('/students/create')}}">
                    <i data-feather="plus-circle" class="me-50"></i> Añadir Alumno
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
                <th class="tableHeader" data-header="name">Nombre</th>
                <th class="tableHeader" data-header="surname">Apellidos</th>
                <th class="tableHeader" data-header="dni">Dni</th>
                <th class="tableHeader" data-header="telephone">Telephono</th>
                <th class="tableHeader" data-header="email">Correo</th>
                <th class="tableHeader" data-header="company">Empresa</th>
                <th class="tableHeader" data-header="state">Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($students as $row)
            <tr>
                <td data-bs-toggle="modal" data-bs-target="#studentsTabModal" wire:click="general({{$row->id}})">{{ $loop->iteration }}</td>
                <td data-bs-toggle="modal" data-bs-target="#studentsTabModal" wire:click="general({{$row->id}})">{{ $row->name }}</td>
                <td data-bs-toggle="modal" data-bs-target="#studentsTabModal" wire:click="general({{$row->id}})">{{ $row->surname }}</td>
                <td data-bs-toggle="modal" data-bs-target="#studentsTabModal" wire:click="general({{$row->id}})">{{ $row->dni }}</td>
                <td data-bs-toggle="modal" data-bs-target="#studentsTabModal" wire:click="general({{$row->id}})">{{ $row->telephone }}</td>
                <td data-bs-toggle="modal" data-bs-target="#studentsTabModal" wire:click="general({{$row->id}})">{{ $row->email }}</td>
                <td data-bs-toggle="modal" data-bs-target="#studentsTabModal" wire:click="general({{$row->id}})">{{ $row->company }}</td>
                <td data-bs-toggle="modal" data-bs-target="#studentsTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->active == 0 ?'danger' : 'success'}} me-1">{{$row->active == 0 ? 'Inactivo' : 'Activo'}}</span></td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{url('/students/edit/'.$row->id)}}" class="dropdown-item"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                            @if ($row->activo === 0)
                                <a class="dropdown-item activate" data-id="{{$row->id}}"><i class="fa-regular fa-eye"></i> Activar </a>
                            @else
                                <a class="dropdown-item desactivate" data-id="{{$row->id}}"><i class="fa-regular fa-eye-slash"></i> Desactivar </a>
                            @endif
                            @if (!$row->used)
                                <a class="dropdown-item eliminar" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{ $students->links() }}
    </div>
    @section('vendor-script')
        <!-- vendor files -->
            <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    @endsection
    @section('page-script')
        <!-- Page js files -->
        <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    @endsection
    @section('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
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
                        text: "Eliminaras al alumno!",
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
                $('body').on('click', '.desactivate', function () {
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
                        text: "Desactivaras al alumno!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Si, desactivalo!',
                        cancelButtonText: 'No, cancela!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            id = $(this).data('id');
                            Livewire.emit('changeState', id)
                            window.addEventListener('state-update', e=>{
                                if (e.detail.value === 'desactivated'){
                                    swalWithBootstrapButtons.fire(
                                        'Desactivado!',
                                        'Desactivado con exito.',
                                        'success'
                                    )
                                } else{
                                    swalWithBootstrapButtons.fire(
                                        'Error',
                                        'Fallo al desactivar al alumno.',
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
                                'No se ha desactivado al alumno',
                                'error'
                            )
                        }
                    })
                })
                $('body').on('click', '.activate', function () {
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
                        text: "Activar al alumno!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Si, activalo!',
                        cancelButtonText: 'No, cancela!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            id = $(this).data('id');
                            Livewire.emit('changeState', id)
                            window.addEventListener('state-update', e=> {
                                if (e.detail.value === 'desactivated') {
                                    swalWithBootstrapButtons.fire(
                                        'Activado!',
                                        'Activado con exito.',
                                        'success'
                                    )
                                    success()
                                } else {
                                    swalWithBootstrapButtons.fire(
                                        'Error',
                                        'Fallo al activar al alumno',
                                        'error'
                                    )
                                }
                            })
                        } else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swalWithBootstrapButtons.fire(
                                'Cacelado',
                                'No se ha desactivado al alumno',
                                'error'
                            )
                        }
                    })
                })
            })
            initializeSelect2()
            $('.select2').on('change', function(){
            @this.set(this.id, this.value)
            })
            $('#training_action_id').on('change', function(){
            @this.set(this.id, this.value)
            @this.setName()
            })
        </script>
    @endsection
</div>
