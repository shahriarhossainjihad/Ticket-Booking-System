<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleType;
class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(VehicleType::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string',
        ]);

        $VehicleType = VehicleType::create($request->all());

        return response()->json($VehicleType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $VehicleType = VehicleType::findOrFail($id);
        return response()->json($VehicleType);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $VehicleType = VehicleType::findOrFail($id);

        $request->validate([
            'name' => 'string',
            'vehicle_type_id' => 'exists:vehicle_types,id',
            'capacity' => 'integer|min:1',
        ]);

        $VehicleType->update($request->all());

        return response()->json($VehicleType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $VehicleType = VehicleType::findOrFail($id);
        $VehicleType->delete();

        return response()->json(null, 204);
    }
}
