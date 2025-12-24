<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\User;
use App\Http\Controllers\Branch;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Community;
use App\Http\Controllers\Customer;
use App\Http\Controllers\Rate;
use App\Http\Controllers\GasRequest;
use App\Http\Controllers\Agent;
use App\Http\Controllers\Sale;
use App\Http\Controllers\Report;
use App\Http\Controllers\Role;
use App\Http\Controllers\Permission;
use App\Http\Controllers\CashRetirement;
use App\Http\Controllers\Driver;
use App\Http\Controllers\Vehicle;
use App\Http\Controllers\Invoice;
use App\Http\Controllers\Payment;
use App\Http\Controllers\Notification;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [Auth\LoginController::class, 'index'])->name('auth.index');
Route::post('/login', [Auth\LoginController::class, 'login'])->name('auth.login');
Route::get('/logout', [Auth\LoginController::class, 'logout'])->name('auth.logout');
Route::get('/customer/invoice/{invoice}', [Invoice\InvoiceController::class, 'show'])->name('customer_invoice.show');
Route::get('/customer/receipt/{payment}', [Payment\PaymentController::class, 'generateReceipt'])->name('customer_payment.receipt');

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/operational', [Dashboard\DashboardController::class, 'operational'])->name('dashboard.operational');
    Route::get('/sale/summary', [Dashboard\DashboardController::class, 'saleSummary'])->name('dashboard.saleSummary');
    Route::get('/receivables', [Dashboard\DashboardController::class, 'debtors'])->name('dashboard.debtors');
});

Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [User\UserController::class, 'index'])->name('users.index');
    Route::get('/create', [User\UserController::class, 'create'])->name('users.create');
    Route::post('/create', [User\UserController::class, 'store'])->name('users.store');
    Route::get('/show/{user}', [User\UserController::class, 'show'])->name('users.show');
    Route::get('/edit/{user}', [User\UserController::class, 'edit'])->name('users.edit');
    Route::post('/update/{user}', [User\UserController::class, 'update'])->name('users.update');
});

Route::group(['prefix' => 'branches', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Branch\BranchController::class, 'index'])->name('branches.index');
    Route::get('/create', [Branch\BranchController::class, 'create'])->name('branches.create');
    Route::post('/create', [Branch\BranchController::class, 'store'])->name('branches.store');
    Route::get('/show/{branch}', [Branch\BranchController::class, 'show'])->name('branches.show');
    Route::get('/edit/{branch}', [Branch\BranchController::class, 'edit'])->name('branches.edit');
    Route::post('/update/{branch}', [Branch\BranchController::class, 'update'])->name('branches.update');
});

Route::group(['prefix' => 'communities', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Community\CommunityController::class, 'index'])->name('communities.index');
    Route::get('/create', [Community\CommunityController::class, 'create'])->name('communities.create');
    Route::post('/create', [Community\CommunityController::class, 'store'])->name('communities.store');
    Route::get('/show/{community}', [Community\CommunityController::class, 'show'])->name('communities.show');
    Route::get('/edit/{community}', [Community\CommunityController::class, 'edit'])->name('communities.edit');
    Route::post('/update/{community}', [Community\CommunityController::class, 'update'])->name('communities.update');
});

Route::group(['prefix' => 'customer', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Customer\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/create', [Customer\CustomerController::class, 'create'])->name('customers.create');
    Route::post('/create', [Customer\CustomerController::class, 'store'])->name('customers.store');
    Route::get('/show/{customer}', [Customer\CustomerController::class, 'show'])->name('customers.show');
    Route::get('/edit/{customer}', [Customer\CustomerController::class, 'edit'])->name('customers.edit');
    Route::post('/update/{customer}', [Customer\CustomerController::class, 'update'])->name('customers.update');
    Route::get('/gas/request/{customer}', [Customer\CustomerController::class, 'gasRequest'])->name('customers.gasRequest');
    Route::post('/gas/request/{customer}', [Customer\CustomerController::class, 'gasRequestStore'])->name('customers.gasRequestStore');
    Route::get('/statement/{customer}', [Customer\CustomerController::class, 'statement'])->name('customers.statement');
    Route::get('/statement/pdf/{customer}', [Customer\CustomerController::class, 'downloadStatementPDF'])->name('customers.statement.pdf');
    Route::get('/make/payment/{customer}', [Customer\CustomerController::class, 'makePayment'])->name('customers.makePayment');
    Route::post('/credit/debit', [Customer\CustomerController::class, 'creditDebit'])->name('customers.creditDebit');
});

Route::group(['prefix' => 'rates', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Rate\RateController::class, 'index'])->name('rates.index');
    Route::get('/create', [Rate\RateController::class, 'create'])->name('rates.create');
    Route::post('/create', [Rate\RateController::class, 'store'])->name('rates.store');
    Route::get('/show/{rate}', [Rate\RateController::class, 'show'])->name('rates.show');
    Route::get('/edit/{rate}', [Rate\RateController::class, 'edit'])->name('rates.edit');
    Route::post('/update/{rate}', [Rate\RateController::class, 'update'])->name('rates.update');
});

