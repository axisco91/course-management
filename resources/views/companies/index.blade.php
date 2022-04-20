@extends('layouts.app')
@section('content')
    <script src="{{ url('js/companies.js') }}"></script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @livewire('companies')

            </div>
        </div>
    </div>
@endsection
