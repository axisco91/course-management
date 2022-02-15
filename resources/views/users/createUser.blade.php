@extends('layouts.app')
@section('content')
    <script src="/js/users.js"></script>
    <div id="container-content" class="container-content">
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-sm-12">
                    <div class="card" style="margin-top: 10px">
                        <div class="card-header">
                            <h4 class="card-title">Crear Usuario</h4>
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
                                        <label for="surname">Apellidos</label>
                                        <input name="surname"type="text" id="surname" class="form-control round" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="username">Usuario</label>
                                        <input name="username"type="text" id="username" class="form-control round" required>
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
                                        <label for="password">Contraseña</label>
                                        <input name="password" id="password" type="text" class="form-control round">
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <a  class="btn btn-success me-1 mb-1" id="saveUser">Create</a>
                                    <a  class="btn btn-primary me-1 mb-1" href="{{ url('/users') }}" >Volver</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="row">
        </div>
@endsection
