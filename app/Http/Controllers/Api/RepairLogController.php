<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RepairLog;

class RepairLogController extends Controller
{
    /**
     * DISPLAY LIST OF REPAIR LOGS
     */
    public function index()
    {
        $repairLogs = RepairLog::with([
            'asset',
            'ticket',
            'actor'
        ])
        ->latest()
        ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Daftar repair log berhasil diambil.',
            'data' => $repairLogs,
        ], 200);
    }

    /**
     * STORE NEW REPAIR LOG
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => [
                'required',
                'exists:tickets,id',
            ],

            'asset_id' => [
                'required',
                'exists:assets,id',
            ],

            'from_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'to_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'action_type' => [
                'required',
                'string',
                'max:100',
            ],

            'notes' => [
                'required',
                'string',
            ],

            'metadata' => [
                'nullable',
                'array',
            ],
        ]);

        $repairLog = RepairLog::create([
            'ticket_id' => $validated['ticket_id'] ?? null,

            'asset_id' => $validated['asset_id'],

            'actor_user_id' => auth()->id(),

            'from_status' => $validated['from_status'] ?? null,

            'to_status' => $validated['to_status'] ?? null,

            'action_type' => $validated['action_type'],

            'notes' => $validated['notes'],

            'metadata' => $validated['metadata'] ?? null,

            'logged_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Repair log berhasil ditambahkan.',
            'data' => $repairLog->load([
                'asset',
                'ticket',
                'actor'
            ]),
        ], 201);
    }

    /**
     * SHOW DETAIL REPAIR LOG
     */
    public function show($id)
    {
        $repairLog = RepairLog::with([
            'asset',
            'ticket',
            'actor'
        ])->find($id);

        if (!$repairLog) {
            return response()->json([
                'success' => false,
                'message' => 'Data repair log tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail repair log berhasil diambil.',
            'data' => $repairLog,
        ], 200);
    }

    /**
     * UPDATE REPAIR LOG
     */
    public function update(Request $request, $id)
    {
        $repairLog = RepairLog::find($id);

        if (!$repairLog) {
            return response()->json([
                'success' => false,
                'message' => 'Data repair log tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'ticket_id' => [
                'required',
                'exists:tickets,id',
            ],

            'asset_id' => [
                'required',
                'exists:assets,id',
            ],

            'from_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'to_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'action_type' => [
                'required',
                'string',
                'max:100',
            ],

            'notes' => [
                'required',
                'string',
            ],

            'metadata' => [
                'nullable',
                'array',
            ],
        ]);

        $repairLog->update([
            'ticket_id' => $validated['ticket_id'] ?? null,

            'asset_id' => $validated['asset_id'],

            'from_status' => $validated['from_status'] ?? null,

            'to_status' => $validated['to_status'] ?? null,

            'action_type' => $validated['action_type'],

            'notes' => $validated['notes'],

            'metadata' => $validated['metadata'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Repair log berhasil diperbarui.',
            'data' => $repairLog->load([
                'asset',
                'ticket',
                'actor'
            ]),
        ], 200);
    }

    /**
     * DELETE REPAIR LOG
     */
    public function destroy($id)
    {
        $repairLog = RepairLog::find($id);

        if (!$repairLog) {
            return response()->json([
                'success' => false,
                'message' => 'Data repair log tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $repairLog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Repair log berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}