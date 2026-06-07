<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Ticket;
use App\Models\RepairLog;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $user = auth()->user();

        // 1. Assets Query
        $assetQuery = Asset::query();
        if ($user->role === 'staff') {
            $assetQuery->where('assigned_to_user_id', $user->id);
        }

        $totalAssets = (clone $assetQuery)->count();
        $activeAssets = (clone $assetQuery)->where('status', 'active')->count();
        $inRepairAssets = (clone $assetQuery)->where('status', 'in_repair')->count();
        $inactiveAssets = (clone $assetQuery)->where('status', 'inactive')->count();

        // Condition count
        $goodCondition = (clone $assetQuery)->where('condition', 'good')->count();
        $damagedCondition = (clone $assetQuery)->where('condition', 'damaged')->count();
        $maintenanceCondition = (clone $assetQuery)->where('condition', 'maintenance')->count();

        // 2. Tickets Query
        $ticketQuery = Ticket::query();
        if ($user->role === 'staff') {
            $ticketQuery->where('reported_by', $user->id);
        }

        $totalTickets = (clone $ticketQuery)->count();
        $openTickets = (clone $ticketQuery)->where('status', 'open')->count();
        $inProgressTickets = (clone $ticketQuery)->where('status', 'in_progress')->count();
        $resolvedTickets = (clone $ticketQuery)->where('status', 'resolved')->count();
        $closedTickets = (clone $ticketQuery)->where('status', 'closed')->count();

        // 3. Repairs count (for repairs pending)
        // If staff, only count repair logs of tickets reported by them.
        $repairQuery = RepairLog::query();
        if ($user->role === 'staff') {
            $repairQuery->whereHas('ticket', function ($q) use ($user) {
                $q->where('reported_by', $user->id);
            });
        }
        $totalRepairs = $repairQuery->count();

        return response()->json([
            'success' => true,
            'message' => 'Statistik dashboard berhasil diambil.',
            'data' => [
                'assets' => [
                    'total' => $totalAssets,
                    'active' => $activeAssets,
                    'in_repair' => $inRepairAssets,
                    'inactive' => $inactiveAssets,
                    'good' => $goodCondition,
                    'damaged' => $damagedCondition,
                    'maintenance' => $maintenanceCondition,
                ],
                'tickets' => [
                    'total' => $totalTickets,
                    'open' => $openTickets,
                    'in_progress' => $inProgressTickets,
                    'resolved' => $resolvedTickets,
                    'closed' => $closedTickets,
                ],
                'repairs' => [
                    'total' => $totalRepairs,
                    // repairs pending are open tickets that are in service/in repair
                    'pending' => $inProgressTickets, 
                ],
            ]
        ], 200);
    }
}
