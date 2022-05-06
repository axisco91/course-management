@extends('layouts.app')
@section('content')
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
    <script src="{{ url('js/courses.js') }}"></script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @livewire('courses-update', ['id' => $id])
            </div>
        </div>
    </div>
@endsection
