<?php

namespace App\Http\Controllers\Rate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rate\CreateRateRequest;
use App\Http\Requests\Rate\UpdateRateRequest;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('rates.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Rate Page";

        $rates = Rate::orderBy('created_at', 'DESC')->get();

        return view('rates.index', compact('rates', 'pageTitle'));
    }

    public function create()
    {
        if (!auth()->user()->can('rates.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Rate Page";

        return view('rates.create', compact('pageTitle'));
    }

    public function store(CreateRateRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        Rate::create($data);

        return redirect()->route('rates.index')->with('status', 'Rate created successfully.');
    }

    public function show(Rate $rate)
    {
        $pageTitle = "Rate Page";

        return view('rates.show', compact('rate', 'pageTitle'));
    }

    public function edit(Rate $rate)
    {
        if (!auth()->user()->can('rates.update')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Rate Page";

        return view('rates.edit', compact('rate', 'pageTitle'));
    }

    public function update(UpdateRateRequest $request, Rate $rate)
    {
        $rate->update($request->validated());

        return redirect()->route('rates.index')->with('status', 'Rate updated successfully.');
    }
}
