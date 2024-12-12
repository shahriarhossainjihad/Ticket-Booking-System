<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
class VehicleController extends Controller
{
    public function index()
    {
        return response()->json(Vehicle::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $Vehicle = Vehicle::create($request->all());

        return response()->json($Vehicle, 201);
    }

    public function show($id)
    {
        $Vehicle = Vehicle::findOrFail($id);
        return response()->json($Vehicle);
    }

    public function update(Request $request, $id)
    {
        $Vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'name' => 'string',
            'vehicle_type_id' => 'exists:vehicle_types,id',
            'capacity' => 'integer|min:1',
        ]);

        $Vehicle->update($request->all());

        return response()->json($Vehicle);
    }

    public function destroy($id)
    {
        $Vehicle = Vehicle::findOrFail($id);
        $Vehicle->delete();

        return response()->json(null, 204);
    }
}
