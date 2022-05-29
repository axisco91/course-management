@extends('layouts/contentLayoutMaster')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('app-assets/css/plugins/forms/form-validation.css')}}">
@endsection

@section('content')
    <section class="bs-validation" id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Crear Acción Formativa
                    </div>
                </div>
                @livewire('training-actions-create')
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('app-assets/js/scripts/forms/form-validation.js') }}"></script>
@endsection
