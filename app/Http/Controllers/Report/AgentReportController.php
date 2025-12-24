<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Driver;
use Illuminate\Http\Request;

class AgentReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (!auth()->user()->can('reports.view')) {
                abort(403, 'Unauthorized action.');
            }

            $pageTitle = "Driver Report Page";


            if (request()->ajax()) {

                if ($request->report_type == 1) {
                    $data = Driver::orderBy('created_at', 'DESC')
                        ->when(($request->filled('from_date') && $request->filled('to_date')), function ($query) use ($request) {
                            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
                        })
                        ->get();

                    return datatables()->of($data)
                        ->addIndexColumn()
                        ->editColumn('delivery_no', function (Driver $driver) {
                            return $driver->delivery->count() ?? 0;
                        })
                        ->editColumn('created_by', function (Driver $driver) {
                            return $driver->createdBy->name ?? '';
                        })
                        ->editColumn('created_at', function (Driver $driver) {
                            return $driver->created_at;
                        })
                        ->make(true);
                } else {
                }
            }

            return view('reports.agent-report', compact('pageTitle'));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
