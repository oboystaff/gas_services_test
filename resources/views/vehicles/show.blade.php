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
                            <h5 class="card-title">View Vehicle</h5>

                            <a href="{{ route('vehicles.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('vehicles.update', $vehicle) }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Driver</label>
                                        <input type="text" class="form-control @error('driver_id') is-invalid @enderror"
                                            name="driver_id" placeholder="Driver Name"
                                            value="{{ $vehicle->driver->name ?? 'N/A' }}" readonly />

                                        @error('driver_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Vehicle Number</label>
                                        <input type="text"
                                            class="form-control @error('vehicle_number') is-invalid @enderror"
                                            name="vehicle_number" placeholder="Vehicle Number"
                                            value="{{ $vehicle->vehicle_number }}" readonly />

                                        @error('vehicle_number')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label>Vehicle Brand</label>
                                        <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                            name="brand" placeholder="Vehicle Brand" value="{{ $vehicle->brand }}"
                                            readonly />

                                        @error('brand')
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
