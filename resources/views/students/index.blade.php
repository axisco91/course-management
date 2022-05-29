@extends('layouts/contentLayoutMaster')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" href="{{asset('app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
@endsection

@section('content')
    <section id="advanced-search-datatable">
        <div class="row">
            <div class="col-12">
                @livewire('students')
            </div>
        </div>
    </section>
@endsection
@section('vendor-script')
    <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/polyfill.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset('app-assets/js/scripts/extensions/ext-component-toastr.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/extensions/ext-component-sweet-alerts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
