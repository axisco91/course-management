@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @livewire('teachers-update', ['id' => $id])
            </div>
        </div>
    </div>
@endsection
