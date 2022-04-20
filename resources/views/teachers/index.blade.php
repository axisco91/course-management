@extends('layouts.app')
@section('content')
    <script src="{{ url('js/teachers.js') }}"></script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @livewire('teachers')
            </div>
        </div>
    </div>
@endsection
