<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Ogroz</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Fonts and icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/core/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/remix-icon-fonts/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/core/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('css/core/sweet-alert.css') }}">
    <link rel="stylesheet" href="{{ asset('css/core/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/core/theme.css') }}">

    @yield('page-head-scripts')
</head>

<body data-bs-theme="light">
    <div class="full-page-loader">
        <h5>Loading...</h5>
    </div>
    <div class="admin-page">
        @include('partials.admin.topbar')
        <div class="sidebar-and-content">
            @include('partials.admin.sidebar')
            <div class="main-content px-4 py-3 bg-light-subtle">
                @session('warning')
                    <div class="alert alert-warning alert-dismissible fade show top-level-message">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endsession
                @session('success')
                    <div class="alert alert-success alert-dismissible fade show top-level-message">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endsession
                @session('error')
                    <div class="alert alert-danger alert-dismissible fade show top-level-message">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endsession
                @yield('page-content')
            </div>
        </div>
    </div>
    @yield('direct-body-childs')
    <script src="{{ asset('js/core/jquery.js') }}"></script>
    <script src="{{ asset('js/core/datatable.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.js') }}"></script>
    <script src="{{ asset('js/core/sweet-alert.js') }}"></script>
    <script src="{{ asset('js/core/chart.js') }}"></script>
    <script src="{{ asset('js/core/theme.js') }}"></script>
    <script src="{{ asset('js/core/select2.js') }}"></script>
    @yield('page-scripts')
</body>

</html>
