<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Tareas</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('chores.info')
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
                        <option value="-1">Todas los alumnos</option>
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
                    <input wire:model.lazy="beginning_search" type="date" class="form-control" id="beginning_search">
                </div>
            </div>
            <div class="col-md-3">
                <div wire:ignore>
                    <label class="form-label" for="end_search">Hasta</label>
                    <input wire:model.lazy="end_search" type="date" class="form-control" id="end_search">
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
                <th>Ficha Adhesión</th>
                <th>Propuesta Económica</th>
                <th>Ficha Alumno</th>
                <th>Guia Bienvenida</th>
                <th>Matriculación</th>
                <th>Diploma</th>
                <th>Comunicación Inicio</th>
                <th>Comunicación Cierre</th>
                <th>Facturado</th>
                <th>Bonificacion Enviada</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($chores as $row)
                <tr>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})">{{ $loop->iteration }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})">{{ $row->course }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})">{{ $row->company }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})">{{ $row->student }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->membership_tab_status == 0 ?'warning' : ($row->membership_tab_status == 1 ? 'info' : ($row->membership_tab_status == 2 ? 'success' : 'danger' ))}} me-1">{{$row->membership_tab_status == 0 ? 'Pendiente' : ($row->membership_tab_status == 1 ? 'Enviado' : ($row->membership_tab_status == 2 ? 'Recibido' : 'No procede'))}}</span></td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->economic_proposal_status == 0 ?'warning' : ($row->economic_proposal_status == 1 ? 'info' : 'success')}} me-1">{{$row->economic_proposal_status == 0 ? 'Pendiente' : ($row->economic_proposal_status == 1 ? 'Enviado' : 'Recibido')}}</span></td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->student_tab_status == 0 ?'warning' : ($row->student_tab_status == 1 ? 'info' : 'success')}} me-1">{{$row->student_tab_status == 0 ? 'Pendiente' : ($row->student_tab_status == 1 ? 'Enviado' : 'Recibido')}}</span></td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->welcome_guid_status == 0 ?'warning' : ($row->welcome_guid_status == 1 ? 'info' : 'success')}} me-1">{{$row->welcome_guid_status == 0 ? 'Pendiente' : ($row->welcome_guid_status == 1 ? 'Enviado' : 'Recibido')}}</span></td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->registration_status == 0 ?'warning' : 'success'}} me-1">{{$row->registration_status == 0 ? 'Pendiente' : 'Realizada'}}</span></td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->diploma_status == 0 ?'warning' : ($row->diploma_status == 1 ? 'success' : 'danger')}} me-1">{{$row->diploma_status == 0 ? 'Pendiente' : ($row->diploma_status == 1 ? 'Enviada' : 'No procede')}}</span></td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->start_communication_status == 0 ?'warning' : ($row->start_communication_status == 1 ? 'success' : 'danger')}} me-1">{{$row->start_communication_status == 0 ? 'Pendiente' : ($row->start_communication_status == 1 ? 'Realizada' : 'No procede')}}</span></td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->close_communication_status == 0 ?'warning' : ($row->close_communication_status == 1 ? 'success' : 'danger')}} me-1">{{$row->close_communication_status == 0 ? 'Pendiente' : ($row->close_communication_status == 1 ? 'Realizada' : 'No procede')}}</span></td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->invoiced_status == 0 ?'warning' : ($row->invoiced_status == 1 ? 'success' : 'danger')}} me-1">{{$row->invoiced_status == 0 ? 'Pendiente' : ($row->invoiced_status == 1 ? 'Realizada' : 'No procede')}}</span></td>
                    <td data-bs-toggle="modal" data-bs-target="#choresTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->bonus_sent_status == 0 ?'warning' : ($row->bonus_sent_status == 1 ? 'success' : 'danger')}} me-1">{{$row->bonus_sent_status == 0 ? 'Pendiente' : ($row->bonus_sent_status == 1 ? 'Realizada' : 'No procede')}}</span></td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item edit" href="{{url('/chores/edit/'.$row->id)}}"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $chores->links() }}
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
            initializeSelect2()
            $('.select2').on('change', function(){
            @this.set(this.id, this.value)
            })
            $('#training_action_id').on('change', function(){
            @this.set(this.id, this.value)
            @this.setName()
            })
        })
    </script>
</div>
