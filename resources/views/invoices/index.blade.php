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
                            <h5 class="card-title">View Invoices</h5>

                            <a href="{{ route('invoices.create') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-plus" aria-hidden="true"></i> New Invoice
                            </a>
                        </div>

                        <hr>
                        <form method="GET" action="{{ route('invoices.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Filter by Month</label>
                                    <select name="month" class="form-control form-select">
                                        <option value="">-- Select Month --</option>
                                        @foreach (range(1, 12) as $m)
                                            <option value="{{ $m }}"
                                                {{ request('month') == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Filter by Year</label>
                                    <select name="year" class="form-control form-select">
                                        <option value="">-- Select Year --</option>
                                        @for ($y = 2025; $y <= now()->year; $y++)
                                            <option value="{{ $y }}"
                                                {{ request('year') == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-warning w-100" type="submit">Search</button>
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary w-100">Reset</a>
                                </div>
                            </div>
                        </form>
                        <hr>

                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Invoice No.</th>
                                        <th>Customer ID</th>
                                        <th>Name</th>
                                        <th>Rate</th>
                                        <th>KG</th>
                                        <th>Amount</th>
                                        <th>Discount(%)</th>
                                        <th>Discount Amt</th>
                                        <th>Delivery Branch</th>
                                        <th>Driver</th>
                                        <th>Vehicle</th>
                                        <th>Due Date</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $index => $invoice)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $invoice->invoice_no }}</td>
                                            <td>{{ $invoice->customer_id }}</td>
                                            <td>{{ $invoice->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $invoice->rate }}</td>
                                            <td>{{ $invoice->kg }}</td>
                                            <td>{{ number_format($invoice->amount, 2) }}</td>
                                            <td>{{ $invoice->discount }}</td>
                                            <td>{{ number_format($invoice->discount_amount, 2) }}</td>
                                            <td>{{ $invoice->gasRequest->deliveryBranch->name ?? 'N/A' }}</td>
                                            <td>{{ $invoice->gasRequest->driverAssigned->name ?? 'N/A' }}</td>
                                            <td>{{ $invoice->gasRequest->driverAssigned->vehicle->vehicle_number ?? 'N/A' }}
                                            </td>
                                            <td>
                                                @if ($invoice->customer?->due_date)
                                                    {{ $invoice->created_at->copy()->addDays((int) $invoice->customer->due_date)->format('Y-m-d H:i:s') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $invoice->createdBy->name ?? 'N/A' }}</td>
                                            <td>{{ $invoice->created_at }}</td>
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
                                                                href="{{ route('invoices.show', $invoice) }}"
                                                                target="_blank">View Invoice
                                                            </a>

                                                            @can('invoices.update')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('invoices.edit', $invoice) }}">Edit Invoice
                                                                </a>
                                                            @endcan

                                                            <a class="dropdown-item"
                                                                href="{{ route('payments.create', $invoice) }}">Make
                                                                Payment
                                                            </a>
                                                            <a class="dropdown-item pass_cd" href="javascript:;"
                                                                data-bs-toggle="modal" data-bs-target="#reject-preview"
                                                                data-invoice_no="{{ $invoice->invoice_no }}"
                                                                data-customer_id="{{ $invoice->customer_id }}">
                                                                Pass Credit & Debit Note
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4"></th>
                                        <th>Total:</th>
                                        <th>{{ $total['kg'] }}</th>
                                        <th>{{ $total['amount'] }}</th>
                                        <th colspan="5"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="reject-preview">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pass Credit & Debit Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('invoices.creditDebit') }}">
                            @csrf

                            <input type='hidden' name="invoice_no" value="">
                            <input type="hidden" name="customer_id" value="">

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label class="form-label">Note Type</label>
                                    <select class="form-control form-select @error('note_type') is-invalid @enderror"
                                        name="note_type" required>
                                        <option disabled selected>Select Note Type</option>
                                        <option value="credit">Credit</option>
                                        <option value="debit">Debit</option>
                                    </select>

                                    @error('note_type')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4 col-md-12">
                                    <label class="form-label">Amount</label>
                                    <input type="text" name="amount"
                                        class="form-control @error('amount') is-invalid @enderror" placeholder="Amount"
                                        required>

                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4 col-md-12">
                                    <label class="form-label">Reason</label>
                                    <textarea class="form-control" name="reason" rows="5" cols="10" placeholder="Enter your reason here..."
                                        required></textarea>

                                    @error('reason')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </div>
                        </form>
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
        $(document).ready(function() {

            $(document).on('click', '.pass_cd', function() {
                let invoiceNo = $(this).data('invoice_no');
                let customerId = $(this).data('customer_id');

                $('input[name="invoice_no"]').val(invoiceNo);
                $('input[name="customer_id"]').val(customerId);
            });
        });
    </script>
@endsection
