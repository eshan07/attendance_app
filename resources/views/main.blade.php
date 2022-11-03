<!DOCTYPE html>
<html lang="en">
@include('partials.head_asset')
<body class="sb-nav-fixed">
@include('include.navbar')
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            @include('include.sidebar')
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            @if ($message = Session::get('success'))
                    <h6 class="alert alert-success">{{ $message }}</h6>
            @elseif($message = Session::get('error'))
                <h6 class="alert alert-danger">{{ $message }}</h6>
                @endif
                @yield('content')
        </main>
        @include('include.footer')
    </div>
</div>
@include('partials.foot_asset')
@stack('scripts')
</body>
</html>
