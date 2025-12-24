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
                            <h5 class="card-title">Raise Invoice</h5>

                            <a href="{{ route('gas-requests.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('invoices.store') }}">
                            @csrf

                            <input type="hidden" name="rate" value="{{ $rate }}" />
                            <input type="hidden" name="kg" value="{{ $gasRequest->kg }}" />
                            <input type="hidden" name="amount" value="{{ $gasRequest->amount }}" />
                            <input type="hidden" name="branch_id" value="{{ $gasRequest->branch_id }}" />
                            <input type="hidden" name="request_id" value="{{ $gasRequest->id }}" />
                            <input type="hidden" name="customer_id" value="{{ $gasRequest->customer_id }}" />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" placeholder="Name" value="{{ $gasRequest->name ?? '' }}"
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
                                            name="contact" placeholder="Contact" value="{{ $gasRequest->contact ?? '' }}"
                                            readonly />

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
                                            value="{{ $gasRequest->community->name ?? '' }}" readonly />

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
                                            name="branch" placeholder="Branch"
                                            value="{{ $gasRequest->branch->name ?? '' }}" readonly />

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
                                        <label>Delivery Branch</label>
                                        <input type="text" class="form-control @error('branch') is-invalid @enderror"
                                            name="branch" placeholder="Branch"
                                            value="{{ $gasRequest->deliveryBranch->name ?? '' }}" readonly />

                                        @error('branch_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Delivery Driver</label>
                                        <input type="text" class="form-control @error('driver_id') is-invalid @enderror"
                                            name="driver_id" placeholder="Delivery Driver"
                                            value="{{ $gasRequest->driverAssigned->name ?? 'N/A' }}" readonly />

                                        @error('driver_assigned')
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
                                        <label>Rep Name</label>
                                        <input type="text" class="form-control @error('rep_name') is-invalid @enderror"
                                            name="rep_name" placeholder="Rep Name"
                                            value="{{ $gasRequest->rep_name ?? '' }}" readonly />

                                        @error('rep_name')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Rep Contact</label>
                                        <input type="text"
                                            class="form-control @error('rep_contact') is-invalid @enderror"
                                            name="rep_contact" placeholder="Rep Contact"
                                            value="{{ $gasRequest->rep_contact ?? '' }}" readonly />

                                        @error('rep_contact')
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
                                        <label>Gas Quantity Type</label>
                                        <select
                                            class="form-control default-select @error('quantity_type') is-invalid @enderror"
                                            name="quantity_type">
                                            <option value="" selected>Select Gas Quantity Type</option>
                                            <option value="KG">By KG</option>
                                            <option value="GHS">By GHS</option>
                                        </select>

                                        @error('quantity_type')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label id="value1_label">Gas Quantity (KG / GHS)</label>
                                        <input type="text" class="form-control @error('value1') is-invalid @enderror"
                                            name="value1" placeholder="Gas Quantity (KG / GHS)"
                                            value="{{ $gasRequest->kg ?? '0' }}" />

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
                                        <label id="value2_label">Amount/KG</label>
                                        <input type="text" class="form-control @error('value2') is-invalid @enderror"
                                            name="value2" placeholder="Gas Amount (GHS)"
                                            value="{{ $gasRequest->amount ?? '0' }}" readonly />

                                        @error('value2')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label id="value2_label">Discount (%)</label>
                                        <input type="text"
                                            class="form-control @error('discount') is-invalid @enderror" name="discount"
                                            placeholder="Discount (%)" />

                                        @error('discount')
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
                                        <label id="value2_label">Discount Amount</label>
                                        <input type="text"
                                            class="form-control @error('discount_amount') is-invalid @enderror"
                                            name="discount_amount" placeholder="Discount Amount" readonly />

                                        @error('discount_amount')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label id="invoice_no">Invoice No.</label>
                                        <input type="text"
                                            class="form-control @error('invoice_no') is-invalid @enderror"
                                            name="invoice_no" placeholder="Invoice No." />

                                        @error('invoice_no')
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
                                        <label>Attachment</label>

                                        @if (!empty($gasRequest->attachment))
                                            @php
                                                $attachmentPath = asset(
                                                    'storage/images/attachment/' . $gasRequest->attachment,
                                                );
                                            @endphp

                                            @if (Str::endsWith($gasRequest->attachment, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                                <p><a href="{{ $attachmentPath }}" target="_blank"
                                                        class="btn btn-outline-primary btn-sm">
                                                        View Invoice Attachment
                                                    </a></p>
                                            @else
                                                <p><a href="{{ $attachmentPath }}" target="_blank"
                                                        class="btn btn-outline-primary btn-sm">
                                                        View Invoice Attachment
                                                    </a></p>
                                            @endif
                                        @else
                                            <p class="text-muted mt-2">No attachment uploaded.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="margin-top:20px;">
                                <button type="submit" class="btn btn-primary" style="width:180px">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Raise Invoice
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
    <script src="{{ asset('assets/dist/js/gas.js?v1=5678') }}"></script>

    <script>
        $(document).ready(function() {
            $('input[name="discount"]').on('blur', function() {
                let discount = parseFloat($(this).val()) || 0;
                let amount = parseFloat($('input[name="value2"]').val()) || 0;

                if (amount > 0 && discount > 0) {
                    let discountAmount = (amount * discount) / 100;
                    $('input[name="discount_amount"]').val(discountAmount.toFixed(2));
                } else {
                    $('input[name="discount_amount"]').val('0.00');
                }
            });
        });
    </script>
@endsection
