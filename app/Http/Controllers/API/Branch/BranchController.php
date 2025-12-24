<?php

namespace App\Http\Controllers\API\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $data = Branch::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('id', $request->user()->branch_id);
            })
            ->get();

        return response()->json([
            'message' => 'Get all branches',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $branch = Branch::query()
            ->where('id', $id)
            ->first();

        if (empty($branch)) {
            return response()->json([
                'message' => 'Branch not found'
            ], 422);
        }

        return response()->json([
            'message' => 'Get particular branch',
            'data' => $branch
        ]);
    }
}
