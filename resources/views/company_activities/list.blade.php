@extends('layout')

@section('content')
    <script src="/js/companies_activities.js"></script>
    <div id="container-content" class="container-content">
        <div class="col-sm-12">
            <h1>Actividades</h1>
        </div>
        <div class="col-sm-12 table-responsive">
            <table id="activitiesTable" class="table table-striped table-bordered" style="width: 100%">
                <thead>
                    <tr class="">
                        <th>Actividad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="col-sm-12">
            <a class="btn btn-primary" id="createActivity">Create</a>
        </div>
    </div>
@endsection
