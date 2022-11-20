<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Contratos de Formación</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('training-contracts.info')
    </div>
    <!--Search Form -->
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4 col-12">
                <label class="form-label" for="search_company_id">Empresa</label>
                <div wire:ignore>
                    <select wire:model.lazy="search_company_id" class="form-select select2" id="search_company_id">
                        <option value="">Selección una empresa</option>
                        @foreach($companies as $company)
                            <option value="{{$company['id']}}">{{$company['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label" for="search_student_id">Alumno</label>
                <div wire:ignore>
                    <select wire:model.lazy="search_student_id" class="form-select select2" id="search_student_id">
                        <option value="">Selección un alumno</option>
                        @foreach($students as $student)
                            <option value="{{$student['id']}}">{{$student['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label" for="search_training_contract_status_id">Estado de Contrato</label>
                <div wire:ignore>
                    <select wire:model.lazy="search_training_contract_status_id" class="form-select select2" id="search_training_contract_status_id">
                        <option value="">Selección un estado de contrato</option>
                        @foreach($training_contract_statuses as $training_contract_status)
                            <option value="{{$training_contract_status['id']}}">{{$training_contract_status['name']}}</option>
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
                <a wire:ignore class="btn btn-sm btn-info" href="{{url('/training_contracts/create')}}">
                    <i class="fa fa-plus" class="me-50"></i> Añadir Contrato Formativo
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
                    <th>Número CFA</th>
                    <th>Empresa</th>
                    <th>Alumno</th>
                    <th>Estado</th>
                    <th>Proveedor</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trainingContracts as $row)
                <tr>
                    <th data-bs-toggle="modal" data-bs-target="#trainingContractTabModal" wire:click="general({{$row->id}})">{{ $row->number_cfa }}</th>
                    <td data-bs-toggle="modal" data-bs-target="#trainingContractTabModal" wire:click="general({{$row->id}})">{{ $row->company_name }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingContractTabModal" wire:click="general({{$row->id}})">{{ $row->student_name }} {{$row->student_surname}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingContractTabModal" wire:click="general({{$row->id}})">{{ $row->training_contract_status }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingContractTabModal" wire:click="general({{$row->id}})">{{ $row->provider }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingContractTabModal" wire:click="general({{$row->id}})">{{ $row->beginning }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingContractTabModal" wire:click="general({{$row->id}})">{{ $row->end }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                            <!--<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>-->
                                <a href="{{url('/training_contracts/edit/'.$row->id)}}" class="dropdown-item edit"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                @if (!$row->used)
                                    <a class="dropdown-item eliminar" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                                @endif
                            </div>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
        {{ $trainingContracts->links() }}
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
                    text: "Eliminaras al contrato de formación!",
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
</div>
