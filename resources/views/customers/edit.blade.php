@extends('layout.base')

@section('page-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                            <h5 class="card-title">Edit Customer</h5>

                            <a href="{{ route('customers.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('customers.update', $customer) }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" placeholder="Name" value="{{ $customer->name }}" />

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
                                            name="contact" placeholder="Contact" value="{{ $customer->contact }}" />

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
                                            name="secondary_contact" placeholder="Secondary Contact"
                                            value="{{ $customer->secondary_contact }}" />

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
                                                <option value="{{ $community->id }}"
                                                    {{ in_array($community->id, $communityIds ?? []) ? 'selected' : '' }}>
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
                                            <option disabled selected>Select Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ old('branch_id', $customer->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
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
                                    <div class="form-group" style="margin-top:-10px">
                                        <div class="form-check">
                                            <input type="checkbox" id="threshold_check" class="form-check-input"
                                                name="threshold" value="Y"
                                                {{ old('threshold', $customer->threshold_amount ? true : false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="threshold_check">
                                                Enable Threshold
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group threshold-amount-group"
                                        style="margin-top:-20px; {{ old('threshold', $customer->threshold_amount ? true : false) ? '' : 'display:none;' }}">
                                        <label>Threshold Amount</label>
                                        <input type="text"
                                            class="form-control @error('threshold_amount') is-invalid @enderror"
                                            name="threshold_amount" id="threshold_amount"
                                            placeholder="Enter threshold amount"
                                            value="{{ old('threshold_amount', $customer->threshold_amount) }}" />

                                        @error('threshold_amount')
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
                                        <label id="due_date">Credit Line</label>
                                        <input type="text" class="form-control @error('due_date') is-invalid @enderror"
                                            name="due_date" placeholder="Credit Line" value="{{ $customer->due_date }}" />

                                        @error('due_date')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label id="due_date">Recovery Officer</label>
                                        <select
                                            class="form-control default-select @error('recovery_officer_id') is-invalid @enderror"
                                            name="recovery_officer_id">
                                            <option disabled selected>Select Recovery Officer</option>
                                            @foreach ($recoveryOfficers as $recoveryOfficer)
                                                <option value="{{ $recoveryOfficer->id }}"
                                                    {{ old('recovery_officer_id', $customer->recovery_officer_id ?? '') == $recoveryOfficer->id ? 'selected' : '' }}>
                                                    {{ $recoveryOfficer->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('recovery_officer_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="margin-top:20px;">
                                <button type="submit" class="btn btn-primary" style="width:150px">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Update
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

            $('#threshold_check').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.threshold-amount-group').show();
                } else {
                    $('.threshold-amount-group').hide();
                    $('#threshold_amount').val('');
                }
            });
        });
    </script>
@endsection
