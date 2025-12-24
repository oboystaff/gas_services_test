<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Community;
use App\Models\GasRequest;

class GasRequestReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (!auth()->user()->can('reports.view')) {
                abort(403, 'Unauthorized action.');
            }

            $pageTitle = "Gas Request Report Page";

            $branches = Branch::orderBy('name', 'ASC')
                ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                    $query->where('id', $request->user()->branch_id);
                })
                ->get();

            $communities = Community::orderBy('name', 'ASC')
                ->get();

            if (request()->ajax()) {

                if ($request->report_type == 1) {
                    $data = GasRequest::orderBy('created_at', 'DESC')
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
                        ->get();

                    return datatables()->of($data)
                        ->addIndexColumn()
                        ->editColumn('customer_id', function (GasRequest $gasRequest) {
                            return $gasRequest->customer_id ?? 'N/A';
                        })
                        ->editColumn('agent_assigned', function (GasRequest $gasRequest) {
                            return $gasRequest->driverAssigned->name ?? 'N/A';
                        })
                        ->editColumn('created_by', function (GasRequest $gasRequest) {
                            return $gasRequest->createdBy->name ?? '';
                        })
                        ->editColumn('request_contact', function (GasRequest $gasRequest) {
                            return $gasRequest->request_contact ?? 'N/A';
                        })
                        ->editColumn('delivery_branch', function (GasRequest $gasRequest) {
                            return $gasRequest->deliveryBranch->name ?? 'N/A';
                        })
                        ->editColumn('amount', function (GasRequest $gasRequest) {
                            return number_format($gasRequest->amount, 2) ?? 'N/A';
                        })
                        ->editColumn('branch', function (GasRequest $gasRequest) {
                            $communityIds = $gasRequest->customer->community_id;

                            if (is_string($communityIds)) {
                                $decoded = json_decode($communityIds, true);
                                $communityIds = is_array($decoded) ? $decoded : [$communityIds];
                            } elseif (is_int($communityIds)) {
                                $communityIds = [$communityIds];
                            }

                            $communityNames = Community::whereIn('id', $communityIds)->pluck('name')->implode(', ');

                            return $communityNames ?? 'N/A';
                        })
                        ->editColumn('community', function (GasRequest $gasRequest) {
                            return $gasRequest->community->name ?? 'N/A';
                        })
                        ->editColumn('created_at', function (GasRequest $gasRequest) {
                            return $gasRequest->created_at;
                        })
                        ->make(true);
                } else {
                }
            }

            return view('reports.gas-request-report', compact('branches', 'communities', 'pageTitle'));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
