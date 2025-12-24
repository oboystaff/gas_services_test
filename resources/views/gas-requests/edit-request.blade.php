@extends('layout.base')

@section('page-styles')
@endsection

@section('page-content')
    @php
        $communityIds = $gasRequest->customer->community_id;

        if (is_string($communityIds)) {
            $decoded = json_decode($communityIds, true);
            $communityIds = is_array($decoded) ? $decoded : [$communityIds];
        } elseif (is_int($communityIds)) {
            $communityIds = [$communityIds];
        }

        $communityNames = \App\Models\Community::whereIn('id', $communityIds)->pluck('name')->implode(', ');
        $communities = \App\Models\Community::whereIn('id', $communityIds)->select('id', 'name')->get();
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
                            <h5 class="card-title">Edit Gas Request</h5>

                            <a href="{{ route('gas-requests.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('gas-requests.updateRequest', $gasRequest) }}">
                            @csrf

                            <input type="hidden" name="kg" />
                            <input type="hidden" name="amount" />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Customer ID</label>
                                        <input type="text"
                                            class="form-control @error('customer_id') is-invalid @enderror"
                                            name="customer_id" placeholder="Customer_id"
                                            value="{{ $gasRequest->customer->customer_id ?? '' }}" readonly />

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
                                            name="name" placeholder="Name"
                                            value="{{ $gasRequest->customer->name ?? '' }}" readonly />

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
                                            name="contact" placeholder="Contact"
                                            value="{{ $gasRequest->customer->contact ?? '' }}" readonly />

                                        @error('contact')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Customer Branch</label>
                                        <input type="text" class="form-control @error('community') is-invalid @enderror"
                                            name="community" placeholder="Community" value="{{ $communityNames ?: 'N/A' }}"
                                            readonly />

                                        @error('community')
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
                                            value="{{ $gasRequest->customer->branch->name ?? '' }}" readonly />

                                        @error('branch')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Delivery Branch</label>
                                        <select
                                            class="form-control default-select @error('delivery_branch') is-invalid @enderror"
                                            name="delivery_branch" required>
                                            <option value="" selected>Select Delivery Branch</option>
                                            @foreach ($communities as $community)
                                                <option value="{{ $community->id }}">
                                                    {{ $community->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('delivery_branch')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="margin-top:20px;">
                                <button type="submit" class="btn btn-primary" style="width:180px">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Update Request
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
@endsection
