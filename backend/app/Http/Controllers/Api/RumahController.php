<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rumah\StoreRumahRequest;
use App\Http\Requests\Rumah\UpdateRumahRequest;
use App\Models\Rumah;
use App\Http\Resources\RumahResource;
use App\Http\Resources\RumahDetailResource;
use Illuminate\Http\Request;

class RumahController extends Controller
{
    public function index()
    {
        $rumah = Rumah::with(['historiHuni.penghuni'])->orderBy('blok_nomor')->get();
        return RumahResource::collection($rumah);
    }

    public function store(StoreRumahRequest $request)
    {
        $rumah = Rumah::create($request->validated());

        return response()->json([
            'message' => 'Data rumah berhasil ditambahkan.',
            'data' => new RumahResource($rumah)
        ], 201);
    }

    public function show(Rumah $rumah)
    {
        $rumah->load(['historiHuni.penghuni']);
        return new RumahDetailResource($rumah);
    }

    public function update(UpdateRumahRequest $request, Rumah $rumah)
    {
        $validatedData = $request->validated();

        $rumah->update($validatedData);

        return response()->json([
            'message' => 'Data rumah berhasil diperbarui.',
            'data' => new RumahResource($rumah)
        ]);
    }
    public function destroy(Rumah $rumah)
    {
        $rumah->delete();
        return response()->json(['message' => 'Data rumah berhasil dihapus.']);
    }
}