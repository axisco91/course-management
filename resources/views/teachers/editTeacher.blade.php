@extends('layout')
@section('content')
    <script src="/js/teachers.js"></script>
    <div id="container-content" class="container-content">
        <input name="id" type="text" id="teacher_id" class="form-control" value="{{$teacher['id']}}" hidden>
        <div class="col-sm-12">
        </div>
        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="name">Nombre</label>
                <input name="name" type="text" id="name" class="form-control" value="{{$teacher['name']}}" required>
            </div>
            <div class="col-sm-4 form-group">
                <label for="surname">Apellidos</label>
                <input name="surname"type="text" id="surname" class="form-control" value="{{$teacher['surname']}}" required>
            </div>
            <div class="col-sm-4 form-group">
                <label for="dni">DNI</label>
                <input name="dni" id="dni" type="text" class="form-control" value="{{$teacher['dni']}}">
            </div>
            <div class="col-sm-4 form-group">
                <label for="email">Correo</label>
                <input name="email" id="email" type="email" class="form-control" value="{{$teacher['email']}}">
            </div>
            <div class="col-sm-4 form-group">
                <label for="telephone">Telefono</label>
                <input name="telephone" id="telephone" type="text" class="form-control" value="{{$teacher['telephone']}}">
            </div>
            <div class="col-sm-4 form-group">
                <label for="user">Usuario</label>
                <input name="user" id="user" type="text" class="form-control" value="{{$teacher['user']}}">
            </div>
            <div class="col-sm-4 form-group">
                <label for="password">Contraseña</label>
                <input name="password" id="password" type="text" class="form-control" value="{{$teacher['password']}}">
            </div>
            <div class="col-sm-4 form-group">
                <label for="observations">Observaciones</label>
                <input name="observations" id="observations" type="text" class="form-control" value="{{$teacher['observations']}}">
            </div>
        </div>
        <div class="col-sm-12">
            <a class="btn btn-success" id="updateTeacher">Edit</a>
        </div>
    </div>
@endsection
