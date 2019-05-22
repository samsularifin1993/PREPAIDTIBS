<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('js.up')

    <!-- Styles -->
    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/dashboard/">
    <link href="https://getbootstrap.com/docs/4.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://getbootstrap.com/docs/4.3/examples/dashboard/dashboard.css" rel="stylesheet">

    @yield('css')

    <style>
        .feather-32{
            width: 32px;
            height: 32px;
        }

        [role="main"] {
            padding-top: 42px;
        }

        li.nav-item a.nav-link{
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="d-block d-md-none">
        <nav class="navbar navbar-dark bg-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#side-menu" aria-controls="side-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
    </div>
    <div class="d-none d-md-block">
        <nav class="navbar navbar-dark navbar-expand fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{ url('/') }}">Prepaid System</a>
        <!-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> -->
        <ul class="navbar-nav px-3 ml-auto">
            <li class="nav-item text-nowrap">
            @if(Auth::guard('admin')->check())
            <a class="nav-link" href="{{ route('admin.logout') }}" id="logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Sign out</a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
            @elseif(Auth::guard('user')->check())
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::guard('user')->user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('log.index') }}">
                    Log
                </a>
                <a class="dropdown-item" href="{{ route('user.password.change') }}">
                    Change Password
                </a>
                <a class="dropdown-item" href="{{ route('user.logout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            @endif
        </ul>
        </nav>
    </div>

    <div class="container-fluid">
        <div class="row">
        @if(Auth::guard('admin')->check())
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('dashboard.administrator') }}">
                            <span data-feather="bar-chart-2"></span>
                            Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('channel.index') }}">
                            <span data-feather="file"></span>
                            Channel
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user2.index') }}">
                            <span data-feather="users"></span>
                            User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('organization.index') }}">
                            <span data-feather="users"></span>
                            Organization
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payment.index') }}">
                            <span data-feather="credit-card"></span>
                            Payment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('product_family.index') }}">
                            <span data-feather="box"></span>
                            Product Family
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('product.index') }}">
                            <span data-feather="box"></span>
                            Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.password.change') }}">
                            <span data-feather="lock"></span>
                            Change Password
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <nav class="col-md-2 bg-light sidebar" id="side-menu" style="padding-top:0px;">
            <h1 class="text-right mr-3"><strong id="" style="cursor:pointer;"><span class="feather-32" data-feather="chevron-up"></span></strong></h1>
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('dashboard.administrator') }}">
                            <span data-feather="bar-chart-2"></span>
                            Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('channel.index') }}">
                            <span data-feather="file"></span>
                            Channel
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user2.index') }}">
                            <span data-feather="users"></span>
                            User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('organization.index') }}">
                            <span data-feather="users"></span>
                            Organization
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payment.index') }}">
                            <span data-feather="credit-card"></span>
                            Payment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('product_family.index') }}">
                            <span data-feather="box"></span>
                            Product Family
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('product.index') }}">
                            <span data-feather="box"></span>
                            Product
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-primary" id="logout_link" style="cursor:pointer;">
                    <strong><span>Logout</span></strong>
                    <a class="d-flex align-items-center text-primary">
                        <span data-feather="log-out"></span>
                    </a>
                </div>
            </nav>
        @elseif(Auth::guard('user')->check())
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'v_dashboard_admin') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.administrator') }}">
                            <span data-feather="bar-chart-2"></span>
                            Dashboard Administrator <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'v_dashboard_revenue') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.pivot2') }}">
                            <span data-feather="bar-chart-2"></span>
                            Dashboard Finance<span class="sr-only">(current)</span>
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'role_r') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('role.index') }}">
                            <span data-feather="settings"></span>
                            Role
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'user_r') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user2.index') }}">
                            <span data-feather="users"></span>
                            User
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'channel_r') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('channel.index') }}">
                            <span data-feather="file"></span>
                            Channel
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'organization_r') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('organization.index') }}">
                            <span data-feather="users"></span>
                            Organization
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'payment_r') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payment.index') }}">
                            <span data-feather="credit-card"></span>
                            Payment
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                            <span data-feather="box"></span>
                            Product
                            </a>
                        </li>
                        <ul class="nav collapse" id="collapse1">
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'product_family_r') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('product_family.index') }}">
                                <span data-feather="box"></span>
                                Family
                                </a>
                            </li>
                            @endif
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'product_r') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('product.index') }}">
                                <span data-feather="box"></span>
                                Product
                                </a>
                            </li>
                            @endif
                        </ul>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                            <span data-feather="monitor"></span>
                            Transaction
                            </a>
                        </li>
                        <ul class="nav collapse" id="collapse2">
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'r_trx_success') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('transaction.success') }}">
                                <span data-feather="check-circle"></span>
                                Transaction Success
                                </a>
                            </li>
                            @endif
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'r_trx_reject') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('transaction.rejected') }}">
                                <span data-feather="x-circle"></span>
                                Transaction Rejected
                                </a>
                            </li>
                            @endif
                        </ul>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                            <span data-feather="monitor"></span>
                            Report
                            </a>
                        </li>
                        <ul class="nav collapse" id="collapse3">
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'r_revenue') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('report.revenue_summary') }}">
                                <span data-feather="monitor"></span>
                                Revenue (Summary)
                                </a>
                            </li>
                            @endif
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'r_revenue') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('report.revenue_by_product') }}">
                                <span data-feather="monitor"></span>
                                Revenue (Product)
                                </a>
                            </li>
                            @endif
                        </ul>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('api.index') }}">
                            <span data-feather="code"></span>
                            API
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <nav class="col-md-2 bg-light sidebar collapse" id="side-menu" style="padding-top:0px;">
            <h1 class="text-right mr-3"><strong id="hide" style="cursor:pointer;"><span class="feather-32" data-feather="chevron-up"></span></strong></h1>
            <div class="sidebar-sticky">
                    <ul class="nav flex-column mb-5">
                    @if(\App\User::permission(Auth::guard('user')->user()->id, 'v_dashboard_admin') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.administrator') }}">
                            <span data-feather="bar-chart-2"></span>
                            Dashboard Administrator <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'v_dashboard_revenue') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.pivot') }}">
                            <span data-feather="bar-chart-2"></span>
                            Dashboard Finance<span class="sr-only">(current)</span>
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'role_r') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('role.index') }}">
                            <span data-feather="settings"></span>
                            Role
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'user_r') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user2.index') }}">
                            <span data-feather="users"></span>
                            User
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'channel_r') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('channel.index') }}">
                            <span data-feather="file"></span>
                            Channel
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'organization_r') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('organization.index') }}">
                            <span data-feather="users"></span>
                            Organization
                            </a>
                        </li>
                        @endif
                        @if(\App\User::permission(Auth::guard('user')->user()->id, 'payment_r') === 'true')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payment.index') }}">
                            <span data-feather="credit-card"></span>
                            Payment
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                            <span data-feather="box"></span>
                            Product
                            </a>
                        </li>
                        <ul class="nav collapse" id="collapse1">
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'product_family_r') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('product_family.index') }}">
                                <span data-feather="box"></span>
                                Family
                                </a>
                            </li>
                            @endif
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'product_r') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('product.index') }}">
                                <span data-feather="box"></span>
                                Product
                                </a>
                            </li>
                            @endif
                        </ul>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                            <span data-feather="monitor"></span>
                            Transaction
                            </a>
                        </li>
                        <ul class="nav collapse" id="collapse2">
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'r_trx_success') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('transaction.success') }}">
                                <span data-feather="check-circle"></span>
                                Transaction Success
                                </a>
                            </li>
                            @endif
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'r_trx_reject') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('transaction.rejected') }}">
                                <span data-feather="x-circle"></span>
                                Transaction Rejected
                                </a>
                            </li>
                            @endif
                        </ul>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                            <span data-feather="monitor"></span>
                            Report
                            </a>
                        </li>
                        <ul class="nav collapse" id="collapse3">
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'r_revenue') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('report.revenue_summary') }}">
                                <span data-feather="monitor"></span>
                                Revenue (Summary)
                                </a>
                            </li>
                            @endif
                            @if(\App\User::permission(Auth::guard('user')->user()->id, 'r_revenue') === 'true')
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="{{ route('report.revenue_by_product') }}">
                                <span data-feather="monitor"></span>
                                Revenue (Product)
                                </a>
                            </li>
                            @endif
                        </ul>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('log.index') }}">
                            <span data-feather="clipboard"></span>
                            Log
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.password.change') }}">
                            <span data-feather="unlock"></span>
                            Change Password
                            </a>
                        </li>
                        <li class="nav-item mt-5">
                            <a class="nav-link" href="{{ route('user.logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            <span data-feather="log-out"></span>
                            Logout
                            </a>

                            <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        @endif

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="https://getbootstrap.com/docs/4.3/dist/js/bootstrap.bundle.min.js"><\/script>')</script>
    <script src="https://getbootstrap.com/docs/4.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.3/examples/dashboard/dashboard.js"></script> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
    <!-- <script src="https://getbootstrap.com/docs/4.2/examples/dashboard/dashboard.js"></script> -->
    <script type="text/javascript" src="{{ asset('js/jquery.preloaders.min.js') }}"></script>
    
    <script type="text/javascript">
        feather.replace();

        $(document).on("click", "#hide", function(){
            $('button[data-target="#side-menu"]').click();
        });

        $(document).on("click", "#logout_link", function(){
            $('#logout').click();
        });
    </script>
</body>
@yield('js.bottom')
</html>