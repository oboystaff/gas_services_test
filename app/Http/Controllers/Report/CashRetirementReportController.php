<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\CashRetirement;
use Illuminate\Support\Facades\DB;


class CashRetirementReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (!auth()->user()->can('reports.view')) {
                abort(403, 'Unauthorized action.');
            }

            $pageTitle = "Cash Retirement Report Page";

            $branches = Branch::orderBy('name', 'ASC')
                ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                    $query->where('id', $request->user()->branch_id);
                })
                ->get();

            if (request()->ajax()) {

                if ($request->report_type == 1) {
                    $sales = DB::table('sales')
                        ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                            $query->where('branch_id', $request->user()->branch_id);
                        })
                        ->when(($request->filled('from_date') && $request->filled('to_date')), function ($query) use ($request) {
                            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
                        })
                        ->when($request->filled('branch_id'), function ($query) use ($request) {
                            $query->where('branch_id', $request->branch_id);
                        })
                        ->select(
                            'branch_id',
                            DB::raw('DATE(created_at) as sales_date'),
                            DB::raw('SUM(amount) as total_sales')
                        )
                        ->groupBy('branch_id', DB::raw('DATE(created_at)'));

                    $cashRetirements = DB::table('cash_retirements')
                        ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                            $query->where('branch_id', $request->user()->branch_id);
                        })
                        ->when(($request->filled('from_date') && $request->filled('to_date')), function ($query) use ($request) {
                            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
                        })
                        ->when($request->filled('branch_id'), function ($query) use ($request) {
                            $query->where('branch_id', $request->branch_id);
                        })
                        ->select(
                            'branch_id',
                            DB::raw('DATE(sales_date) as cash_retirements_date'),
                            DB::raw('SUM(amount_retired) as total_retired'),
                            DB::raw('MAX(date_retired) as date_retired')
                        )
                        ->groupBy('branch_id', DB::raw('DATE(sales_date)'));

                    $data = DB::query()
                        ->fromSub($sales, 'sales')
                        ->leftJoinSub($cashRetirements, 'cash_retirements', function ($join) {
                            $join->on('sales.branch_id', '=', 'cash_retirements.branch_id')
                                ->on('sales.sales_date', '=', 'cash_retirements.cash_retirements_date');
                        })
                        ->leftJoin('branches', 'sales.branch_id', '=', 'branches.id')
                        ->leftJoin('users', 'sales.branch_id', '=', 'users.branch_id')
                        ->leftJoin('roles', function ($join) {
                            $join->on('users.user_role', '=', 'roles.id')
                                ->where('roles.name', 'LIKE', '%Branch Manager%');
                        })
                        ->select(
                            'sales.branch_id',
                            'branches.name as branch_name',
                            'sales.sales_date',
                            'sales.total_sales',
                            'cash_retirements.total_retired',
                            'cash_retirements.date_retired',
                            DB::raw('MAX(CASE WHEN roles.name LIKE "%Branch Manager%" THEN users.name END) as branch_manager')
                        )
                        ->groupBy(
                            'sales.branch_id',
                            'branches.name',
                            'sales.sales_date',
                            'cash_retirements.total_retired',
                            'cash_retirements.date_retired'
                        )
                        ->orderBy('sales.branch_id')
                        ->orderBy('sales.sales_date')
                        ->get();

                    return datatables()->of($data)
                        ->addIndexColumn()
                        ->editColumn('status', function ($row) {
                            if ($row->total_retired >= $row->total_sales) {
                                return "Completed";
                            } else {
                                return "Pending";
                            }
                        })
                        ->editColumn('branch', function ($row) {
                            return $row->branch_name ?? 'N/A';
                        })
                        ->editColumn('total_retired', function ($row) {
                            return $row->total_retired ?? '0.00';
                        })
                        ->editColumn('date_retired', function ($row) {
                            return $row->date_retired ?? 'N/A';
                        })
                        ->make(true);
                } else {
                }
            }

            return view('reports.cash-retirement-report', compact('branches', 'pageTitle'));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
