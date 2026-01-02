@extends('layout.base')

@section('page-styles')
@endsection

@section('page-content')
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 20px;">
                            <h5 class="card-title">Make Payment</h5>

                            <a href="{{ route('invoices.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('payments.store') }}">
                            @csrf

                            <input type="hidden" name="branch_id" value="{{ $invoice->branch_id }}" />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Invoice No.</label>
                                        <input type="text" class="form-control @error('invoice_no') is-invalid @enderror"
                                            name="invoice_no" placeholder="Invoice No"
                                            value="{{ $invoice->invoice_no ?? '' }}" readonly />

                                        @error('invoice_no')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Customer ID</label>
                                        <input type="text"
                                            class="form-control @error('customer_id') is-invalid @enderror"
                                            name="customer_id" placeholder="Customer id"
                                            value="{{ $invoice->customer_id ?? '' }}" readonly />

                                        @error('customer_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" placeholder="Name" value="{{ $invoice->customer->name ?? '' }}"
                                            readonly />

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Contact</label>
                                        <input type="text" class="form-control @error('contact') is-invalid @enderror"
                                            name="contact" placeholder="Contact"
                                            value="{{ $invoice->customer->contact ?? '' }}" readonly />

                                        @error('contact')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Community</label>
                                        <input type="text" class="form-control @error('community') is-invalid @enderror"
                                            name="community" placeholder="Community"
                                            value="{{ $invoice->customer->community->name ?? '' }}" readonly />

                                        @error('community_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Branch</label>
                                        <input type="text" class="form-control @error('branch') is-invalid @enderror"
                                            name="branch" placeholder="Branch" value="{{ $invoice->branch->name ?? '' }}"
                                            readonly />

                                        @error('branch_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Delivery Driver</label>
                                        <input type="text" class="form-control @error('driver_id') is-invalid @enderror"
                                            name="driver_id" placeholder="Delivery Driver"
                                            value="{{ $invoice->gasRequest->driverAssigned->name ?? 'N/A' }}" readonly />

                                        @error('driver_assigned')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label id="value1_label">Gas Quantity (KG)</label>
                                        <input type="text" class="form-control @error('value1') is-invalid @enderror"
                                            name="value1" placeholder="Gas Quantity (KG / GHS)"
                                            value="{{ $invoice->gasRequest->kg ?? '0' }}" readonly />

                                        @error('value1')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label id="value2_label">Invoice Amount (GHS)</label>
                                        <input type="text" class="form-control @error('amount') is-invalid @enderror"
                                            name="amount" placeholder="Gas Amount (GHS)"
                                            value="{{ $invoice->amount ?? '0' }}" readonly />

                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Payment Mode</label>
                                        <select
                                            class="form-control default-select @error('payment_mode') is-invalid @enderror"
                                            name="payment_mode">
                                            <option value="" selected>Select Payment Mode</option>
                                            <option value="cash">Cash</option>
                                            <option value="cheque">Cheque</option>
                                            <option value="bank transfer">Bank Transfer</option>
                                        </select>

                                        @error('payment_mode')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label id="value2_label">Amount Paid (GHS)</label>
                                        <input type="text"
                                            class="form-control @error('amount_paid') is-invalid @enderror"
                                            name="amount_paid" placeholder="Amount Paid (GHS)" />

                                        @error('amount_paid')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="bank_name">
                                    <div class="form-group mt-3">
                                        <label id="value2_label">Bank Name</label>
                                        <input type="text"
                                            class="form-control @error('bank_name') is-invalid @enderror"
                                            name="bank_name" placeholder="Bank Name" />

                                        @error('bank_name')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6" id="cheque_no">
                                    <div class="form-group mt-3">
                                        <label>Cheque No.</label>
                                        <input type="text"
                                            class="form-control @error('cheque_no') is-invalid @enderror"
                                            name="cheque_no" placeholder="Cheque No." />

                                        @error('cheque_no')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>
                                            <input type="checkbox" id="withholding_tax_checkbox" name="withholding_tax"
                                                value="3" class="big-checkbox">
                                            Apply Withholding Tax (3%)
                                        </label>

                                        @error('withholding_tax')
                                            <span class="invalid-feedback d-block" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-2">
                                        <label>Withholding Tax Amount</label>
                                        <input type="text"
                                            class="form-control @error('withholding_tax_amount') is-invalid @enderror"
                                            name="withholding_tax_amount" id="withholding_tax_amount"
                                            placeholder="Withholding Tax Amount" readonly />

                                        @error('withholding_tax_amount')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="d-flex justify-content-end" style="margin-top:20px;">
                                <button type="submit" class="btn btn-primary" style="width:180px">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Make Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script src="{{ asset('assets/dist/js/gas.js?v1=' . time()) }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('withholding_tax_checkbox');
            const whtAmountInput = document.getElementById('withholding_tax_amount');
            const invoiceAmountInput = document.querySelector('input[name="amount"]');

            checkbox.addEventListener('change', function() {
                let invoiceAmount = parseFloat(invoiceAmountInput?.value) || 0;

                if (this.checked && invoiceAmount > 0) {
                    let withholdingTaxAmount = invoiceAmount * 0.03;
                    whtAmountInput.value = withholdingTaxAmount.toFixed(2);
                } else {
                    whtAmountInput.value = '';
                }
            });
        });
    </script>
@endsection