Route::group(['prefix' => 'gas-requests', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [GasRequest\GasRequestController::class, 'index'])->name('gas-requests.index');
    Route::get('/create', [GasRequest\GasRequestController::class, 'create'])->name('gas-requests.create');
    Route::post('/create', [GasRequest\GasRequestController::class, 'store'])->name('gas-requests.store');
    Route::get('/show/{gasRequest}', [GasRequest\GasRequestController::class, 'show'])->name('gas-requests.show');
    Route::get('/edit/{gasRequest}', [GasRequest\GasRequestController::class, 'edit'])->name('gas-requests.edit');
    Route::post('/update/{gasRequest}', [GasRequest\GasRequestController::class, 'update'])->name('gas-requests.update');
    Route::get('/assign/agent/{gasRequest}', [GasRequest\GasRequestController::class, 'assignAgent'])->name('gas-requests.assignAgent');
    Route::post('/assign/agent/{gasRequest}', [GasRequest\GasRequestController::class, 'assignAgentStore'])->name('gas-requests.assignAgentStore');
    Route::get('/mark/done/{gasRequest}', [GasRequest\GasRequestController::class, 'markDone'])->name('gas-requests.markDone');
    Route::post('/mark/done/{gasRequest}', [GasRequest\GasRequestController::class, 'markDoneStore'])->name('gas-requests.markDoneStore');
    Route::post('/fetch/customers', [GasRequest\GasRequestController::class, 'fetchCustomer'])->name('gas-requests.fetch');
    Route::get('/invoice/{gasRequest}', [GasRequest\GasRequestController::class, 'raiseInvoice'])->name('gas-requests.raiseInvoice');
    Route::get('/edit-request/{gasRequest}', [GasRequest\GasRequestController::class, 'editRequest'])->name('gas-requests.editRequest');
    Route::post('/update-request/{gasRequest}', [GasRequest\GasRequestController::class, 'updateRequest'])->name('gas-requests.updateRequest');
    Route::get('/approve-request/{gasRequest}', [GasRequest\GasRequestController::class, 'getApproveRequest'])->name('gas-requests.getApproveRequest');
    Route::post('/approve-request/{gasRequest}', [GasRequest\GasRequestController::class, 'approveRequest'])->name('gas-requests.approveRequest');
    Route::get('/reverse-request/{gasRequest}', [GasRequest\GasRequestController::class, 'getReverseRequest'])->name('gas-requests.getReverseRequest');
    Route::post('/reverse-request/{gasRequest}', [GasRequest\GasRequestController::class, 'reverseRequest'])->name('gas-requests.reverseRequest');
});

Route::group(['prefix' => 'agents', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Agent\AgentController::class, 'index'])->name('agents.index');
    Route::get('/create', [Agent\AgentController::class, 'create'])->name('agents.create');
    Route::post('/create', [Agent\AgentController::class, 'store'])->name('agents.store');
    Route::get('/show/{agent}', [Agent\AgentController::class, 'show'])->name('agents.show');
    Route::get('/edit/{agent}', [Agent\AgentController::class, 'edit'])->name('agents.edit');
    Route::post('/update/{agent}', [Agent\AgentController::class, 'update'])->name('agents.update');
});

Route::group(['prefix' => 'sales', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Sale\SaleController::class, 'index'])->name('sales.index');
    Route::get('/create', [Sale\SaleController::class, 'create'])->name('sales.create');
    Route::post('/create', [Sale\SaleController::class, 'store'])->name('sales.store');
    Route::get('/show/{sale}', [Sale\SaleController::class, 'show'])->name('sales.show');
    Route::get('/edit/{sale}', [Sale\SaleController::class, 'edit'])->name('sales.edit');
    Route::post('/update/{sale}', [Sale\SaleController::class, 'update'])->name('sales.update');
    Route::post('/fetch/customers', [Sale\SaleController::class, 'fetchCustomer'])->name('sales.fetch');
    Route::get('/print/{sale}', [Sale\SaleController::class, 'printReceipt'])->name('sales.print');
});

Route::group(['prefix' => 'reports', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/customer-report', [Report\CustomerReportController::class, 'index'])->name('customer-reports.index');
    Route::get('/gas-request-report', [Report\GasRequestReportController::class, 'index'])->name('gas-request-reports.index');
    Route::get('/sale-report', [Report\SaleReportController::class, 'index'])->name('sale-reports.index');
    Route::get('/agent-report', [Report\AgentReportController::class, 'index'])->name('agent-reports.index');
    Route::get('/cash-retiremnet-report', [Report\CashRetirementReportController::class, 'index'])->name('cash-retirement-reports.index');
    Route::get('/invoice-report', [Report\InvoiceReportController::class, 'index'])->name('invoice-reports.index');
    Route::get('/receivables-report', [Report\ReceivablesReportController::class, 'index'])->name('receivables-reports.index');
    Route::get('/payment-report', [Report\PaymentReportController::class, 'index'])->name('payment-reports.index');
});

