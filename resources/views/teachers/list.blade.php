@extends('layout')

@section('content')
    <script src="/js/teachers.js"></script>
    <div id="container-content" class="container-content">
        <div class="col-sm-12">
            <h1>Docentes</h1>
        </div>
        <div class="col-sm-12 table-responsive">
            <table id="teachersTable" class="table table-striped table-bordered" style="width: 100%">
                <thead>
                    <tr class="">
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>Correo</th>
                        <th>telephono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="col-sm-12">
            <a id="createTeacher" href="{{url('/teachers/create')}}">Create</a>
        </div>
    </div>
@endsection
