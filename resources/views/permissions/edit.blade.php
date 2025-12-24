@extends('layout.base')

@section('page-styles')
@endsection

@section('page-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 20px;">
                            <h5 class="card-title">Permissions Management</h5>
                            <span>Assign Permission to Role</span>

                            <a href="{{ route('permissions.index') }}" type="button" class="btn btn-primary">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </a>
                        </div>

                        <form class="row g-3 needs-validation" method="POST"
                            action="{{ route('permissions.update', $role->id) }}">
                            @csrf

                            <div class="col-md-12">
                                <label for="exampleFormControlInput1">Role Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="exampleFormControlInput1" name="name" value="{{ $role->name }}" readonly>
                            </div>

                            <label for="permissions" class="form-label" style="margin-top:50px">Assign Permissions</label>
                            <hr />

                            <!-- dashboard -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.dashboards')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('dashboards.operational')" @if (in_array('dashboards.operational', $role_permissions)) checked @endif
                                                type="checkbox">
                                            @lang('role.dashboards.operational')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('dashboards.financial')" @if (in_array('dashboards.financial', $role_permissions)) checked @endif
                                                type="checkbox">
                                            @lang('role.dashboards.financial')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- users -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.users')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('users.view')" @if (in_array('users.view', $role_permissions)) checked @endif
                                                type="checkbox">
                                            @lang('role.users.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('users.create')" @if (in_array('users.create', $role_permissions)) checked @endif
                                                type="checkbox">
                                            @lang('role.users.create')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('users.update')" @if (in_array('users.update', $role_permissions)) checked @endif
                                                type="checkbox">
                                            @lang('role.users.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- roles-->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.user_roles')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('roles.view')" @if (in_array('roles.view', $role_permissions)) checked @endif
                                                type="checkbox">
                                            @lang('role.roles.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('roles.create')" @if (in_array('roles.create', $role_permissions)) checked @endif
                                                type="checkbox">
                                            @lang('role.roles.create')
                                        </label>
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('roles.update')" @if (in_array('roles.update', $role_permissions)) checked @endif
                                                type="checkbox">
                                            @lang('role.roles.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- permissions -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.permissions')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('permissions.view')" @if (in_array('permissions.view', $role_permissions)) checked @endif
                                                type="checkbox">
                                            @lang('role.permissions.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('permissions.create')"
                                                @if (in_array('permissions.create', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.permissions.create')
                                        </label>
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('permissions.update')"
                                                @if (in_array('permissions.update', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.permissions.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- branches -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.branches')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('branches.view')"
                                                @if (in_array('branches.view', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.branches.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('branches.create')"
                                                @if (in_array('branches.create', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.branches.create')
                                        </label>
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('branches.update')"
                                                @if (in_array('branches.update', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.branches.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- communities -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.communities')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('communities.view')"
                                                @if (in_array('communities.view', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.communities.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('communities.create')"
                                                @if (in_array('communities.create', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.communities.create')
                                        </label>
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('communities.update')"
                                                @if (in_array('communities.update', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.communities.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- vehicles -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.rates')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('rates.view')"
                                                @if (in_array('rates.view', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.rates.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('rates.create')"
                                                @if (in_array('rates.create', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.rates.create')
                                        </label>
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('rates.update')"
                                                @if (in_array('rates.update', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.rates.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- customers -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.customers')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('customers.view')"
                                                @if (in_array('customers.view', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.customers.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('customers.create')"
                                                @if (in_array('customers.create', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.customers.create')
                                        </label>
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('customers.update')"
                                                @if (in_array('customers.update', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.customers.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- job orders -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.drivers')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('drivers.view')"
                                                @if (in_array('drivers.view', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.drivers.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('drivers.create')"
                                                @if (in_array('drivers.create', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.drivers.create')
                                        </label>
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('drivers.update')"
                                                @if (in_array('drivers.update', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.drivers.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.vehicles')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('vehicles.view')"
                                                @if (in_array('vehicles.view', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.vehicles.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('vehicles.create')"
                                                @if (in_array('vehicles.create', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.vehicles.create')
                                        </label>
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('vehicles.update')"
                                                @if (in_array('vehicles.update', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.vehicles.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- proformas -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.gas-requests')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('gas-requests.view')"
                                                @if (in_array('gas-requests.view', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.gas-requests.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('gas-requests.create')"
                                                @if (in_array('gas-requests.create', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.gas-requests.create')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('gas-requests.update')"
                                                @if (in_array('gas-requests.update', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.gas-requests.update')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('gas-requests.approve')"
                                                @if (in_array('gas-requests.approve', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.gas-requests.approve')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('gas-requests.reverse')"
                                                @if (in_array('gas-requests.reverse', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.gas-requests.reverse')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- invoices -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.invoices')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('invoices.view')"
                                                @if (in_array('invoices.view', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.invoices.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('invoices.create')"
                                                @if (in_array('invoices.create', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.invoices.create')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('invoices.update')"
                                                @if (in_array('invoices.update', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.invoices.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- payments -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.payments')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('payments.view')"
                                                @if (in_array('payments.view', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.payments.view')
                                        </label>
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('payments.create')"
                                                @if (in_array('payments.create', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.payments.create')
                                        </label>
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('payments.update')"
                                                @if (in_array('payments.update', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.payments.update')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <!-- reports -->
                            <div class="check_group">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h5>@lang('role.reports')</h5>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <label class="d-block" for="chk-ani">
                                            <input class="form-check-input check_all" id="chk-ani " type="checkbox">
                                            @lang('role.select_all')
                                        </label>

                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <label for="chk-ani">
                                            <input class="form-check-input" name="permissions[]" id="chk-ani"
                                                value="@lang('reports.view')"
                                                @if (in_array('reports.view', $role_permissions)) checked @endif type="checkbox">
                                            @lang('role.reports.view')
                                        </label>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary" type="submit">Update Permission</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script src="{{ asset('assets/dist/js/permission/role.js') }}"></script>
@endsection
