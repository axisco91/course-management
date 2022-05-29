<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- BEGIN: Vendor JS-->
<script src="{{asset('app-assets/vendors/js/vendors.min.js')}}"></script>
<!-- BEGIN Vendor JS-->
<!-- BEGIN: Page Vendor JS-->
<script src="{{asset('app-assets/vendors/js/ui/jquery.sticky.js')}}"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{asset('app-assets/js/core/app-menu.js')}}"></script>
<script src="{{asset('app-assets/js/core/app.js')}}"></script>

<!-- custome scripts file for user -->
<script src="{{ asset('js/general.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

@if($configData['blankPage'] === false)
<script src="{{ asset('app-assets/js/scripts/customizer.js') }}"></script>
@endif

@livewireScripts
<script type="text/javascript">
    window.livewire.on('closeModal', () => {
        $('#createDataModal').modal('hide');
    });
</script>
<!-- END: Theme JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
