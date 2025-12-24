@extends('layout.base')

@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

    <style>
        th,
        td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        table.dataTable {
            table-layout: auto;
            width: 100%;
        }

        .dataTables_wrapper {
            overflow-x: auto;
            position: relative;
        }

        .dropdown-menu {
            position: absolute !important;
            z-index: 1050 !important;
            white-space: nowrap;
            overflow: visible;
            min-width: max-content;
            max-width: none;
        }

        .dropdown {
            position: static !important;
        }

        .dataTables_wrapper .dropdown-menu {
            transform: translate3d(0, 0, 0);
        }

        .dropdown-menu.show.adjusted {
            position: fixed !important;
        }

        .dropdown-menu {
            position: absolute !important;
            z-index: 1050 !important;
            white-space: nowrap;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
            min-width: 200px;
            max-width: 300px;
            width: auto;
        }

        .dropdown {
            position: static !important;
        }

        .dataTables_wrapper .dropdown-menu {
            transform: translate3d(0, 0, 0);
        }
    </style>
@endsection

@section('page-content')
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">

                @if (session()->has('status'))
                    <div class="alert alert-success" role="alert">
                        <strong>{{ session('status') }}</strong>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 30px;">
                            <h5 class="card-title" style="color:green">View Gas Sales Summary</h5>
                        </div>

                        <div class="table-responsive">
                            <table id="zero_config2" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Sales Person</th>
                                        <th>Total Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($summary as $index => $summ)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $summ->name ?? 'N/A' }}</td>
                                            <td>{{ $summ->total_sold ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th>Total:</th>
                                    <th>{{ $total['total_sum'] }}</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 30px;">
                            <h5 class="card-title">View Gas Sales</h5>

                            <a href="{{ route('sales.create') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-plus" aria-hidden="true"></i> New Gas Sale
                            </a>
                        </div>


                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>S/N</th>
                                        <th>Trans. ID</th>
                                        {{-- <th>Customer ID</th> --}}
                                        <th>CID</th>
                                        <th>Name</th>
                                        {{-- <th>Contact</th> --}}
                                        {{-- <th>Community</th> --}}
                                        <th>Branch</th>
                                        <th>KG</th>
                                        <th>Amount</th>
                                        <th>Service Charge</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sales as $index => $sale)
                                        <tr>
                                            <td>
                                                <div class="dropdown">
                                                    <div class="btn-link" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg width="24" height="24" viewBox="0 0 24 24"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12Z"
                                                                stroke="#737B8B" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                            </path>
                                                            <path
                                                                d="M18 12C18 12.5523 18.4477 13 19 13C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11C18.4477 11 18 11.4477 18 12Z"
                                                                stroke="#737B8B" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                            </path>
                                                            <path
                                                                d="M4 12C4 12.5523 4.44772 13 5 13C5.55228 13 6 12.5523 6 12C6 11.4477 5.55228 11 5 11C4.44772 11 4 11.4477 4 12Z"
                                                                stroke="#737B8B" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <div class="py-2">
                                                            <a class="dropdown-item"
                                                                href="{{ route('sales.show', $sale) }}">View Sales
                                                            </a>
                                                            @can('sales.print')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('sales.print', $sale) }}"
                                                                    target="_blank">Print Receipt
                                                                </a>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $sale->transaction_id ?? 'N/A' }}</td>
                                            <td>{{ $sale->cid ?? 'N/A' }}</td>
                                            {{-- <td>{{ $sale->customer_id ?? 'N/A' }}</td> --}}
                                            <td>{{ $sale->name ?? 'N/A' }}</td>
                                            {{-- <td>{{ $sale->contact ?? 'N/A' }}</td> --}}
                                            {{-- <td>{{ $sale->community->name ?? 'N/A' }}</td> --}}
                                            <td>{{ $sale->branch->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($sale->kg, 2) }}</td>
                                            <td>{{ number_format($sale->amount, 2) }}</td>
                                            <td>{{ $sale->service_charge ?? 'N/A' }}</td>
                                            <td>{{ $sale->createdBy->name ?? 'N/A' }}</td>
                                            <td>{{ $sale->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Total:</th>
                                        <th>{{ $total['kg'] }}</th>
                                        <th>{{ $total['amount'] }}</th>
                                        <th>{{ $total['service_charge'] }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="{{ asset('assets/dist/js/common.js?t=' . time()) }}"></script>

    <script>
        $(".dropdown").on("shown.bs.dropdown", function() {
            var $dropdownMenu = $(this).find(".dropdown-menu");
            var dropdownOffset = $dropdownMenu.offset();
            var dropdownWidth = $dropdownMenu.outerWidth();
            var windowWidth = $(window).width();
            var windowHeight = $(window).height();
            var scrollTop = $(window).scrollTop();

            $dropdownMenu.removeClass("adjusted").css({
                top: "",
                left: "",
                right: "",
            });

            if (dropdownOffset.left + dropdownWidth > windowWidth) {
                $dropdownMenu.addClass("adjusted").css({
                    left: windowWidth - dropdownWidth - 20 + "px",
                });
            } else if (dropdownOffset.left < 0) {
                $dropdownMenu.addClass("adjusted").css({
                    left: "20px",
                });
            }

            if (dropdownOffset.top + $dropdownMenu.outerHeight() - scrollTop > windowHeight) {
                $dropdownMenu.addClass("adjusted").css({
                    top: windowHeight - $dropdownMenu.outerHeight() - 20 + scrollTop + "px",
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const Id = urlParams.get("id");

            if (Id) {
                // Open print page in a new tab
                window.open(`/sales/print/${Id}`, "_blank");
            }
        });
    </script>
@endsection
