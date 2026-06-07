<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * DISPLAY LIST OF TICKETS
     */
    public function index()
    {
        $query = Ticket::with([
            'reporter',
            'technician',
            'asset'
        ]);

        // Staff hanya boleh melihat tiket yang mereka buat sendiri
        if (auth()->check() && auth()->user()->role === 'staff') {
            $query->where('reported_by', auth()->id());
        }

        $tickets = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Daftar ticket berhasil diambil.',
            'data' => $tickets,
        ], 200);
    }

    /**
     * STORE NEW TICKET
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => [
                'required',
                'exists:assets,id',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'priority' => [
                'required',
                Rule::in(['low', 'medium', 'high']),
            ],
        ]);

        $ticket = Ticket::create([
            'ticket_number' => 'TCK-' . strtoupper(uniqid()),

            'asset_id' => $validated['asset_id'],

            'reported_by' => auth()->id(),

            'assigned_to' => $validated['assigned_to'] ?? null,

            'title' => $validated['title'],

            'description' => $validated['description'],

            'priority' => $validated['priority'],

            'status' => 'open',

            'reported_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket berhasil dibuat.',
            'data' => $ticket->load([
                'reporter',
                'technician',
                'asset'
            ]),
        ], 201);
    }

    /**
     * SHOW DETAIL TICKET
     */
    public function show($id)
    {
        $ticket = Ticket::with([
            'reporter',
            'technician',
            'asset',
            'repairLogs'
        ])->find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Data ticket tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail ticket berhasil diambil.',
            'data' => $ticket,
        ], 200);
    }

    /**
     * UPDATE TICKET
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Data ticket tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'asset_id' => [
                'required',
                'exists:assets,id',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'priority' => [
                'required',
                Rule::in(['low', 'medium', 'high']),
            ],

            'status' => [
                'required',
                Rule::in([
                    'open',
                    'in_progress',
                    'resolved',
                    'closed'
                ]),
            ],
        ]);

        $ticket->update([
            'asset_id' => $validated['asset_id'],
            'assigned_to' => $validated['assigned_to'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket berhasil diperbarui.',
            'data' => $ticket->load([
                'reporter',
                'technician',
                'asset'
            ]),
        ], 200);
    }

    /**
     * DELETE TICKET
     */
    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Data ticket tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $ticket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ticket berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}