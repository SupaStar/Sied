    {{-- Vendor Scripts --}}
        <script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>

        <script src="{{ asset('js/scripts/ui/loader.js') }}?{{rand()}}"></script>
        <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>

        <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>

        <script src="{{ asset(mix('js/scripts/forms/select/form-select2.js')) }}"></script>
        <link type="text/css" rel="stylesheet" href="{{asset('vendors/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}" />

        <script type="text/javascript" src="{{asset('vendors/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
        <script src="{{asset('vendors/clockpicker/js/jquery-clockpicker.min.js')}}"></script>
        @yield('vendor-script')
        {{-- Theme Scripts --}}
        <script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
        <script src="{{ asset(mix('js/core/app.js')) }}"></script>
        <script src="{{ asset(mix('js/scripts/components.js')) }}"></script>
@if($configData['blankPage'] == false)
        <script src="{{ asset(mix('js/scripts/footer.js')) }}"></script>
@endif
        {{-- page script --}}
        @yield('page-script')
