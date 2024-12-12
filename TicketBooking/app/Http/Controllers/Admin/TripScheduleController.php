<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TripSchedule;

class TripScheduleController extends Controller
{
    // List all trip schedules
    public function index()
    {
        return response()->json(TripSchedule::all());
    }

    // Store a new trip schedule
    public function store(Request $request)
    {
        $request->validate([
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'pickup_point' => 'required|string',
            'drop_point' => 'required|string',
        ]);

        $tripSchedule = TripSchedule::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'Trip schedule created successfully.',
            'data' => $tripSchedule
        ], 201);
    }

    // Show a specific trip schedule
    public function show($id)
    {
        $tripSchedule = TripSchedule::findOrFail($id);

        return response()->json($tripSchedule);
    }

    // Update a trip schedule
    public function update(Request $request, $id)
    {
        $tripSchedule = TripSchedule::findOrFail($id);

        $request->validate([
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'pickup_point' => 'required|string',
            'drop_point' => 'required|string',
        ]);

        $tripSchedule->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Trip schedule updated successfully.',
            'data' => $tripSchedule
        ]);
    }

    // Delete a trip schedule
    public function destroy($id)
    {
        $tripSchedule = TripSchedule::findOrFail($id);
        $tripSchedule->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Trip schedule deleted successfully.'
        ]);
    }
}
