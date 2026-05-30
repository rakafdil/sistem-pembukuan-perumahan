<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Penghuni\UpsertPenghuniRequest;
use App\Models\Penghuni;
use App\Http\Resources\PenghuniResource;
use Illuminate\Support\Facades\Storage;

class PenghuniController extends Controller
{
    public function index()
    {
        $penghuni = Penghuni::latest()->get();
        return PenghuniResource::collection($penghuni)
            ->response()
            ->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }

    public function store(UpsertPenghuniRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('foto_ktp')) {
            $path = $request->file('foto_ktp')->store('foto_ktp', 'public');
            $validatedData['foto_ktp'] = $path;
        }

        $penghuni = Penghuni::create($validatedData);

        return response()->json([
            'message' => 'Data penghuni berhasil ditambahkan',
            'data' => new PenghuniResource($penghuni)
        ], 201);
    }

    public function show(Penghuni $penghuni)
    {
        return (new PenghuniResource($penghuni))
            ->response()
            ->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }

    public function update(UpsertPenghuniRequest $request, Penghuni $penghuni)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('foto_ktp')) {
            if ($penghuni->foto_ktp) {
                Storage::disk('public')->delete($penghuni->foto_ktp);
            }

            $validatedData['foto_ktp'] = $request->file('foto_ktp')->store('foto_ktp', 'public');
        }

        $penghuni->update($validatedData);

        return response()->json([
            'message' => 'Data penghuni berhasil diperbarui',
            'data' => new PenghuniResource($penghuni),
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function destroy(Penghuni $penghuni)
    {
        $penghuni->delete();
        return response()->json(['message' => 'Data penghuni berhasil dihapus']);
    }
}