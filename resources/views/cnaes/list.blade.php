@extends('layouts.app')

@section('content')
    <script src="/js/cnaes.js"></script>
    <div id="container-content" class="container-content">
        <section class="section">
            <div class="col-sm-12">
                <div class="card" style="margin-top: 10px">
                    <div class="card-header">
                        <h4 class="card-title">Cnaes</h4>
                    </div>
                    <div class="card-body">
                        <table id="cnaesTable" class="table" style="width: 100%">
                            <thead>
                            <tr>
                                <th>Cnae</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 d-flex justify-content-end">
                        <a class="btn btn-primary" id="createCnae">Create</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
