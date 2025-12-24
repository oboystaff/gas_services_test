<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehicle\CreateVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('vehicles.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Vehicle Page";

        $vehicles = Vehicle::orderBy('created_at', 'DESC')->get();

        return view('vehicles.index', compact('vehicles', 'pageTitle'));
    }

    public function create()
    {
        if (!auth()->user()->can('vehicles.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Vehicle Page";

        $drivers = Driver::orderBy('name', 'ASC')->get();

        return view('vehicles.create', compact('drivers', 'pageTitle'));
    }

    public function store(CreateVehicleRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        Vehicle::create($data);

        return redirect()->route('vehicles.index')->with('status', 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $pageTitle = "Vehicle Page";

        return view('vehicles.show', compact('vehicle', 'pageTitle'));
    }

    public function edit(Vehicle $vehicle)
    {
        if (!auth()->user()->can('vehicles.update')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Vehicle Page";

        $drivers = Driver::orderBy('name', 'ASC')->get();

        return view('vehicles.edit', compact('vehicle', 'drivers', 'pageTitle'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return redirect()->route('vehicles.index')->with('status', 'Vehicle updated successfully.');
    }
}
