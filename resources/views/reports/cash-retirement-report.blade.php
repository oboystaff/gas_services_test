@extends('layout.base')

@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/dist/css/datatable.css') }}" />
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

                <input type="hidden" name="cash-retirement-report_url" url="{{ route('cash-retirement-reports.index') }}">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 10px;">
                            <h5 class="card-title">View Cash Retirement Report</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label>From Date</label>
                                    <input type="date" class="form-control" name="from_date" placeholder="From Date" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label>To Date</label>
                                    <input type="date" class="form-control" name="to_date" placeholder="To Date" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label>Branch</label>
                                    <select class="form-control default-select @error('branch_id') is-invalid @enderror"
                                        name="branch_id">
                                        <option disabled selected>Select Branch</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('branch_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4" style="display:none">
                            <label class="form-label">Report Type</label>
                            <select class="form-select @error('report_type') is-invalid @enderror" id="report_type"
                                name="report_type">
                                <option value="1">Customer Report</option>
                                <option value="2">Summary Report</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-center align-items-center"
                            style="margin-top:20px; height: 100%;margin-bottom: 80px">
                            <button type="submit" class="btn btn-primary generate_report" style="width:180px">
                                <i class="fa fa-paper-plane" aria-hidden="true"></i> Generate Report
                            </button>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Branch Manager</th>
                                        <th>Amount Collected</th>
                                        <th>Sales Date</th>
                                        <th>Amount Retired</th>
                                        <th>Status</th>
                                        <th>Branch</th>
                                        <th>Date Retired</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th id="title"></th>
                                        <th id="total_sales"></th>
                                        <th></th>
                                        <th id="total_retired"></th>
                                        <th colspan="3"></th>
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
    <script src="{{ asset('assets/dist/js/report/cash-retirement-report.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="{{ asset('assets/dist/js/common.js?t=' . time()) }}"></script>
@endsection
