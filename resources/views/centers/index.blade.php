@extends('layouts.app')
@section('content')
    <script src="{{ url('js/centers.js') }}"></script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @livewire('centers')
            </div>
        </div>
    </div>
@endsection
