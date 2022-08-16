<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Proveedores</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('providers.info')
    </div>
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <a class="btn btn-sm btn-info" href="{{url('/providers/create')}}">
                    <i class="fa fa-plus"></i>  Añadir proveedor
                </a>
            </div>
        </div>
    </div>
    <hr class="my-0" />
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead">
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
                @foreach($providers as $row)
                <tr>
                    <td data-bs-toggle="modal" data-bs-target="#providersTabModal" wire:click="general({{$row->id}})">{{ $loop->iteration }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#providersTabModal" wire:click="general({{$row->id}})">{{ $row->name }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#providersTabModal" wire:click="general({{$row->id}})">{{ $row->nif }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#providersTabModal" wire:click="general({{$row->id}})">{{ $row->type }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#providersTabModal" wire:click="general({{$row->id}})">{{ $row->activity }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#providersTabModal" wire:click="general({{$row->id}})">{{ $row->email }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#providersTabModal" wire:click="general({{$row->id}})">{{ $row->telephone }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#providersTabModal" wire:click="general({{$row->id}})">{{ $row->legal_representative }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#providersTabModal" wire:click="general({{$row->id}})">{{ $row->advisor }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="{{url('/providers/edit/'.$row->id)}}" class="dropdown-item"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                @if (!$row->used)
                                    <a class="dropdown-item eliminar" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                                @endif
                            </div>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
        {{ $providers->links() }}
        </div>
    </div>
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
                    text: "Eliminaras al proveedor!",
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
        })
    </script>
    @endsection
</div>
