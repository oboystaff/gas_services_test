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
                            <h5 class="card-title">View Customer</h5>

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
                                            name="name" placeholder="Name" value="{{ $customer->name }}" readonly />

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
                                            name="contact" placeholder="Contact" value="{{ $customer->contact }}"
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
                                        <label>Secondary Contact</label>
                                        <input type="text"
                                            class="form-control @error('secondary_contact') is-invalid @enderror"
                                            name="secondary_contact" placeholder="Secondary Contact"
                                            value="{{ $customer->secondary_contact }}" readonly />

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
                                        <input type="text"
                                            class="form-control @error('community_id') is-invalid @enderror"
                                            name="community_id" placeholder="Community"
                                            value="{{ $communityNames ?: 'N/A' }}" readonly />

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
                                        <input type="text" class="form-control @error('branch_id') is-invalid @enderror"
                                            name="branch_id" placeholder="Branch"
                                            value="{{ $customer->branch->name ?? 'N/A' }}" readonly />

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
                                            name="due_date" placeholder="Credit Line" value="{{ $customer->due_date }}"
                                            readonly />

                                        @error('due_date')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="margin-top:20px;">
                                {{-- <button type="submit" class="btn btn-primary" style="width:150px">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Update
                                </button> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
