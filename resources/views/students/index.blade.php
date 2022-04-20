@extends('layouts.app')
@section('content')
    <script src="{{ url('js/students.js') }}"></script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @livewire('students')
            </div>
        </div>
    </div>
@endsection
