@extends('layouts.app')
@section('content')
    <script src="{{ url('js/training-actions.js') }}"></script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @livewire('training-actions')
            </div>
        </div>
    </div>
@endsection
