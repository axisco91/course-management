<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Rentabilidad</h4>
        @if (session()->has('message'))
            <input hidden id="toastr" data-type="success" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
    </div>
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">

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
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profitabilities as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->course_name }}</td>
                    <td>{{ $row->company_name }}</td>
                    <td>{{ $row->student_name }} {{$row->student_surname}}</td>
                    <td>{{ $row->total }}</td>
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
                @endforeach
            </tbody>
        </table>
        {{ $profitabilities->links() }}
        </div>
    </div>
</div>
