<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trip;
class TripController extends Controller
{
    // List all trips
    public function index()
    {
        return response()->json(Trip::with('vehicles', 'tripSchedule')->get());
    }

    // Store a new trip
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'trip_schedule_id' => 'required|exists:trip_schedules,id',
            'ticket_price' => 'required|numeric|min:1',
        ]);

        $trip = Trip::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'Trip created successfully.',
            'data' => $trip
        ], 201);
    }

    // Show a specific trip
    public function show($id)
    {
        $trip = Trip::with('vehicles', 'tripSchedule')->findOrFail($id);

        return response()->json($trip);
    }

    // Update a trip
    public function update(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'trip_schedule_id' => 'required|exists:trip_schedules,id',
            'ticket_price' => 'required|numeric|min:1',
        ]);

        $trip->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Trip updated successfully.',
            'data' => $trip
        ]);
    }

    // Delete a trip
    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);
        $trip->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Trip deleted successfully.'
        ]);
    }
}
