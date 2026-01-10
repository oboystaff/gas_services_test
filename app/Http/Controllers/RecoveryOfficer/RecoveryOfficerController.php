<?php

namespace App\Http\Controllers\RecoveryOfficer;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecoveryOfficer\CreateRecoveryOfficerRequest;
use App\Http\Requests\RecoveryOfficer\UpdateRecoveryOfficerRequest;
use App\Models\RecoveryOfficer;
use Illuminate\Http\Request;

class RecoveryOfficerController extends Controller
{
    public function index()
    {
        $pageTitle = "Recovery Officer Page";

        $recoveryOfficers = RecoveryOfficer::orderBy('created_by')->get();

        return view('recovery-officers.index', compact('recoveryOfficers', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = "Recovery Officer Page";

        return view('recovery-officers.create', compact('pageTitle'));
    }

    public function store(CreateRecoveryOfficerRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        RecoveryOfficer::create($data);

        return redirect()->route('recovery-officers.index')->with('status', 'Recovery officer created successfully.');
    }

    public function show(RecoveryOfficer $recoveryOfficer)
    {
        $pageTitle = "Recovery Officer Page";

        return view('recovery-officers.show', compact('recoveryOfficer', 'pageTitle'));
    }

    public function edit(RecoveryOfficer $recoveryOfficer)
    {
        $pageTitle = "Recovery Officer Page";

        return view('recovery-officers.edit', compact('recoveryOfficer', 'pageTitle'));
    }

    public function update(UpdateRecoveryOfficerRequest $request, RecoveryOfficer $recoveryOfficer)
    {
        $recoveryOfficer->update($request->validated());

        return redirect()->route('recovery-officers.index')->with('status', 'Recovery officer updated successfully.');
    }
}
