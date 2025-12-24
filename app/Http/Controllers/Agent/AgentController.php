<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\CreateAgentRequest;
use App\Http\Requests\Agent\UpdateAgentRequest;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('agents.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Delivery Agent Page";

        $agents = Agent::orderBy('created_at', 'DESC')->get();

        return view('agents.index', compact('agents', 'pageTitle'));
    }

    public function create()
    {
        if (!auth()->user()->can('agents.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Delivery Agent Page";

        return view('agents.create', compact('pageTitle'));
    }

    public function store(CreateAgentRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        Agent::create($data);

        return redirect()->route('agents.index')->with('status', 'Agent created successfully.');
    }

    public function show(Agent $agent)
    {
        $pageTitle = "Delivery Agent Page";

        return view('agents.show', compact('agent', 'pageTitle'));
    }

    public function edit(Agent $agent)
    {
        if (!auth()->user()->can('agents.update')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Delivery Agent Page";

        return view('agents.edit', compact('agent', 'pageTitle'));
    }

    public function update(UpdateAgentRequest $request, Agent $agent)
    {
        $agent->update($request->validated());

        return redirect()->route('agents.index')->with('status', 'Agent updated successfully.');
    }
}
