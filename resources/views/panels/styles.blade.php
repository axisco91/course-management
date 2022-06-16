<!-- BEGIN: Vendor CSS-->
@if ($configData['direction'] === 'rtl' && isset($configData['direction']))
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors-rtl.min.css')) }}" />
@else
  <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}" />
@endif

@yield('vendor-style')
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap-extended.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/colors.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/components.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/dark-layout.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/bordered-layout.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/semi-dark-layout.css')}}">
<link rel="stylesheet" href="{{ asset('app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('app-assets/vendors/css/animate/animate.min.css') }}">

@php $configData = Helper::applClasses(); @endphp

<!-- BEGIN: Page CSS-->
@if ($configData['mainLayoutType'] === 'horizontal')
  <link rel="stylesheet" href="{{ asset('css/base/core/menu/menu-types/horizontal-menu.css') }}" />
@else
  <link rel="stylesheet" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}" />
@endif

{{-- Page Styles --}}
@yield('page-style')

<!-- BEGIN: Custom CSS-->

@if ($configData['direction'] === 'rtl' && isset($configData['direction']))
  <link rel="stylesheet" href="{{ asset('css-rtl/custom-rtl.css') }}" />
  <link rel="stylesheet" href="{{ asset('css-rtl/style-rtl.css') }}" />
  <link rel="stylesheet" href="{{asset('app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
@else
  {{-- user custom styles --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
@endif
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
@livewireStyles
