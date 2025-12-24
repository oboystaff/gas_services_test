@extends('layout.base')

@section('page-styles')
@endsection

@section('page-content')
    @php
        $communityIds = $gasRequest->community_id;

        if (is_string($communityIds)) {
            $decoded = json_decode($communityIds, true);
            $communityIds = is_array($decoded) ? $decoded : [$communityIds];
        } elseif (is_int($communityIds)) {
            $communityIds = [$communityIds];
        }

        $communityNames = \App\Models\Community::whereIn('id', $communityIds)->pluck('name')->implode(', ');
        $communities = \App\Models\Community::whereIn('id', $communityIds)->get();
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
                            <h5 class="card-title">Assign Delivery Driver</h5>

                            <a href="{{ route('gas-requests.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('gas-requests.assignAgentStore', $gasRequest) }}">
                            @csrf

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
                                        <label>Customer Branch</label>
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

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Delivery Customer Branch</label>
                                        <input type="text"
                                            class="form-control @error('delivery_branch') is-invalid @enderror"
                                            name="delivery_branch" placeholder="Delivery branch"
                                            value="{{ $gasRequest->deliveryBranch->name ?? '' }}" readonly />

                                        @error('delivery_branch')
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

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label id="value1_label">Gas Quantity (KG)</label>
                                        <input type="text" class="form-control @error('value1') is-invalid @enderror"
                                            name="value1" placeholder="Gas Quantity (KG)"
                                            value="{{ $gasRequest->kg ?? '0' }}" readonly />

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
                                        <label>Delivery Driver</label>
                                        <select
                                            class="form-control default-select @error('driver_assigned') is-invalid @enderror"
                                            name="driver_assigned">
                                            <option disabled selected>Select Delivery Driver</option>
                                            @foreach ($drivers as $driver)
                                                <option value="{{ $driver->id }}">
                                                    {{ $driver->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('driver_assigned')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="margin-top:20px;">
                                <button type="submit" class="btn btn-primary" style="width:180px">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Assign Driver
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
@endsection
