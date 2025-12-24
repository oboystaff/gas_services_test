@extends('layout.base')

@section('page-styles')
@endsection

@section('page-content')
    @php
        $communityIds = $customer->community_id;

        if (is_string($communityIds)) {
            $decoded = json_decode($communityIds, true);
            $communityIds = is_array($decoded) ? $decoded : [$communityIds];
        } elseif (is_int($communityIds)) {
            $communityIds = [$communityIds];
        }

        $communityNames = \App\Models\Community::whereIn('id', $communityIds)->pluck('name')->implode(', ');
    @endphp

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

                            <a href="{{ route('customers.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('payments.store') }}">
                            @csrf

                            <input type="hidden" name="branch_id" value="{{ $customer->branch_id }}" />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Customer ID</label>
                                        <input type="text"
                                            class="form-control @error('customer_id') is-invalid @enderror"
                                            name="customer_id" placeholder="Customer id"
                                            value="{{ $customer->customer_id ?? '' }}" readonly />

                                        @error('customer_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" placeholder="Name" value="{{ $customer->name ?? '' }}"
                                            readonly />

                                        @error('name')
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
                                        <label>Contact</label>
                                        <input type="text" class="form-control @error('contact') is-invalid @enderror"
                                            name="contact" placeholder="Contact" value="{{ $customer->contact ?? '' }}"
                                            readonly />

                                        @error('contact')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Community</label>
                                        <input type="text" class="form-control @error('community') is-invalid @enderror"
                                            name="community" placeholder="Community" value="{{ $communityNames ?: 'N/A' }}"
                                            readonly />

                                        @error('community_id')
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
                                        <label>Branch</label>
                                        <input type="text" class="form-control @error('branch') is-invalid @enderror"
                                            name="branch" placeholder="Branch" value="{{ $customer->branch->name ?? '' }}"
                                            readonly />

                                        @error('branch_id')
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

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Payment Reference</label>
                                        <input type="text" class="form-control @error('reference') is-invalid @enderror"
                                            name="reference" placeholder="Payment Reference" />

                                        @error('reference')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6" id="bank_name">
                                    <div class="form-group mt-3">
                                        <label id="value2_label">Bank Name</label>
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                            name="bank_name" placeholder="Bank Name" />

                                        @error('bank_name')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="cheque_no">
                                    <div class="form-group mt-3">
                                        <label>Cheque No.</label>
                                        <input type="text"
                                            class="form-control @error('cheque_no') is-invalid @enderror" name="cheque_no"
                                            placeholder="Amount Paid (GHS)" />

                                        @error('cheque_no')
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
@endsection
