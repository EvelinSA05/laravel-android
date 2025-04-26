<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{
    public function getDataFromView()
    {
        $data = DB::table('view_transaksi')->get();
        return response()->json($data);
    }

    public function getDataFromView2(Request $request)
    {
        // $transaksis = DB::table('view_ordersdetails')->get();
        // return response()->json($data);

         // Ambil nilai parameter service_name dari permintaan
    $service_name = $request->input('nama_pelanggan');

    // Query data berdasarkan nilai service_name dari view
    $transaksis = DB::table('view_ordersdetails')
                    ->select('*')
                    ->where('nama_pelanggan', $service_name)
                    ->get();

    // Kembalikan data dalam format JSON
    return response()->json($transaksis);
    }

    public function getDataFromView3(Request $request) {
        $data = DB::table('view_ordersdetails')->get();
            return response()->json($data);
    }

    public function getDataFromViewService(Request $request) {
        $service = $request->input('kategori');

        $data = DB::table('view_services')
        ->select('*')
        ->where('kategori', $service)
        ->get();
        return response()->json($data);
    }
    
}

