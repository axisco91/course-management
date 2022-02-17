@extends('layouts.app')
@section('content')
    <script src="/js/centers.js"></script>
    <div id="container-content" class="container-content">
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card" style="margin-top: 10px">
                        <div class="card-header">
                            <h4 class="card-title">Crear Centro</h4>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Nombre</label>
                                        <input name="name" type="text" id="name" class="form-control round" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="address">Dirrección</label>
                                        <input name="address" id="address" type="text" class="form-control round">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Correo</label>
                                        <input name="email" id="email" type="email" class="form-control round">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="telephone">Telefono</label>
                                        <input name="telephone" id="telephone" type="text" class="form-control round">
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <a  class="btn btn-success me-1 mb-1" id="saveCenter">Crear</a>
                                    <a  class="btn btn-primary me-1 mb-1" href="{{ url('/centers') }}" >Volver</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
