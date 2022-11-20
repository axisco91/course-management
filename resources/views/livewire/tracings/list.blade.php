<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Seguimientos</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('livewire.tracings.info')
        @include('livewire.tracings.update-modal')
    </div>
    <!--Search Form -->
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-3">
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
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div wire:ignore>
                    <label class="form-label" for="student_search">Alumno</label>
                    <select wire:model.lazy="student_search" class="form-control select2" id="student_search">
                        <option value="-1">Todos los alumnos</option>
                        @foreach($students as $student)
                            <option value="{{$student['id']}}">{{$student['name']}} {{$student['surname']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div wire:ignore>
                    <label class="form-label" for="status_search">Estado</label>
                    <select wire:model.lazy="status_search" class="form-control select2" id="status_search">
                        <option value="-1">Todas los estados</option>
                        @foreach($course_statuses as $status)
                            <option value="{{$status['id']}}">{{$status['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div wire:ignore>
                    <label class="form-label" for="beginning_search">Desde</label>
                    <input wire:model.lazy="beginning_search" wire:change="restartPage()" type="date" class="form-control" id="beginning_search">
                </div>
            </div>
            <div class="col-md-3">
                <div wire:ignore>
                    <label class="form-label" for="end_search">Hasta</label>
                    <input wire:model.lazy="end_search" wire:change="restartPage()" type="date" class="form-control" id="end_search">
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
                <th>Curso</th>
                <th>Empresa</th>
                <th>Alumno</th>
                <th>Estado</th>
                <th>Horas Realizadas</th>
                <th>Actividades Realizadas</th>
                <th>Unidades Realizadas</th>
                <th>Fecha Seguimiento</th>
                <th>Test Final</th>
                <th>Cuestionario</th>
                <th>Bienvenida</th>
                <th>Mensaje 25%</th>
                <th>Mensaje 50%</th>
                <th>Mensaje 75%</th>
                <th>Finalizacion</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tracings as $row)
                <tr>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $loop->iteration }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ str_replace( ' -', '/'.$row->course_group.' -', $row->course) }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->company }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->student_name}} {{$row->student_surname}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->status }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->performed_hours }} / {{$row->total_hours}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->performed_activities }} / {{$row->number_activities}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->performed_units }} / {{$row->number_units}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->follow_up_date ? Carbon\Carbon::parse($row->follow_up_date)->format('d/m/Y') : ''}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->final_test }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->questionnaire }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->welcome_message == 1 ? 'Si' : 'No' }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->quarter_message == 1 ? 'Si' : 'No' }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->half_message == 1 ? 'Si' : 'No' }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->three_quarters_message == 1 ? 'Si' : 'No' }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#tracingsTabModal" wire:click="general({{$row->id}})">{{ $row->final_message == 1 ? 'Si' : 'No' }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                              <a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                <a href="{{url('/tracing_communications/create/'.$row->id)}}" class="dropdown-item"><i class="fa-regular fa-pen-to-square"></i> Crear Comunicación</a>
                              <a class="dropdown-item eliminar" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $tracings->links() }}
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
        Livewire.on('toastr', type => {
            if (type == 'success'){
                toastr['success']($('#success-toast').val(), {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    timeOut: 2000,
                });
            } else{
                toastr['warning']($('#success-toast').val(), {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    timeOut: 2000,
                });
            }
        })
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
                    text: "Eliminaras el seguimiento!",
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
                Livewire.emit('restartPage')
            })
            $('#training_action_id').on('change', function(){
            @this.set(this.id, this.value)
            @this.setName()
            })
        })
    </script>
        @endsection
</div>
