<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Cursos</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('registrations.index')
        @include('livewire.courses.tracings')
        @include('livewire.courses.chores')
        @include('livewire.courses.update-chore')
        @include('courses.info')
    </div>
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <label class="form-label">Acción Formativa:</label>
                <input wire:model="search_formative_action" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Acción Formativo" data-column-index="0" />
            </div>
            <div class="col-md-4">
                <label class="form-label">Nombre:</label>
                <input wire:model="search_name" type="text" class="form-control dt-input" data-column="2" placeholder="Nombre" data-column-index="1" />
            </div>
            <div class="col-md-4">
                <label class="form-label">Grupo:</label>
                <input wire:model="search_group" type="text" class="form-control dt-input" data-column="3" placeholder="Grupo" data-column-index="2" />
            </div>
        </div>
        <div class="row g-1">
            <div class="col-md-4">
                <div wire:ignore>
                    <label class="form-label">Tipo:</label>
                    <select wire:model.lazy="search_type" class="form-control select2" id="search_type">
                        <option value="">Seleccione un tipo</option>
                        @foreach($course_types as $type)
                            <option value="{{$type['id']}}">{{$type['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div wire:ignore>
                    <label class="form-label">Estado:</label>
                    <select wire:model.lazy="search_status" class="form-control select2" id="search_status">
                        <option value="">Seleccione un estado</option>
                        @foreach($course_statuses as $status)
                            <option value="{{$status['id']}}">{{$status['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div wire:ignore>
                    <label class="form-label">Empresa:</label>
                    <select wire:model.lazy="search_company" class="form-control select2" id="search_company">
                        <option value="">Seleccione una empresa</option>
                        @foreach($companies as $company)
                            <option value="{{$company['id']}}">{{$company['name']}}</option>
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

            </div>
            <div class="col-md-4">
                <a wire:ignore class="btn btn-sm btn-info" href="{{url('/courses/create')}}">
                    <i data-feather="plus-circle" class="me-50"></i> Añadir curso
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
                    <td>#</td>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Docente</th>
                    <th>Fecha inicio</th>
                    <th>Fecha fin</th>
                    <th>Matriculados</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $row)
                <tr>
                    <td data-bs-toggle="modal" data-bs-target="#coursesTabModal" wire:click="general({{$row->id}})">{{ $loop->iteration }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#coursesTabModal" wire:click="general({{$row->id}})">{{ str_replace( ' -', '/'.$row->group.' -', $row->name) }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#coursesTabModal" wire:click="general({{$row->id}})">{{ $row->course_type }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#coursesTabModal" wire:click="general({{$row->id}})">{{ $row->teacher_name }} {{$row->teacher_surname}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#coursesTabModal" wire:click="general({{$row->id}})">{{ $row->beginning }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#coursesTabModal" wire:click="general({{$row->id}})">{{ $row->end }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#coursesTabModal" wire:click="general({{$row->id}})" style="text-align: center">{{$row->registrations->count()}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#coursesTabModal" wire:click="general({{$row->id}})">{{ $row->course_status }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item edit" href="{{url('/courses/edit/'.$row->id)}}"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                               <!-- <a data-bs-toggle="modal" class="dropdown-item" data-bs-target="#updateModal" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar</a>-->
                            <!--  <a data-bs-toggle="modal" data-bs-target="#registrationsModal" class="dropdown-item" wire:click="registrations({{$row->id}})"><i class="fas fa-chalkboard-teacher"></i> Matriculaciones </a>-->
                                <a data-bs-toggle="modal" data-bs-target="#registrationsModal" class="dropdown-item" wire:click="registrations({{$row->id}})"><i class="fas fa-chalkboard-teacher"></i> Matriculaciones </a>
                                <a class="dropdown-item eliminar" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                            </div>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
        {{ $courses->links() }}
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
                    text: "Eliminaras al curso!",
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
