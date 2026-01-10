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

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 30px;">
                            <h5 class="card-title">View Recovery Officer Debt Details</h5>

                            <a href="{{ route('dashboard.operational') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-plus" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Invoice No</th>
                                        <th>Customer</th>
                                        <th>Invoice Amount</th>
                                        <th>Outstanding</th>
                                        <th>Invoice Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $index => $invoice)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $invoice->invoice_no }}</td>
                                            <td>{{ $invoice->customer_name }}</td>
                                            <td>{{ number_format($invoice->amount, 2) }}</td>
                                            <td class="text-danger">
                                                {{ number_format($invoice->outstanding_balance, 2) }}
                                            </td>
                                            <td>{{ $invoice->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th>Total:</th>
                                        <th>{{ $total['totalAmount'] }}</th>
                                        <th colspan="2">{{ $total['totalBalance'] }}</th>
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
@endsection
