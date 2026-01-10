<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="Gas Request Services" />
    <meta name="description" content="Gas Request Services" />
    <meta name="robots" content="noindex,nofollow" />
    <title>Gas Request Services</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}" />
    <!-- Custom CSS -->
    <link href="{{ asset('assets/libs/flot/css/float-chart.css') }}" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="{{ asset('assets/dist/css/style.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dist/css/common.css?t=' . time()) }}" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @yield('page-styles')
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon -->
                        <b class="logo-icon ps-2">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            {{-- <img src="{{ asset('assets/images/logo-icon.png') }}" alt="homepage" class="light-logo"
                                width="25" /> --}}
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text ms-2">
                            <!-- dark Logo text -->
                            {{-- <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage" class="light-logo" /> --}}
                            <p style="font-weight:bold">Gas Request Service</p>
                        </span>
                        <!-- Logo icon -->
                        <!-- <b class="logo-icon"> -->
                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                        <!-- Dark Logo icon -->
                        <!-- <img src="../assets/images/logo-text.png" alt="homepage" class="light-logo" /> -->

                        <!-- </b> -->
                        <!--End Logo icon -->
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-start me-auto">
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a>
                        </li>

                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-end">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="
                    nav-link
                    dropdown-toggle
                    text-muted
                    waves-effect waves-dark
                    pro-pic
                  "
                                href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="../assets/images/users/1.jpg" alt="user" class="rounded-circle"
                                    width="31" />
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end user-dd animated"
                                aria-labelledby="navbarDropdown">
                                <div class="dropdown-divider"></div>
                                <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 4px;">
                                    <a class="dropdown-item" href="javascript:void(0);"
                                        style="text-decoration: none;">{{ request()->user()->name ?? 'N/A' }}</a>
                                    <span style="margin-left:17px;margin-top:-10px">Application User</span>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                        class="fa fa-power-off me-1 ms-1"></i>
                                    Logout</a>


                                <form id="logout-form" action="{{ route('auth.logout') }}" method="GET"
                                    class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="pt-4">
                        @can('dashboards.operational')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('dashboard.operational') }}" aria-expanded="false"><i
                                        class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a>
                            </li>
                        @endcan

                        @can('customers.view')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('customers.index') }}" aria-expanded="false"><i
                                        class="mdi mdi-face"></i><span class="hide-menu">Customers</span></a>
                            </li>
                        @endcan

                        @can('gas-requests.view')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('gas-requests.index') }}" aria-expanded="false"><i
                                        class="mdi mdi-forum"></i><span class="hide-menu">Gas Requests</span></a>
                            </li>
                        @endcan

                        @can('invoices.view')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('invoices.index') }}" aria-expanded="false"><i
                                        class="mdi mdi-cart"></i><span class="hide-menu">Invoices</span></a>
                            </li>
                        @endcan

                        @can('payments.view')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('payments.index') }}" aria-expanded="false"><i
                                        class="mdi mdi-cash"></i><span class="hide-menu">Payments</span></a>
                            </li>
                        @endcan

                        @can('payments.view')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('due-dates.index') }}" aria-expanded="false"><i
                                        class="mdi mdi-calendar"></i><span class="hide-menu">Invoices Due</span></a>
                            </li>
                        @endcan

                        @canany(['users.view', 'branches.view', 'communities.view', 'rates.view', 'agents.view',
                            'roles.view', 'permissions.view', 'drivers.view', 'vehicles.view'])
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                    aria-expanded="false"><i class="mdi mdi-settings"></i><span
                                        class="hide-menu">Settings</span></a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    @can('users.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('users.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-emoticon"></i><span class="hide-menu"> View Users
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('branches.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('branches.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-map-marker"></i><span class="hide-menu"> View Branches
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('communities.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('communities.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-map-marker"></i><span class="hide-menu"> View Communties
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('rates.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('rates.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-cash"></i><span class="hide-menu"> View Rates
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('agents.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('agents.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-emoticon"></i><span class="hide-menu"> View Agents
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('drivers.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('drivers.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-emoticon"></i><span class="hide-menu"> View Drivers
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('vehicles.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('vehicles.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-emoticon"></i><span class="hide-menu"> View Vehicles
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('vehicles.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('notifications.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-emoticon"></i><span class="hide-menu"> View Notification
                                                    Team
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('vehicles.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('recovery-officers.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-emoticon"></i><span class="hide-menu"> View Recovery
                                                    Officers
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('roles.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('roles.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-logout-variant"></i><span class="hide-menu"> View Roles
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('permissions.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('permissions.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-logout-variant"></i><span class="hide-menu"> View
                                                    Permissions
                                                </span></a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany


                        @canany(['reports.view'])
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                    aria-expanded="false"><i class="mdi mdi-chart-areaspline"></i><span
                                        class="hide-menu">Reports
                                    </span></a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    @can('reports.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('customer-reports.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-chart-timeline"></i><span class="hide-menu"> Customer
                                                    Report
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('reports.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('gas-request-reports.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-chart-timeline"></i><span class="hide-menu"> Gas Request
                                                    Report
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('reports.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('agent-reports.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-chart-timeline"></i><span class="hide-menu"> Agent Report
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('reports.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('invoice-reports.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-chart-timeline"></i><span class="hide-menu"> Invoice Report
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('reports.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('receivables-reports.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-chart-timeline"></i><span class="hide-menu"> Receivables
                                                    Report
                                                </span></a>
                                        </li>
                                    @endcan

                                    @can('reports.view')
                                        <li class="sidebar-item">
                                            <a href="{{ route('payment-reports.index') }}" class="sidebar-link"><i
                                                    class="mdi mdi-chart-timeline"></i><span class="hide-menu"> Payment
                                                    Report
                                                </span></a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">{{ $pageTitle }}</h4>
                        <div class="ms-auto text-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Library
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            @yield('page-content')
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
                All Rights Reserved. Designed and Developed by
                <a href="javascript:void(0)">Cyber Solutions</a>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('assets/dist/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('assets/dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('assets/dist/js/custom.min.js') }}"></script>
    <!--This page JavaScript -->
    <!-- <script src="../dist/js/pages/dashboards/dashboard1.js"></script> -->
    <!-- Charts js Files -->
    <script src="{{ asset('assets/libs/flot/excanvas.js') }}"></script>
    <script src="{{ asset('assets/libs/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/libs/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('assets/libs/flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('assets/libs/flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('assets/libs/flot/jquery.flot.crosshair.js') }}"></script>
    <script src="{{ asset('assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/pages/chart/chart-page-init.js') }}"></script>

    @yield('page-scripts')
</body>

</html>
