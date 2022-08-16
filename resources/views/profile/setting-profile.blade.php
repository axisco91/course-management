@extends('layouts/contentLayoutMaster')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{asset('app-assets/css/plugins/forms/form-validation.css')}}">
@endsection

@section('content')
    <section class="bs-validation" id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Perfil
                    </div>
                </div>
                @livewire('profile-setting')
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('app-assets/js/scripts/pages/page-account-settings-account.js') }}"></script>
@endsection
