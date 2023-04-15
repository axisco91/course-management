<div class="card-body">
    <div class="mb-1">
        <a class="btn" href="{{asset('/companies/edit/'.$selected_id)}}">General</a>
        <a class="btn btn-primary">Observaciones</a>
    </div>
    @if (session()->has('message'))
        <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
    @endif
    @if (session()->has('error'))
        <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
    @endif
    <div class="row">
        <div class="form-group">
            <label class="form-label" for="observation">Observación</label>
            <textarea wire:model="observation" class="form-control" id="observation" placeholder="Observación"></textarea>@error('observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-12 mt-2 pt-50">
        @if($observation_id)
            <button type="button" wire:click.prevent="new" class="btn btn-success" data-bs-dismiss="modal">Nuevo</button>
            <button type="button" wire:click.prevent="update" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
        @else
            <button type="button" wire:click.prevent="store" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
        @endif
    </div>
    <br>
    <hr class="my-0" />
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <td>#</td>
                <th>Observación</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($observations as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['observation'] }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item edit" wire:click="edit({{$row['id']}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                <a class="dropdown-item eliminate" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                            </div>
                        </div>
                    </td>
            @endforeach
            </tbody>
        </table>
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
                    text: "Eliminaras a la observación!",
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
</div>
