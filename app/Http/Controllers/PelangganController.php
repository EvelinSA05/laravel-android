<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = pelanggan::all();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pelanggan = Pelanggan::create([
            'nama_pelanggan' => $request->input('nama_pelanggan'),
            'alamat' => $request->input('alamat'),
            'telp' => $request->input('telp')
        ]);

        return response()->json(['pelanggan' => $pelanggan], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelanggan $pelanggan, $id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json(['message' => 'Data Pelanggan Tidak Ditemukan'], 404);
        }

        return response()->json($pelanggan, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json(['message' => 'Data Pelanggan not found'], 404);
        }

        $pelanggan->update([
            'nama_pelanggan' => $request->input('nama_pelanggan'),
            'alamat' => $request->input('alamat'),
            'telp' => $request->input('telp'),
        ]);

        return response()->json(['message' => 'Data Pelanggan updated successfully', 'pelanggan' => $pelanggan], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json(['message' => 'Data Pelanggan Tidak Ditemukan'], 404);
        }

        $pelanggan->delete();

        return response()->json(['message' => 'Data Pelanggan berhasil dihapus'], 200);
    }
}
