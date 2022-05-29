<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Seguimientos</h4>
        @if (session()->has('message'))
            <input hidden id="toastr" data-type="success" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('livewire.tracings.info')
        @include('livewire.tracings.update')
    </div>
    <!--Search Form -->
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <div wire:ignore>
                    <label class="form-label" for="course_search"></label>
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
                    <label class="form-label" for="company_search"></label>
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
                    <label class="form-label" for="student_search"></label>
                    <select wire:model.lazy="student_search" class="form-control select2" id="student_search">
                        <option value="-1">Todas las empresas</option>
                        @foreach($students as $student)
                            <option value="{{$student['id']}}">{{$student['name']}}</option>
                        @endforeach
                    </select>
                </div>
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
                <th>Actividades Realizadas</th>
                <th>Horas Realizadas</th>
                <th>Unidades Realizadas</th>
                <th>Fecha Seguimiento</th>
                <th>Test Final</th>
                <th>Cuestionario</th>
                <th>Bienvenida</th>
                <th>Mensaje 25%</th>
                <th>Mensaje 50%</th>
                <th>Mensaje 75%</th>
                <th>Finalizacion</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tracings as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a data-bs-toggle="modal" data-bs-target="#tracingsTabModal" class="dropdown-item" wire:click="general({{$row->id}})">{{ $row->course }}</a></td>
                    <td>{{ $row->company }}</td>
                    <td>{{ $row->student }}</td>
                    <td>{{ $row->performed_activities }}</td>
                    <td>{{ $row->performed_hours }}</td>
                    <td>{{ $row->performed_units }}</td>
                    <td>{{ $row->follow_up_date }}</td>
                    <td>{{ $row->final_test }}</td>
                    <td>{{ $row->questionnaire }}</td>
                    <td>{{ $row->welcome_message == 1 ? 'Si' : 'No' }}</td>
                    <td>{{ $row->quarter_message == 1 ? 'Si' : 'No' }}</td>
                    <td>{{ $row->half_message == 1 ? 'Si' : 'No' }}</td>
                    <td>{{ $row->three_quarters_message == 1 ? 'Si' : 'No' }}</td>
                    <td>{{ $row->final_message == 1 ? 'Si' : 'No' }}</td>
                    <td>{{ $row->observation }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                              <a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $tracings->links() }}
    </div>
    <script>
        document.addEventListener('livewire:load', function(){
            $('.select2').select2()
            $('.select2').on('change', function(){
            @this.set(this.id, this.value)
            })
            $('.select2').on('change', function(){
            @this.set(this.id, this.value)
            })
            $('#create_training_action_id').on('change', function(){
            @this.set(this.id, this.value)
            @this.setName()
            })
        })
    </script>
</div>
