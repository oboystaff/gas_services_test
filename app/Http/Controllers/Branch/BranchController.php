<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\CreateBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('branches.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Branch Page";

        $branches = Branch::orderBy('created_at', 'DESC')
            ->get();

        return view('branches.index', compact('branches', 'pageTitle'));
    }

    public function create()
    {
        if (!auth()->user()->can('branches.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Branch Page";

        return view('branches.create', compact('pageTitle'));
    }

    public function store(CreateBranchRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id ?? null;

        Branch::create($data);

        return redirect()->route('branches.index')->with('status', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        $pageTitle = "Branch Page";

        return view('branches.show', compact('branch', 'pageTitle'));
    }

    public function edit(Branch $branch)
    {
        if (!auth()->user()->can('branches.update')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Branch Page";

        return view('branches.edit', compact('branch', 'pageTitle'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

        return redirect()->route('branches.index')->with('status', 'Branch updated successfully.');
    }
}