Route::group(['prefix' => 'role', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Role\RoleController::class, 'index'])->name('roles.index');
    Route::get('/create', [Role\RoleController::class, 'create'])->name('roles.create');
    Route::get('/show/{role}', [Role\RoleController::class, 'show'])->name('roles.show');
    Route::get('/edit/{role}', [Role\RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/create', [Role\RoleController::class, 'store'])->name('roles.store');
    Route::post('/update/{role}', [Role\RoleController::class, 'update'])->name('roles.update');
});

Route::group(['prefix' => 'permission', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Permission\PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/create', [Permission\PermissionController::class, 'create'])->name('permissions.create');
    Route::get('/show/{permission}', [Permission\PermissionController::class, 'show'])->name('permissions.show');
    Route::get('/edit/{permission}', [Permission\PermissionController::class, 'edit'])->name('permissions.edit');
    Route::post('/create', [Permission\PermissionController::class, 'store'])->name('permissions.store');
    Route::post('/update/{permission}', [Permission\PermissionController::class, 'update'])->name('permissions.update');
});

Route::group(['prefix' => 'cash-retirements', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [CashRetirement\CashRetirementController::class, 'index'])->name('cash-retirements.index');
    Route::get('/create', [CashRetirement\CashRetirementController::class, 'create'])->name('cash-retirements.create');
    Route::post('/create', [CashRetirement\CashRetirementController::class, 'store'])->name('cash-retirements.store');
    Route::get('/show/{cashRetirement}', [CashRetirement\CashRetirementController::class, 'show'])->name('cash-retirements.show');
    Route::get('/edit/{cashRetirement}', [CashRetirement\CashRetirementController::class, 'edit'])->name('cash-retiremnets.edit');
    Route::post('/update/{cashRetirement}', [CashRetirement\CashRetirementController::class, 'update'])->name('cash-retirements.update');
    Route::get('/index', [CashRetirement\CashRetirementController::class, 'retiredCash'])->name('cash-retirements.retiredCash');
});

Route::group(['prefix' => 'driver', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Driver\DriverController::class, 'index'])->name('drivers.index');
    Route::get('/create', [Driver\DriverController::class, 'create'])->name('drivers.create');
    Route::post('/create', [Driver\DriverController::class, 'store'])->name('drivers.store');
    Route::get('/show/{driver}', [Driver\DriverController::class, 'show'])->name('drivers.show');
    Route::get('/edit/{driver}', [Driver\DriverController::class, 'edit'])->name('drivers.edit');
    Route::post('/update/{driver}', [Driver\DriverController::class, 'update'])->name('drivers.update');
});

Route::group(['prefix' => 'vehicle', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Vehicle\VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/create', [Vehicle\VehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/create', [Vehicle\VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/show/{vehicle}', [Vehicle\VehicleController::class, 'show'])->name('vehicles.show');
    Route::get('/edit/{vehicle}', [Vehicle\VehicleController::class, 'edit'])->name('vehicles.edit');
    Route::post('/update/{vehicle}', [Vehicle\VehicleController::class, 'update'])->name('vehicles.update');
});

Route::group(['prefix' => 'invoice', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Invoice\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/create', [Invoice\InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/create', [Invoice\InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/show/{invoice}', [Invoice\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/edit/{invoice}', [Invoice\InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::post('/update/{invoice}', [Invoice\InvoiceController::class, 'update'])->name('invoices.update');
    Route::post('/credit/debit', [Invoice\InvoiceController::class, 'creditDebit'])->name('invoices.creditDebit');
});

Route::group(['prefix' => 'payment', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Payment\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/create/{invoice}', [Payment\PaymentController::class, 'create'])->name('payments.create');
    Route::post('/create', [Payment\PaymentController::class, 'store'])->name('payments.store');
    Route::get('/show/{payment}', [Payment\PaymentController::class, 'show'])->name('payments.show');
    Route::get('/edit/{payment}', [Payment\PaymentController::class, 'edit'])->name('payments.edit');
    Route::post('/update/{payment}', [Payment\PaymentController::class, 'update'])->name('payments.update');
    Route::get('/generate/receipt/{payment}', [Payment\PaymentController::class, 'generateReceipt'])->name('payments.receipt');
});

Route::group(['prefix' => 'notification', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Notification\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/create', [Notification\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/create', [Notification\NotificationController::class, 'store'])->name('notifications.store');
    Route::get('/show/{notification}', [Notification\NotificationController::class, 'show'])->name('notifications.show');
    Route::get('/edit/{notification}', [Notification\NotificationController::class, 'edit'])->name('notifications.edit');
    Route::post('/update/{notification}', [Notification\NotificationController::class, 'update'])->name('notifications.update');
});
