<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Asset;

class AssetController extends Controller
{
    /**
     * DISPLAY LIST OF ASSETS
     */
    public function index()
    {
        $query = Asset::with('assignedUser');

        if (auth()->check() && auth()->user()->role === 'staff') {
            $query->where('assigned_to_user_id', auth()->id());
        }

        $assets = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Daftar aset berhasil diambil.',
            'data' => $assets,
        ], 200);
    }

    /**
     * STORE NEW ASSET
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_code' => [
                'required',
                'string',
                'max:100',
                'unique:assets,asset_code',
            ],

            'asset_name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'brand' => [
                'nullable',
                'string',
                'max:100',
            ],

            'model' => [
                'nullable',
                'string',
                'max:100',
            ],

            'serial_number' => [
                'required',
                'string',
                'max:150',
                'unique:assets,serial_number',
            ],

            'specification' => [
                'nullable',
                'array',
            ],

            'purchase_date' => [
                'nullable',
                'date',
            ],

            'purchase_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'condition' => [
                'required',
                'string',
                'max:50',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'assigned_to_user_id' => [
                'nullable',
                'exists:users,id',
            ],
        ]);

        $asset = Asset::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Aset berhasil ditambahkan.',
            'data' => $asset,
        ], 201);
    }

    /**
     * SHOW DETAIL ASSET
     */
    public function show($id)
    {
        $asset = Asset::with([
            'assignedUser',
            'tickets',
            'repairLogs'
        ])->find($id);

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Data aset tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail aset berhasil diambil.',
            'data' => $asset,
        ], 200);
    }

    /**
     * UPDATE ASSET
     */
    public function update(Request $request, $id)
    {
        $asset = Asset::find($id);

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Data aset tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'asset_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('assets', 'asset_code')->ignore($asset->id),
            ],

            'asset_name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'brand' => [
                'nullable',
                'string',
                'max:100',
            ],

            'model' => [
                'nullable',
                'string',
                'max:100',
            ],

            'serial_number' => [
                'required',
                'string',
                'max:150',
                Rule::unique('assets', 'serial_number')->ignore($asset->id),
            ],

            'specification' => [
                'nullable',
                'array',
            ],

            'purchase_date' => [
                'nullable',
                'date',
            ],

            'purchase_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'condition' => [
                'required',
                'string',
                'max:50',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'assigned_to_user_id' => [
                'nullable',
                'exists:users,id',
            ],
        ]);

        $asset->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data aset berhasil diperbarui.',
            'data' => $asset,
        ], 200);
    }

    /**
     * DELETE ASSET
     */
    public function destroy($id)
    {
        $asset = Asset::find($id);

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Data aset tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $asset->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data aset berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}