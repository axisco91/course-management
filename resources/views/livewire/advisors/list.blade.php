<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Asesorias</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('advisors.info')
    </div>
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="inactiveFilter"><input wire:model="inactiveFilter" id="inactiveFilter" type="checkbox"> Mostrar inactivos</label>
            </div>
            <div class="col-md-4">
                <a class="btn btn-sm btn-info" href="{{url('/advisors/create')}}">
                    <i data-feather="plus-circle" class="me-50"></i>  Añadir Asesoria
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($advisors as $row)
                <tr>
                    <td data-bs-toggle="modal" data-bs-target="#advisorsTabModal" wire:click="general({{$row->id}})">{{ $loop->iteration }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#advisorsTabModal" wire:click="general({{$row->id}})">{{ $row->name }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#advisorsTabModal" wire:click="general({{$row->id}})">{{ $row->nif }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#advisorsTabModal" wire:click="general({{$row->id}})">{{ $row->type }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#advisorsTabModal" wire:click="general({{$row->id}})">{{ $row->activity }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#advisorsTabModal" wire:click="general({{$row->id}})">{{ $row->email }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#advisorsTabModal" wire:click="general({{$row->id}})">{{ $row->telephone }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#advisorsTabModal" wire:click="general({{$row->id}})">{{ $row->legal_representative }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                            <!--<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>-->
                                <a href="{{url('/advisors/edit/'.$row->id)}}" class="dropdown-item"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                @if (!$row->used)
                                    <a class="dropdown-item eliminar" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                                @endif
                            </div>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
        {{ $advisors->links() }}
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
                    text: "Eliminaras a la asesoria!",
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
