<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Alumnos</h4>
        @if (session()->has('message'))
            <input hidden id="toastr" data-type="success" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('livewire.students.info')
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
                <input wire:model="search_comapny" type="text" class="form-control dt-input" data-column="6" placeholder="Empresa" data-column-index="5" />
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
                    <i data-feather="plus-circle" class="me-50"></i>  Añadir Alumno
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
                <td>{{ $loop->iteration }}</td>
                <td><a data-bs-toggle="modal" data-bs-target="#studentsTabModal" class="dropdown-item" wire:click="general({{$row->id}})">{{ $row->name }}</a></td>
                <td>{{ $row->surname }}</td>
                <td>{{ $row->dni }}</td>
                <td>{{ $row->telephone }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->company }}</td>
                <td><span class="badge rounded-pill badge-light-{{$row->active == 0 ?'danger' : 'success'}} me-1">{{$row->active == 0 ? 'Inactivo' : 'Activo'}}</span></td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{url('/students/edit/'.$row->id)}}" class="dropdown-item"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                            @if ($row->inactivo == 1)
                                <a class="dropdown-item" onclick="confirm('¿Quieres volver a activar a {{$row->name}} {{$row->surname}}?')||event.stopImmediatePropagation()" wire:click="changeState({{$row->id}})"><i class="fa-regular fa-eye"></i> Activar </a>
                            @else
                                <a class="dropdown-item desactivate" data-id="{{$row->id}}"><i class="fa-regular fa-eye-slash"></i> Desactivar </a>
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
    <script>
       /* swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                swalWithBootstrapButtons.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            } else if (
                /* Read more about handling dismissals below */
        /*        result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                )
            }
        })*/

        window.addEventListener('name-updated', e=>{
            alert('loaded');
        });
        $(window).on('click', '.desactivate', function () {
            alert('llega')
            id = $(this).data('id');
            Livewire.emit('changeState', id);
        })
        document.addEventListener('livewire:load', function () {
            $('body').on('click', '.inactive', function () {
                button = $(this)
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                })
            })
            /*$('body').on('click', '.inactive', function (){
                button = $(this)
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "¡Se te pondra como inactivo!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '¡Si!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        alert('entra')
                        @this.changeState(button.data('id'))
                        toastr['success'](
                            $('#toastr').val(),
                            {
                                closeButton: true,
                                tapToDismiss: false,
                                rtl: isRtl
                            }
                        );
                        /*
                        Swal.fire(
                            'Inactivo!',
                            'Se te ha pasado a estar inactivo.',
                            'success'
                        )*/
                /*    }
                })
            })*/

        })
    </script>
</div>
