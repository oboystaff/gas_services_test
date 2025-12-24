<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\CreateDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('drivers.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Driver Page";

        $drivers = Driver::orderBy('created_at', 'DESC')->get();

        return view('drivers.index', compact('drivers', 'pageTitle'));
    }

    public function create()
    {
        if (!auth()->user()->can('drivers.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Driver Page";

        return view('drivers.create', compact('pageTitle'));
    }

    public function store(CreateDriverRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        Driver::create($data);

        return redirect()->route('drivers.index')->with('status', 'Driver created successfully.');
    }

    public function show(Driver $driver)
    {
        $pageTitle = "Driver Page";

        return view('drivers.show', compact('driver', 'pageTitle'));
    }

    public function edit(Driver $driver)
    {
        if (!auth()->user()->can('drivers.update')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Driver Page";

        return view('drivers.edit', compact('driver', 'pageTitle'));
    }

    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        $driver->update($request->validated());

        return redirect()->route('drivers.index')->with('status', 'Driver updated successfully.');
    }
}
