@extends('layout.base')

@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" />
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

                @if (session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ session('error') }}</strong>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 30px;">
                            <h5 class="card-title">View Gas Requests</h5>

                            {{-- <a href="{{ route('gas-requests.create') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-plus" aria-hidden="true"></i> New Gas Request
                            </a> --}}
                        </div>

                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Customer ID</th>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Request Contact</th>
                                        <th>Customer Branch</th>
                                        <th>Delivery Branch</th>
                                        <th>Outlet</th>
                                        <th>KG</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Driver Assigned</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gasRequests as $index => $gasRequest)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $gasRequest->customer_id ?? 'N/A' }}</td>
                                            <td>{{ $gasRequest->name ?? 'N/A' }}</td>
                                            <td>{{ $gasRequest->contact ?? 'N/A' }}</td>
                                            <td>{{ $gasRequest->request_contact ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $communityIds = $gasRequest->community_id;

                                                    if (is_string($communityIds)) {
                                                        $decoded = json_decode($communityIds, true);
                                                        $communityIds = is_array($decoded) ? $decoded : [$communityIds];
                                                    } elseif (is_int($communityIds)) {
                                                        $communityIds = [$communityIds];
                                                    }

                                                    $communityNames = \App\Models\Community::whereIn(
                                                        'id',
                                                        $communityIds,
                                                    )
                                                        ->pluck('name')
                                                        ->toArray();
                                                @endphp

                                                {{ implode(', ', $communityNames) ?: 'N/A' }}
                                            </td>
                                            <td>{{ $gasRequest->deliveryBranch->name ?? 'N/A' }}</td>
                                            <td>{{ $gasRequest->branch->name ?? 'N/A' }}</td>
                                            <td>{{ $gasRequest->kg }}</td>
                                            <td>{{ number_format($gasRequest->amount, 2) }}</td>
                                            <td>{{ $gasRequest->status }}</td>
                                            <td>{{ $gasRequest->driverAssigned->name ?? 'N/A' }}</td>
                                            <td>{{ $gasRequest->createdBy->name ?? 'N/A' }}</td>
                                            <td>{{ $gasRequest->created_at }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <div class="btn-link" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg width="24" height="24" viewBox="0 0 24 24"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12Z"
                                                                stroke="#737B8B" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                            </path>
                                                            <path
                                                                d="M18 12C18 12.5523 18.4477 13 19 13C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11C18.4477 11 18 11.4477 18 12Z"
                                                                stroke="#737B8B" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                            </path>
                                                            <path
                                                                d="M4 12C4 12.5523 4.44772 13 5 13C5.55228 13 6 12.5523 6 12C6 11.4477 5.55228 11 5 11C4.44772 11 4 11.4477 4 12Z"
                                                                stroke="#737B8B" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <div class="py-2">
                                                            @if ($gasRequest->driver_assigned === null && $gasRequest->status === 'Request Approved')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('gas-requests.assignAgent', $gasRequest) }}">Assign
                                                                    Driver
                                                                </a>
                                                            @endif

                                                            @can('gas-requests.approve')
                                                                @if ($gasRequest->driver_assigned === null && $gasRequest->status === 'Pending')
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('gas-requests.getApproveRequest', $gasRequest) }}">Approve
                                                                        Request
                                                                    </a>
                                                                @endif
                                                            @endcan

                                                            @can('gas-requests.reverse')
                                                                @if ($gasRequest->status == 'Driver Assigned')
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('gas-requests.getReverseRequest', $gasRequest) }}">Reverse
                                                                        Request
                                                                    </a>
                                                                @endif
                                                            @endcan

                                                            @if ($gasRequest->status === 'Driver Assigned' && $gasRequest->driver_assigned !== null)
                                                                <a class="dropdown-item"
                                                                    href="{{ route('gas-requests.markDone', $gasRequest) }}">Mark
                                                                    As Done
                                                                </a>
                                                            @endif

                                                            @if ($gasRequest->status == 'Gas Delivered' || $gasRequest->status == 'Work Done')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('gas-requests.raiseInvoice', $gasRequest) }}">Raise
                                                                    Invoice
                                                                </a>
                                                            @endif

                                                            @if ($gasRequest->delivery_branch == null || $gasRequest->delivery_branch == '')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('gas-requests.editRequest', $gasRequest) }}">Edit
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7"></th>
                                        <th>Total:</th>
                                        <th>{{ $total['kg'] }}</th>
                                        <th>{{ $total['amount'] }}</th>
                                        <th colspan="5"></th>
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
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="{{ asset('assets/dist/js/common.js?t=' . time()) }}"></script>

    <script>
        $(".dropdown").on("shown.bs.dropdown", function() {
            var $dropdownMenu = $(this).find(".dropdown-menu");
            var dropdownOffset = $dropdownMenu.offset();
            var dropdownWidth = $dropdownMenu.outerWidth();
            var windowWidth = $(window).width();
            var windowHeight = $(window).height();
            var scrollTop = $(window).scrollTop();

            $dropdownMenu.removeClass("adjusted").css({
                top: "",
                left: "",
                right: "",
            });

            if (dropdownOffset.left + dropdownWidth > windowWidth) {
                $dropdownMenu.addClass("adjusted").css({
                    left: windowWidth - dropdownWidth - 20 + "px",
                });
            } else if (dropdownOffset.left < 0) {
                $dropdownMenu.addClass("adjusted").css({
                    left: "20px",
                });
            }

            if (dropdownOffset.top + $dropdownMenu.outerHeight() - scrollTop > windowHeight) {
                $dropdownMenu.addClass("adjusted").css({
                    top: windowHeight - $dropdownMenu.outerHeight() - 20 + scrollTop + "px",
                });
            }
        });
    </script>
@endsection
