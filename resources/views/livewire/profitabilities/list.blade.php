<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Rentabilidad</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('profitabilities.info')
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
        </div>
    </div>
    <hr class="my-0" />
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <td>#</td>
                    <th>Curso</th>
                    <th>Grupo</th>
                    <th>Empresa</th>
                    <th>Alumno</th>
                    <th>Beneficios</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profitabilities as $row)
                <tr>
                    <td data-bs-toggle="modal" data-bs-target="#profitabilitiesTabModal" wire:click="general({{$row->id}})">{{ $loop->iteration }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#profitabilitiesTabModal" wire:click="general({{$row->id}})">{{ $row->course_name }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#profitabilitiesTabModal" wire:click="general({{$row->id}})">{{ $row->course_group }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#profitabilitiesTabModal" wire:click="general({{$row->id}})">{{ $row->company_name }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#profitabilitiesTabModal" wire:click="general({{$row->id}})">{{ $row->student_name }} {{$row->student_surname}}</td>
                    <td data-bs-toggle="modal" data-bs-target="#profitabilitiesTabModal" wire:click="general({{$row->id}})">{{ $row->benefits }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="{{url('/profitabilities/edit/'.$row->id)}}" class="dropdown-item"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $profitabilities->links() }}
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
        })
    </script>
</div>
