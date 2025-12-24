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
                            <h5 class="card-title">View Cash Retired</h5>

                            <a href="{{ route('cash-retirements.retiredCash', ['sales_date' => request()->query('sales_date')]) }}"
                                type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('cash-retirements.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Bank Name</label>
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                            name="bank_name" placeholder="Bank Name"
                                            value="{{ $cashRetirement->bank_name }}" readonly />

                                        @error('bank_name')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Bank Branch Name</label>
                                        <input type="text"
                                            class="form-control @error('branch_name') is-invalid @enderror"
                                            name="branch_name" placeholder="Bank Branch Name"
                                            value="{{ $cashRetirement->branch_name }}" readonly />

                                        @error('branch_name')
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
                                        <label>Account Number</label>
                                        <input type="text"
                                            class="form-control @error('account_number') is-invalid @enderror"
                                            name="account_number" placeholder="Account Number"
                                            value="{{ $cashRetirement->account_number }}" readonly />

                                        @error('account_number')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Amount Retired</label>
                                        <input type="text"
                                            class="form-control @error('amount_retired') is-invalid @enderror"
                                            name="amount_retired" placeholder="Amount Retired"
                                            value="{{ $cashRetirement->amount_retired }}" readonly />

                                        @error('amount_retired')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if ($cashRetirement->payment_slip != null)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mt-3">
                                            <label>Uploaded Payment Slip</label>
                                            <img src="{{ asset('storage/payment_slips/' . $cashRetirement->payment_slip) }}"
                                                alt="Payment Slip" height="450" width="850" />
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mt-3">
                                            <label>No Payment Slip Uploaded</label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-end" style="margin-top:20px;">
                                {{-- <button type="submit" class="btn btn-primary" style="width:150px">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Submit
                                </button> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
