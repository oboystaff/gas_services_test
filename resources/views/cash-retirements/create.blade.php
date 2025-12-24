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
                            <h5 class="card-title">Retire Cash</h5>

                            <a href="{{ route('cash-retirements.index') }}" type="button" class="btn btn-primary">
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

                            <input type="hidden" name="sales_date" value="{{ $salesDate }}" />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Bank Name</label>
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                            name="bank_name" placeholder="Bank Name" />

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
                                            name="branch_name" placeholder="Bank Branch Name" />

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
                                            name="account_number" placeholder="Account Number" />

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
                                            name="amount_retired" placeholder="Amount Retired" />

                                        @error('amount_retired')
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
                                        <label>Upload Payment Slip</label>
                                        <input type="file"
                                            class="form-control @error('payment_slip') is-invalid @enderror"
                                            name="payment_slip" placeholder="Payment Slip" accept="image/*" />

                                        @error('payment_slip')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="margin-top:20px;">
                                <button type="submit" class="btn btn-primary" style="width:150px">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
