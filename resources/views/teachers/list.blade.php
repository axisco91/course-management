@extends('layouts.app')

@section('content')
    <script src="/js/teachers.js"></script>
    <div id="container-content" class="container-content">
        <section class="section">
            <div class="col-sm-12">
                <div class="card" style="margin-top: 10px">
                    <div class="card-header">
                        <h4 class="card-title">Docentes</h4>
                    </div>
                    <div class="card-body">
                        <table id="teachersTable" class="table" style="width: 100%">
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
                    <div class="col-sm-12 d-flex justify-content-end">
                        <a class="btn btn-primary me-1 mb-1" id="createTeacher" href="{{url('/teachers/create')}}">Create</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
