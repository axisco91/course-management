@extends('layout')

@section('content')
    <script src="/js/company_types.js"></script>
    <div id="container-content" class="container-content">
        <div class="col-sm-12">
            <h1>Types</h1>
        </div>
        <div class="col-sm-12 table-responsive">
            <table id="typesTable" class="table table-striped table-bordered" style="width: 100%">
                <thead>
                    <tr class="">
                        <th>Tipos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="col-sm-12">
            <a class="btn btn-primary" id="createType">Create</a>
        </div>
    </div>
@endsection
