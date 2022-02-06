@extends('layout')
@section('content')
    <script src="/js/teachers.js"></script>
    <div id="container-content" class="container-content">
        <div class="col-sm-12">

        </div>
        <div class="row">
           <div class="col-sm-4 form-group">
               <label for="name">Nombre</label>
               <input name="name" type="text" id="name" class="form-control" required>
           </div>
            <div class="col-sm-4 form-group">
                <label for="surname">Apellidos</label>
                <input name="surname"type="text" id="surname" class="form-control" required>
            </div>
            <div class="col-sm-4 form-group">
                <label for="dni">DNI</label>
                <input name="dni" id="dni" type="text" class="form-control">
            </div>
            <div class="col-sm-4 form-group">
                <label for="email">Correo</label>
                <input name="email" id="email" type="email" class="form-control">
            </div>
            <div class="col-sm-4 form-group">
                <label for="telephone">Telefono</label>
                <input name="telephone" id="telephone" type="text" class="form-control">
            </div>
            <div class="col-sm-4 form-group">
                <label for="user">Usuario</label>
                <input name="user" id="user" type="text" class="form-control">
            </div>
            <div class="col-sm-4 form-group">
                <label for="password">Contraseña</label>
                <input name="password" id="password" type="text" class="form-control">
            </div>
            <div class="col-sm-4 form-group">
                <label for="observations">Observaciones</label>
                <input name="observations" id="observations" type="text" class="form-control">
            </div>
        </div>
        <div class="col-sm-12">
            <a  class="btn btn-success" id="saveTeacher">Create</a>
        </div>
    </div>
@endsection
