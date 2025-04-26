<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = service::all();
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
        $service = Service::create([
            'idkategori' => $request->input('idkategori'),
            'nama_jasa' => $request->input('nama_jasa'),
            'harga' => $request->input('harga'),
        ]);

        return response()->json(['service' => $service], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Jasa not found'], 404);
        }

        return response()->json($service, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Jasa not found'], 404);
        }

        $service->update([
            'nama_jasa' => $request->input('nama_jasa'),
            'harga' => $request->input('harga'),
        ]);

        return response()->json(['message' => 'Jasa updated successfully', 'service' => $service], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Jasa not found'], 404);
        }

        $service->delete();

        return response()->json(['message' => 'Jasa deleted successfully'], 200);
    }
}
