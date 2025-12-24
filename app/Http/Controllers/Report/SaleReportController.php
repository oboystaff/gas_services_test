<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Community;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class SaleReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (!auth()->user()->can('reports.view')) {
                abort(403, 'Unauthorized action.');
            }

            $pageTitle = "Gas Sale Report Page";

            $branches = Branch::orderBy('name', 'ASC')
                ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                    $query->where('id', $request->user()->branch_id);
                })
                ->get();

            $communities = Community::orderBy('name', 'ASC')
                ->get();

            $users = User::orderBy('name', 'ASC')
                ->where('status', '=', 'Active')
                ->get();


            if (request()->ajax()) {

                if ($request->report_type == 1) {
                    $data = Sale::orderBy('created_at', 'DESC')
                        ->when(($request->filled('from_date') && $request->filled('to_date')), function ($query) use ($request) {
                            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
                        })
                        ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                            $query->where('branch_id', $request->user()->branch_id);
                        })
                        ->when($request->filled('branch_id'), function ($query) use ($request) {
                            $query->where('branch_id', $request->branch_id);
                        })
                        ->when($request->filled('community_id'), function ($query) use ($request) {
                            $query->where('community_id', $request->community_id);
                        })
                        ->when($request->filled('status'), function ($query) use ($request) {
                            $query->where('status', $request->status);
                        })
                        ->when($request->filled('user_id'), function ($query) use ($request) {
                            $query->where('created_by', $request->user_id);
                        })
                        ->get();

                    return datatables()->of($data)
                        ->addIndexColumn()
                        ->editColumn('created_by', function (Sale $sale) {
                            return $sale->createdBy->name ?? '';
                        })
                        ->editColumn('name', function (Sale $sale) {
                            return $sale->name ?? 'N/A';
                        })
                        ->editColumn('branch', function (Sale $sale) {
                            return $sale->branch->name ?? 'N/A';
                        })
                        ->editColumn('cid', function (Sale $sale) {
                            return $sale->cid ?? 'N/A';
                        })
                        ->editColumn('service_charge', function (Sale $sale) {
                            return $sale->service_charge ?? '0';
                        })
                        ->editColumn('created_at', function (Sale $sale) {
                            return $sale->created_at;
                        })
                        ->make(true);
                } else {
                    $data = DB::table('sales as s')
                        ->when(($request->filled('from_date') && $request->filled('to_date')), function ($query) use ($request) {
                            $query->whereBetween('s.created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
                        })
                        ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                            return $query->where('s.branch_id', $request->user()->branch_id);
                        })
                        ->when($request->filled('user_id'), function ($query) use ($request) {
                            return $query->where('s.created_by', $request->input('user_id'));
                        })
                        ->join('users as u', 's.created_by', '=', 'u.id')
                        ->select(
                            'u.id',
                            'u.name',
                            DB::raw('SUM(s.amount) as total_sold')
                        )
                        ->groupBy('u.id', 'u.name')
                        ->get();


                    return datatables()->of($data)
                        ->addIndexColumn()
                        ->make(true);
                }
            }

            return view('reports.sale-report', compact('branches', 'communities', 'users', 'pageTitle'));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
