@extends('layout.base')

@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
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

                @if (session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ session('error') }}</strong>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 30px;">
                            <h5 class="card-title">View Customers</h5>

                            <a href="{{ route('customers.create') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-plus" aria-hidden="true"></i> New Customer
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Customer ID</th>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Secondary Contact</th>
                                        <th>Customer Branch</th>
                                        <th>Outlet</th>
                                        <th>Threshold Amount</th>
                                        <th>Credit Line</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $index => $customer)
                                        @php
                                            $invoice_no = $customer->invoices->last()->invoice_no ?? '';
                                        @endphp

                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $customer->customer_id }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->contact ?? 'N/A' }}</td>
                                            <td>{{ $customer->secondary_contact ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $communityIds = $customer->community_id;

                                                    if (is_string($communityIds)) {
                                                        $decoded = json_decode($communityIds, true);
                                                        $communityIds = is_array($decoded) ? $decoded : [$communityIds];
                                                    } elseif (is_int($communityIds)) {
                                                        $communityIds = [$communityIds];
                                                    }

                                                    $communityNames = \App\Models\Community::whereIn(
                                                        'id',
                                                        $communityIds,
                                                    )
                                                        ->pluck('name')
                                                        ->toArray();
                                                @endphp

                                                {{ implode(', ', $communityNames) ?: 'N/A' }}
                                            </td>
                                            <td>{{ $customer->branch->name ?? 'N/A' }}</td>
                                            <td>{{ $customer->threshold_amount ?? '0.0' }}</td>
                                            <td>{{ $customer->due_date ? $customer->due_date . ' day(s)' : 'N/A' }}</td>
                                            <td>{{ $customer->createdBy->name ?? 'N/A' }}</td>
                                            <td>{{ $customer->created_at }}</td>
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
                                                                href="{{ route('customers.show', $customer) }}">View
                                                                Customer
                                                            </a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('customers.edit', $customer) }}">Edit
                                                                Customer
                                                            </a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('customers.gasRequest', $customer) }}">Make
                                                                Gas Request
                                                            </a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('customers.statement', $customer) }}">View
                                                                Statement
                                                            </a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('customers.makePayment', $customer) }}">Make
                                                                Payment
                                                            </a>
                                                            <a class="dropdown-item pass_cd" href="javascript:;"
                                                                data-bs-target="#reject-preview"
                                                                data-invoice_no="{{ $invoice_no }}"
                                                                data-customer_id="{{ $customer->customer_id }}">
                                                                Pass Credit & Debit Note
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
                        <form method="POST" action="{{ route('customers.creditDebit') }}">
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

            $(document).on('click', '.pass_cd', function(e) {
                let invoiceNo = $(this).data('invoice_no');
                let customerId = $(this).data('customer_id');

                if (!invoiceNo || invoiceNo === '') {
                    e.preventDefault();
                    alert('This customer has no existing invoice. Please create an invoice first.');
                    return false;
                }

                $('input[name="invoice_no"]').val(invoiceNo);
                $('input[name="customer_id"]').val(customerId);

                $('#reject-preview').modal('show');
            });
        });
    </script>
@endsection
