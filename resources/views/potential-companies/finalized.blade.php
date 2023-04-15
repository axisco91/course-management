@extends('layouts/basicLayoutMaster')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('app-assets/css/plugins/forms/form-validation.css')}}">
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
@endsection

@section('content')
    <section class="bs-validation" id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Solicitar Alta Empresa
                    </div>
                </div>
                <div style="text-align: center;margin-top: 20px;">
                    Finalizado, muchas gracias en cuanto podemos nos pondremos en contacto contigo.
                </div>
                <div>
                    <a class="btn btn-primary" href="{{url('potential_company')}}">Crear Solicitud</a>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
