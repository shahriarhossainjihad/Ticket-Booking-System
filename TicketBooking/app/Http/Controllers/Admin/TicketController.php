<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
class TicketController extends Controller
{
    /**
     * Display a list of all tickets.
     */
    public function index()
    {
        $tickets = Ticket::with('trip', 'user')->get();
        return response()->json($tickets);
    }

    /**
     * Display a list of available tickets.
     */
    public function availableTickets()
    {
        $tickets = Ticket::where('status', 'available')->get();
        return response()->json($tickets);
    }

    /**
     * Purchase a ticket for a specific trip.
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
        ]);

        $ticket = Ticket::where('trip_id', $request->trip_id)
                        ->where('status', 'available')
                        ->first();

        if (!$ticket) {
            return response()->json([
                'status' => 404,
                'message' => 'No available tickets for this trip.'
            ]);
        }

        $ticket->update([
            'status' => 'booked',
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Ticket booked successfully.',
            'ticket' => $ticket
        ]);
    }

    /**
     * Store a new ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'status' => 'required|in:available,booked',
        ]);

        $ticket = Ticket::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'Ticket created successfully.',
            'data' => $ticket
        ], 201);
    }

    /**
     * Display a specific ticket.
     */
    public function show($id)
    {
        $ticket = Ticket::with('trip', 'user')->findOrFail($id);

        return response()->json($ticket);
    }

    /**
     * Update a specific ticket.
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:available,booked',
            'trip_id' => 'required|exists:trips,id',
        ]);

        $ticket->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Ticket updated successfully.',
            'data' => $ticket
        ]);
    }

    /**
     * Delete a specific ticket.
     */
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Ticket deleted successfully.'
        ]);
    }
}
