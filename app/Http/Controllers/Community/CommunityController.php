<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\CreateCommunityRequest;
use App\Http\Requests\Community\UpdateCommunityRequest;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('communities.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Community Page";

        $communities = Community::orderBy('created_at', 'DESC')
            ->get();

        return view('communities.index', compact('communities', 'pageTitle'));
    }

    public function create()
    {
        if (!auth()->user()->can('communities.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Community Page";

        return view('communities.create', compact('pageTitle'));
    }

    public function store(CreateCommunityRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        Community::create($data);

        return redirect()->route('communities.index')->with('status', 'Community created successfully.');
    }

    public function show(Community $community)
    {
        $pageTitle = "Community Page";

        return view('communities.show', compact('community', 'pageTitle'));
    }

    public function edit(Community $community)
    {
        if (!auth()->user()->can('communities.update')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Community Page";

        return view('communities.edit', compact('community', 'pageTitle'));
    }

    public function update(UpdateCommunityRequest $request, Community $community)
    {
        $community->update($request->validated());

        return redirect()->route('communities.index')->with('status', 'Community updated successfully.');
    }
}
