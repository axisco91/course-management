@extends('layouts/contentLayoutMaster')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/toastr.min.css') }}">>
@endsection
@section('page-style')
    <!-- Page css files -->
   <link rel="stylesheet" href="{{ asset('css/base/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/core/menu/menu-types/vertical-menu.css') }}" />
@endsection

@section('content')
    <section id="advanced-search-datatable">
        <div class="row">
            <div class="col-12">
                @livewire('incidence-types')
            </div>
        </div>
    </section>
@endsection
@section('vendor-script')
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset('js/scripts/extensions/ext-component-toastr.js') }}"></script>
@endsection
