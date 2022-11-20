 <div class="card-body mt-1">
        <a class="btn" href="{{asset('/training_contracts/edit/'.$selected_id)}}">Fase 1</a>
        <a class="btn" href="{{asset('/training_contracts/second_phase/'.$selected_id)}}">Fase 2</a>
        <a class="btn" href="{{asset('/training_contracts/excluded_days/'.$selected_id)}}">Dias Excluidos</a>
        <a class="btn btn-primary">Historico</a>
    </div>
    <div class="card-footer">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">
                <a wire:ignore class="btn btn-sm btn-info" href="{{url('/training_contracts/create')}}">
                    <i class="fa fa-plus" class="me-50"></i> Añadir Contrato Formativo
                </a>
            </div>
        </div>
    </div>
    <hr class="my-0" />
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Asunto</th>
                <th>Incidencia</th>
                <th>Responsables</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($training_contract_incidences as $row)
                <tr>
                    <th data-bs-toggle="modal" data-bs-target="#trainingContractIncidenceTabModal" wire:click="general({{$row->id}})">{{ $row->affair }}</th>
                    <td data-bs-toggle="modal" data-bs-target="#trainingContractTabModal" wire:click="general({{$row->id}})">{{ $row->incidence_type }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingContractTabModal" wire:click="general({{$row->id}})">{{ $row->user_name }} {{$row->user_surname}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingContractTabModal" wire:click="general({{$row->id}})">{{ $row->created_at }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!--<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>-->
                                <a href="{{url('/training_contract_incidences/edit/'.$row->id)}}" class="dropdown-item edit"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                @if (!$row->used)
                                    <a class="dropdown-item eliminar" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                                @endif
                            </div>
                        </div>
                    </td>
            @endforeach
            </tbody>
        </table>
        {{ $training_contract_incidences->links() }}
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
                    text: "Eliminaras la incidencia!",
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
            $( document ).ready(
                setTimeout(function (){
                    initializeSelect2()
                }, 100)
            );
            $('.select2').on('change', function(){
            @this.set(this.id, $(this).val())
            })
        })
    </script>
</div>
