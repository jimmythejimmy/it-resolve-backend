<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;

class ExportController extends Controller
{
    public function exportAssets(Request $request)
    {
        $user = auth()->user();

        $query = Asset::with('assignedUser');
        if ($user && $user->role === 'staff') {
            $query->where('assigned_to_user_id', $user->id);
        }

        $assets = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="it-resolve-assets-' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($assets) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel to open it correctly in UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'ID',
                'Kode Aset',
                'Nama Aset',
                'Kategori',
                'Brand',
                'Model',
                'Serial Number',
                'Tanggal Pembelian',
                'Harga Beli',
                'Kondisi',
                'Status',
                'Lokasi',
                'Ditugaskan Ke'
            ]);

            foreach ($assets as $asset) {
                fputcsv($file, [
                    $asset->id,
                    $asset->asset_code,
                    $asset->asset_name,
                    $asset->category,
                    $asset->brand ?? '—',
                    $asset->model ?? '—',
                    $asset->serial_number,
                    $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '—',
                    $asset->purchase_price ? number_format((float)$asset->purchase_price, 2, '.', '') : '—',
                    $asset->condition,
                    $asset->status,
                    $asset->location ?? '—',
                    $asset->assignedUser ? $asset->assignedUser->name : 'Belum Ditugaskan'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
