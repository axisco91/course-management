@extends('layout')
@section('content')
    <script src="/js/centers.js"></script>
    <div id="container-content" class="container-content">
        <input name="id" type="text" id="center_id" class="form-control" value="{{$center['id']}}" hidden>
        <div class="col-sm-12">

        </div>
        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="name">Nombre</label>
                <input name="name" type="text" id="name" class="form-control" required value="{{$center['name']}}">
            </div>
            <div class="col-sm-4 form-group">
                <label for="address">Dirrección</label>
                <input name="address" id="address" type="text" class="form-control" value="{{$center['address']}}">
            </div>
            <div class="col-sm-4 form-group">
                <label for="email">Correo</label>
                <input name="email" id="email" type="email" class="form-control" value="{{$center['email']}}">
            </div>
            <div class="col-sm-4 form-group">
                <label for="telephone">Telefono</label>
                <input name="telephone" id="telephone" type="text" class="form-control" value="{{$center['telephone']}}">
            </div>
        </div>
        <div class="col-sm-12">
            <a  class="btn btn-success" id="updateCenter">Edit</a>
        </div>
    </div>
@endsection
