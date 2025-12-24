@extends('layout.base')

@section('page-styles')
    <link rel="stylesheet" href="{{ asset('assets/dist/css/autocomplete.css') }}">
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
                            <h5 class="card-title">View Gas Sale</h5>

                            <a href="{{ route('sales.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('sales.store') }}">
                            @csrf

                            <input type="hidden" name="kg" />
                            <input type="hidden" name="amount" />
                            <input type="hidden" name="customer_url" url="{{ route('sales.fetch') }}" />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Customer ID</label>
                                        <input type="text"
                                            class="form-control @error('customer_id') is-invalid @enderror"
                                            name="customer_id" placeholder="Customer ID"
                                            value="{{ $sale->customer_id ?? 'N/A' }}" readonly />

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
                                            name="name" placeholder="Name" value="{{ $sale->name ?? 'N/A' }}" readonly />

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
                                            name="contact" placeholder="Contact" value="{{ $sale->contact }}" readonly />

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
                                        <input type="text"
                                            class="form-control @error('community_id') is-invalid @enderror"
                                            name="community_id" placeholder="Community_id"
                                            value="{{ $sale->community->name ?? '' }}" readonly />

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
                                        <input type="text" class="form-control @error('branch_id') is-invalid @enderror"
                                            name="branch_id" placeholder="Branch_id"
                                            value="{{ $sale->branch->name ?? '' }}" readonly />

                                        @error('branch_id')
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
                                            name="value1" placeholder="Gas Quantity (KG)" value="{{ $sale->kg }}"
                                            readonly />

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
                                        <label id="value2_label">Gas Amount (GHS)</label>
                                        <input type="text" class="form-control @error('value2') is-invalid @enderror"
                                            name="value2" placeholder="Final KG" value="{{ $sale->amount }}" readonly />

                                        @error('value2')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Service Charge (GHS)</label>
                                        <input type="text"
                                            class="form-control @error('service_charge') is-invalid @enderror"
                                            name="service_charge" placeholder="Final KG"
                                            value="{{ $sale->service_charge ?? 'N/A' }}" readonly />

                                        @error('service_charge')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="margin-top:20px;">
                                {{-- <button type="submit" class="btn btn-primary" style="width:180px">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Submit Sale
                                </button> --}}
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
@endsection
