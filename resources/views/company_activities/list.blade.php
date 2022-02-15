@extends('layouts.app')

@section('content')
    <script src="/js/companies_activities.js"></script>
    <div id="container-content" class="container-content">
        <section class="section">
            <div class="col-sm-12">
                <div class="card" style="margin-top: 10px">
                    <div class="card-header">
                        <h4 class="card-title">Actividades</h4>
                    </div>
                </div>
                <div class="card-body">
                    <table id="activitiesTable" class="table" style="width: 100%">
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
                <div class="col-sm-12 d-flex justify-content-end">
                    <a class="btn btn-primary me-1 mb-1" id="createActivity">Create</a>
                </div>
            </div>
        </section>
    </div>
@endsection
