<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Cursos</h4>
        @if (session()->has('message'))
            <input hidden id="toastr" data-type="success" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('registrations.index')
        @include('livewire.courses.tracings')
        @include('livewire.courses.chores')
        @include('livewire.courses.updateChore')
    </div>
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <a class="btn btn-sm btn-info" href="{{url('/courses/create')}}">
                    <i data-feather="plus-circle" class="me-50"></i>   Añadir curso
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
                    <th>Grupo</th>
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
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->group }}</td>
                    <td>{{ $row->course_type }}</td>
                    <td>{{ $row->teacher_name }} {{$row->teacher_surname}}</td>
                    <td>{{ $row->beginning }}</td>
                    <td>{{ $row->end }}</td>
                    <td style="text-align: center">{{$row->registrations->count()}}</td>
                    <td>{{ $row->course_status }}</td>
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
                            </div>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
        {{ $courses->links() }}
        </div>
    </div>
</div>
