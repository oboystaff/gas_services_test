@extends('layout.base')

@section('page-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                            <h5 class="card-title">Create Customer</h5>

                            <a href="{{ route('customers.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('customers.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" placeholder="Name" />

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
                                            name="contact" placeholder="Contact" />

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
                                        <label>Secondary Contact</label>
                                        <input type="text"
                                            class="form-control @error('secondary_contact') is-invalid @enderror"
                                            name="secondary_contact" placeholder="Secondary Contact" />

                                        @error('secondary_contact')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Customer Branch</label>
                                        <select
                                            class="form-control default-select select2 @error('community_id') is-invalid @enderror"
                                            name="community_id[]" multiple>
                                            @foreach ($communities as $community)
                                                <option value="{{ $community->id }}">
                                                    {{ $community->name }}
                                                </option>
                                            @endforeach
                                        </select>

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
                                        <label>Outlet</label>
                                        <select class="form-control default-select @error('branch_id') is-invalid @enderror"
                                            name="branch_id">
                                            <option disabled selected>Select Outlet</option>
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

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label id="due_date">Credit Line</label>
                                        <input type="text" class="form-control @error('due_date') is-invalid @enderror"
                                            name="due_date" placeholder="Credit Line" />

                                        @error('due_date')
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

@section('page-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select Customer Branch",
                allowClear: true
            });
        });
    </script>
@endsection
