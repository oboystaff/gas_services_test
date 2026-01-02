<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Community;
use App\Models\Customer;


class CustomerReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (!auth()->user()->can('reports.view')) {
                abort(403, 'Unauthorized action.');
            }

            $pageTitle = "Customer Report Page";

            $branches = Branch::orderBy('name', 'ASC')
                ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                    $query->where('id', $request->user()->branch_id);
                })
                ->get();


            $communities = Community::orderBy('name', 'ASC')
                ->get();

            if (request()->ajax()) {

                if ($request->report_type == 1) {
                    $data = Customer::orderBy('created_at', 'DESC')
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
                        ->get();

                    return datatables()->of($data)
                        ->addIndexColumn()
                        ->editColumn('created_by', function (Customer $customer) {
                            return $customer->createdBy->name ?? '';
                        })
                        ->editColumn('branch', function (Customer $customer) {
                            return $customer->branch->name ?? 'N/A';
                        })
                        ->editColumn('contact', function (Customer $customer) {
                            return $customer->contact ?? 'N/A';
                        })
                        ->editColumn('secondary_contact', function (Customer $customer) {
                            return $customer->secondary_contact ?? 'N/A';
                        })
                        ->editColumn('threshold_amount', function (Customer $customer) {
                            return number_format($customer->threshold_amount, 2) ?? '0.0';
                        })
                        ->editColumn('due_date', function (Customer $customer) {
                            return $customer->due_date ? $customer->due_date . ' day(s)' : 'N/A';
                        })
                        ->editColumn('customer_branch', function (Customer $customer) {
                            $communityIds = $customer->community_id;

                            if (is_string($communityIds)) {
                                $decoded = json_decode($communityIds, true);
                                $communityIds = is_array($decoded) ? $decoded : [$communityIds];
                            } elseif (is_int($communityIds)) {
                                $communityIds = [$communityIds];
                            }

                            $communityNames = Community::whereIn('id', $communityIds)
                                ->pluck('name')
                                ->toArray();

                            return implode(', ', $communityNames) ?: 'N/A';
                        })
                        ->editColumn('created_at', function (Customer $customer) {
                            return $customer->created_at;
                        })
                        ->make(true);
                } else {
                }
            }

            return view('reports.customer-report', compact('branches', 'communities', 'pageTitle'));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
