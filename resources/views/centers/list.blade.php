@extends('layout')

@section('content')
    <script src="/js/centers.js"></script>
    <div id="container-content" class="container-content">
        <div class="col-sm-12">
            <h1>Centros</h1>
        </div>
        <div class="col-sm-12 table-responsive">
            <table id="centersTable" class="table table-striped table-bordered" style="width: 100%">
                <thead>
                <tr class="">
                    <th>Nombre</th>
                    <th>Dirección</th>
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
            <a id="createCenter" href="{{url('/centers/create')}}">Create</a>
        </div>
    </div>
@endsection
