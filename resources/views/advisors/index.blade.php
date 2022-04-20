@extends('layouts.app')
@section('content')
    <script src="{{ url('js/advisors.js') }}"></script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @livewire('advisors')
            </div>
        </div>
    </div>
@endsection
