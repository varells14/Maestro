<!DOCTYPE html>
<html lang="en">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>
        {{ config('app.name') }}
    </title>
    <meta name="theme-color" content="#ffffff">
    <link rel="shortcut icon" href="{{ asset('assets/images/icon-logo-min.png') }}">
    <link rel="icon" href="{{ asset('assets/images/icon-logo-min.png') }}">

    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/vendor/fontawesome6/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/vendor/fontawesome6/css/regular.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/vendor/fontawesome6/css/solid.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/vendor/fontawesome6/css/duotone.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/vendor/fontawesome6/css/v4-font-face.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/vendor/fontawesome6/css/v4-shims.css') }}"/>
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/vendor/dataTables.bootstrap5.css') }}"/>
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/vendor/select2.min.css') }}" />
	<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/vendor/snackbar.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    {{-- @stack('before-styles') --}}
    {{-- @vite('resources/sass/app.scss') --}}
    {{-- @stack('after-styles') --}}
    <link href="{{ asset('css/cui/coreui-pro.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/cui/coreui-utilities.min.css') }}" rel="stylesheet"> --}}
    @yield('extra_css')
</head>

<body>
    <div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
        <div class="sidebar-header border-bottom">
            <div class="sidebar-brand">
                <img  class="sidebar-brand-full" src="{{ asset('assets/images/logo-min.png')}}" width="210" height="28" alt="LOGISTEED"/>
                <img  class="sidebar-brand-narrow" src="{{ asset('assets/images/icon-logo-min.png')}}" width="35" height="28" alt="LOGISTEED"/>
            </div>
            <button class="btn-close d-lg-none" type="button" data-coreui-dismiss="offcanvas" data-coreui-theme="light"
                aria-label="Close"
                onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()">
            </button>
        </div>
        
        <!-- Sidebar block -->
        @include('layouts.includes.sidebar_admin')
        <!-- / Sidebar block -->
       
    </div>
    <div class="wrapper d-flex flex-column min-vh-100">

        <!-- Header block -->
        @include('layouts.includes.header')
        <!-- / Header block -->

        <div class="body flex-grow-1">
            @yield('content')            
        </div>

        <!-- Footer block -->
        @include('layouts.includes.footer')
        <!-- / Footer block -->
    </div>
    
</body>
<script src="{{ asset('js/vendor/jquery-2.0.2.min.js') }}"></script>
<script src="{{ asset('js/vendor/jquery-ui-1.10.3.min.js') }}"></script>
<script src="{{ asset('js/vendor/dataTables.js') }}"></script>
<script src="{{ asset('js/vendor/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('js/cui/config.js') }}"></script>
{{-- <script src="{{ asset('js/cui/color-modes.js') }}"></script> --}}
<script src="{{ asset('js/cui/coreui-pro.bundle.min.js') }}"></script>
<script src="{{ asset('js/locale/dayjs.min.js') }}"></script>
<script src="{{ asset('js/locale/customParseFormat.js') }}"></script>
{{-- <script src="{{ asset('js/locale/id.js') }}"></script> --}}
<script src="{{ asset('js/locale/moment-with-locales.js') }}"></script>
<script src="{{ asset('js/vendor/select2.min.js') }}"></script>
<script src="{{ asset('js/vendor/snackbar.js') }}"></script>
<script src="{{ asset('js/vendor/ellipsis.js') }}"></script>
<script src="{{ asset('js/vendor/autosize.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.autosize').autosize({append: "\n"});
        $('.select2').select2({ allowClear: true, placeholder: '-- Select --'});
    });
</script>
@yield('extra_js')
{{-- <script src="js/cui/main.js"></script> --}}
    {{-- @stack('before-scripts') --}}
    {{-- <script src="node_modules/@coreui/coreui/dist/js/coreui.bundle.min.js"></script>
    <script src="node_modules/simplebar/dist/simplebar.min.js"></script> --}}
    {{-- @vite('resources/js/app.js') --}}
    {{-- @stack('after-scripts') --}}
</html>