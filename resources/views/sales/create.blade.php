@extends('layout.base')

@section('page-styles')
    <link rel="stylesheet" href="{{ asset('assets/dist/css/autocomplete.css') }}">
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
                            <h5 class="card-title">Create Gas Sale</h5>

                            <a href="{{ route('sales.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('sales.store') }}">
                            @csrf

                            <input type="hidden" name="rate" value="{{ $rate }}" />
                            <input type="hidden" name="kg" />
                            <input type="hidden" name="amount" />
                            <input type="hidden" name="customer_url" url="{{ route('sales.fetch') }}" />

                            @if ($rate == 0)
                                <div class="alert alert-danger" role="alert">
                                    <p>There is no rate created, therefore you cannot make any gas request for now, kindly
                                        contact the admin.</p>
                                </div>
                            @else
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mt-3">
                                            <label>Enter Existing Customer ID</label>
                                            <div class="autocomplete">
                                                <input type="text" id="customer"
                                                    class="form-control @error('customer') is-invalid @enderror"
                                                    name="customer" placeholder="Enter Existing Customer ID" />
                                            </div>

                                            @error('customer')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mt-3">
                                            <label>Customer ID</label>
                                            <input type="text"
                                                class="form-control @error('customer_id') is-invalid @enderror"
                                                name="customer_id" placeholder="Customer ID" readonly />

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
                                                name="name" placeholder="Name" />

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
                                            <input type="text"
                                                class="form-control @error('contact') is-invalid @enderror" name="contact"
                                                placeholder="Contact" />

                                            @error('contact')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mt-3">
                                            <label>Community</label>
                                            <select
                                                class="form-control default-select @error('community_id') is-invalid @enderror"
                                                name="community_id">
                                                <option disabled selected>Select Community</option>
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
                                            <label>Branch</label>
                                            <select
                                                class="form-control default-select @error('branch_id') is-invalid @enderror"
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
                                            <input type="text"
                                                class="form-control @error('value2') is-invalid @enderror" name="value2"
                                                placeholder="Final KG" readonly />

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
                                            <label style="color:red">Service Charge (Provide service charge if the sale is
                                                coming from a request)</label>
                                            <input type="text"
                                                class="form-control @error('service_charge') is-invalid @enderror"
                                                name="service_charge" placeholder="Service Charge" />

                                            @error('service_charge')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end" style="margin-top:20px;">
                                    <button type="submit" class="btn btn-primary" style="width:180px">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i> Submit Sale
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script src="{{ asset('assets/dist/js/gas.js?v1=5678') }}"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function autocomplete(inp, arr) {
                var currentFocus;

                inp.addEventListener("input", function(e) {
                    var a, b, i, val = this.value;

                    closeAllLists();
                    if (!val) {
                        return false;
                    }
                    currentFocus = -1;

                    a = document.createElement("DIV");
                    a.setAttribute("id", this.id + "autocomplete-list");
                    a.setAttribute("class", "autocomplete-items");

                    this.parentNode.appendChild(a);

                    for (i = 0; i < arr.length; i++) {
                        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                            b = document.createElement("DIV");
                            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                            b.innerHTML += arr[i].substr(val.length);
                            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";

                            b.addEventListener("click", function(e) {
                                inp.value = this.getElementsByTagName("input")[0].value;

                                fetch_customer(inp.value);

                                closeAllLists();
                            });
                            a.appendChild(b);
                        }
                    }
                });

                inp.addEventListener("keydown", function(e) {
                    var x = document.getElementById(this.id + "autocomplete-list");
                    if (x) x = x.getElementsByTagName("div");
                    if (e.keyCode == 40) {
                        currentFocus++;

                        addActive(x);
                    } else if (e.keyCode == 38) {

                        currentFocus--;
                        addActive(x);
                    } else if (e.keyCode == 13) {
                        e.preventDefault();
                        if (currentFocus > -1) {
                            if (x) x[currentFocus].click();
                        }
                    }
                });

                function addActive(x) {

                    if (!x) return false;
                    removeActive(x);
                    if (currentFocus >= x.length) currentFocus = 0;
                    if (currentFocus < 0) currentFocus = (x.length - 1);

                    x[currentFocus].classList.add("autocomplete-active");
                }

                function removeActive(x) {
                    for (var i = 0; i < x.length; i++) {
                        x[i].classList.remove("autocomplete-active");
                    }
                }

                function closeAllLists(elmnt) {
                    var x = document.getElementsByClassName("autocomplete-items");
                    for (var i = 0; i < x.length; i++) {
                        if (elmnt != x[i] && elmnt != inp) {
                            x[i].parentNode.removeChild(x[i]);
                        }
                    }
                }

                document.addEventListener("click", function(e) {
                    closeAllLists(e.target);
                });
            }

            var customers = @json($customers);

            autocomplete(document.getElementById("customer"), customers);

            function fetch_customer(customer_id) {
                var url = $("input[name='customer_url']").attr("url");
                var formData = new FormData();
                formData.append("customer_id", customer_id);

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('input[name="customer_id"]').val(response.message.customer_id);
                        $('input[name="name"]').val(response.message.name);
                        $('input[name="contact"]').val(response.message.contact);
                        $('select[name="community_id"]').val(response.message.community_id);
                        $('select[name="branch_id"]').val(response.message.branch_id);
                    }
                });
            }
        });
    </script>
@endsection
