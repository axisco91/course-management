@extends('layout')
@section('content')
    <script src="/js/centers.js"></script>
    <div id="container-content" class="container-content">
        <div class="col-sm-12">

        </div>
        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="name">Nombre</label>
                <input name="name" type="text" id="name" class="form-control" required>
            </div>
            <div class="col-sm-4 form-group">
                <label for="address">Dirrección</label>
                <input name="address" id="address" type="text" class="form-control">
            </div>
            <div class="col-sm-4 form-group">
                <label for="email">Correo</label>
                <input name="email" id="email" type="email" class="form-control">
            </div>
            <div class="col-sm-4 form-group">
                <label for="telephone">Telefono</label>
                <input name="telephone" id="telephone" type="text" class="form-control">
            </div>
        </div>
        <div class="col-sm-12">
            <a  class="btn btn-success" id="saveCenter">Create</a>
        </div>
    </div>
@endsection
