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
                            <h5 class="card-title">Mark Request As Done</h5>

                            <a href="{{ route('gas-requests.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('gas-requests.markDoneStore', $gasRequest) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="rate" value="{{ $rate }}" />
                            <input type="hidden" name="kg" />
                            <input type="hidden" name="amount" />
                            <input type="hidden" name="customer_url" url="{{ route('gas-requests.fetch') }}" />

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
                                            name="delivery_branch" placeholder="Delivery Branch"
                                            value="{{ $gasRequest->deliveryBranch->name ?? 'N/A' }}" readonly />

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
                                        <label>Gas Quantity Type</label>
                                        <select
                                            class="form-control default-select @error('quantity_type') is-invalid @enderror"
                                            name="quantity_type">
                                            <option value="" selected>Select Gas Quantity Type</option>
                                            <option value="KG">By KG</option>
                                            <option value="GHS">By GHS</option>
                                        </select>

                                        @error('quantity_type')
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
                                        <label id="value1_label">Gas Quantity (KG / GHS)</label>
                                        <input type="text" class="form-control @error('value1') is-invalid @enderror"
                                            name="value1" placeholder="Gas Quantity (KG / GHS)" />

                                        @error('value1')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label id="value2_label">Amount/KG</label>
                                        <input type="text" class="form-control @error('value2') is-invalid @enderror"
                                            name="value2" placeholder="Final KG" readonly />

                                        @error('value2')
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
                                        <label>Delivery Driver</label>
                                        <input type="text"
                                            class="form-control @error('driver_id') is-invalid @enderror" name="driver_id"
                                            placeholder="Delivery Driver"
                                            value="{{ $gasRequest->driverAssigned->name ?? 'N/A' }}" readonly />

                                        @error('driver_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Rep Name</label>
                                        <input type="text" class="form-control @error('rep_name') is-invalid @enderror"
                                            name="rep_name" placeholder="Rep Name"
                                            value="{{ $gasRequest->rep_name ?? '' }}" />

                                        @error('rep_name')
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
                                        <label>Rep Contact</label>
                                        <input type="text"
                                            class="form-control @error('rep_contact') is-invalid @enderror"
                                            name="rep_contact" placeholder="Rep Contact"
                                            value="{{ $gasRequest->rep_contact ?? '' }}" />

                                        @error('rep_contact')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Upload File (optional)</label>
                                        <input type="file"
                                            class="form-control @error('attachment') is-invalid @enderror"
                                            name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" />

                                        @error('attachment')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="margin-top:20px;">
                                <button type="submit" class="btn btn-primary" style="width:180px">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Mark As Done
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
