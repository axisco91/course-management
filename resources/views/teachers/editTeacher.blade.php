@extends('layouts.app')
@section('content')
    <script src="/js/teachers.js"></script>
    <div id="container-content" class="container-content">
        <input name="id" type="text" id="teacher_id" class="form-control" value="{{$teacher['id']}}" hidden>
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card" style="margin-top: 10px">
                        <div class="card-header">
                            <h4 class="card-title">Editar Docente</h4>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Nombre</label>
                                        <input name="name" type="text" id="name" class="form-control round" value="{{$teacher['name']}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="surname">Apellidos</label>
                                        <input name="surname"type="text" id="surname" class="form-control round" value="{{$teacher['surname']}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="dni">DNI</label>
                                        <input name="dni" id="dni" type="text" class="form-control round" value="{{$teacher['dni']}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Correo</label>
                                        <input name="email" id="email" type="email" class="form-control round" value="{{$teacher['email']}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="telephone">Telefono</label>
                                        <input name="telephone" id="telephone" type="text" class="form-control round" value="{{$teacher['telephone']}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="user">Usuario</label>
                                        <input name="user" id="user" type="text" class="form-control round" value="{{$teacher['user']}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="password">Contraseña</label>
                                        <input name="password" id="password" type="text" class="form-control round" value="{{$teacher['password']}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="observations">Observaciones</label>
                                        <input name="observations" id="observations" type="text" class="form-control round" value="{{$teacher['observations']}}">
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <a class="btn btn-success me-1 mb-1" id="updateTeacher">Edit</a>
                                    <a  class="btn btn-primary me-1 mb-1" href="{{ url('/teachers') }}" >Volver</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
