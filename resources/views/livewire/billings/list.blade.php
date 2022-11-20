<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Facturas</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('billings.info')
    </div>
    <!--Search Form -->
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <div wire:ignore>
                    <label class="form-label" for="course_search">Curso</label>
                    <select wire:model.lazy="course_search" class="form-control select2" id="course_search">
                        <option value="-1">Todos los cursos</option>
                        @foreach($courses as $course)
                            <option value="{{$course['id']}}">{{$course['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div wire:ignore>
                    <label class="form-label" for="company_search">Empresa</label>
                    <select wire:model.lazy="company_search" class="form-control select2" id="company_search">
                        <option value="-1">Todas las empresas</option>
                        @foreach($companies as $company)
                            <option value="{{$company['id']}}">{{$company['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div wire:ignore>
                    <label class="form-label" for="student_search">Alumno</label>
                    <select wire:model.lazy="student_search" class="form-control select2" id="student_search">
                        <option value="-1">Todas los alumnos</option>
                        @foreach($students as $student)
                            <option value="{{$student['id']}}">{{$student['name']}} {{$student['surname']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div wire:ignore>
                    <label class="form-label" for="is_bonus_search">Tipo</label>
                    <select wire:model.lazy="is_bonus_search" class="form-control select2" id="is_bonus_search">
                        <option value="-1">Seleccione un tipo</option>
                        <option value="0">No bonificada</option>
                        <option value="1">Bonificada</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div wire:ignore>
                    <label class="form-label" for="status_search">Estado</label>
                    <select wire:model.lazy="status_search" class="form-control select2" id="status_search">
                        <option value="-1">Seleccione un estado</option>
                        @foreach($course_statuses as $course_status)
                        <option value="{{$course_status['id']}}">{{$course_status['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
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
                    <th>Nº Factura</th>
                    <th>Curso</th>
                    <th>Año</th>
                    <th>Tipo</th>
                    <th>Empresa</th>
                    <th>Asesoria</th>
                    <th>Numero Alumnos</th>
                    <th>Cobrado</th>
                    <th>Factura</th>
                    <th>Acciónes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($billings as $row)
                <tr>
                    <td data-bs-toggle="modal" data-bs-target="#billingsTabModal" wire:click="general({{$row->id}})">{{ $loop->iteration }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#billingsTabModal" wire:click="general({{$row->id}})">{{ $row->billing_number }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#billingsTabModal" wire:click="general({{$row->id}})">{{ $row->training_action}}/{{$row->group}} {{$row->course }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#billingsTabModal" wire:click="general({{$row->id}})">{{ $row->beginning ? Carbon\Carbon::parse($row->beginning)->year : ''}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#billingsTabModal" wire:click="general({{$row->id}})">{{ $row->is_bonus == 0 ? 'No bonificada' : 'Bonificada' }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#billingsTabModal" wire:click="general({{$row->id}})">{{ $row->company }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#billingsTabModal" wire:click="general({{$row->id}})">{{ $row->asesoria }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#billingsTabModal" wire:click="general({{$row->id}})">{{ $row->number_students }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#billingsTabModal" wire:click="general({{$row->id}})">{{ $row->charged ? 'Si' : 'No' }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#billingsTabModal" wire:click="general({{$row->id}})">{{ $row->billing }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!--<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>-->
                                <a href="{{url('/billings/edit/'.$row->id)}}" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                @if(!$row->used)
                                    <a class="dropdown-item eliminar" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                                @endif
                            </div>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
        {{ $billings->links() }}
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
                    text: "Eliminaras la factura!",
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
    @endsection
</div>
