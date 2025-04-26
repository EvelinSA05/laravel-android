<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Service;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function index()
    {
        $data = transaksi::all();
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

    // public function store(Request $request)
    // {     
    //     // Inisialisasi total transaksi

    //     $transaksi = Transaksi::createNewInvoice([
    //         'nama_pelanggan' => $request->input('nama_pelanggan'),
    //         'total' => $request->input('total'),
    //         'kembali' => $request->input('kembali'),
    //         'jumlah_bayar' => $request->input('jumlah_bayar'),
    //         'status' => $request->input('status'),
    //         'idkasir' => $request->input('idkasir'),
    //     ]);

    //     //Simpan detail transaksi
    //     foreach ($request->details as $detail) {
    //         Detail::create([
    //             'idtransaksi' => $transaksi->idtransaksi,
    //             'idservice' => $detail['idservice'],
    //             'harga' => $detail['harga'],
    //         ]);
    //     }

    //     //return response()->json(['message' => 'Transaksi berhasil disimpan'], 201);
    //     //$invoice = Transaksi::createNewInvoice($transaksi);
    //     return response()->json(['transaksi' => $transaksi], 201);
    // }

    public function transaksiBooking($bookingValue)
    {
        $transaksi = Transaksi::transaksiByBooking($bookingValue);
        return response()->json($transaksi);
    }

    public function index2(Request $request)
    {
        // Ambil tanggal awal dari request jika tersedia, jika tidak, gunakan tanggal hari ini
        $tanggal_awal = $request->input('tanggal_awal', Carbon::today()->toDateString());

        // Ambil tanggal akhir dari request jika tersedia, jika tidak, tambahkan 2 bulan ke tanggal awal
        $tanggal_akhir = $request->input('tanggal_akhir', Carbon::parse($tanggal_awal)->toDateString());

        // Ambil semua post dengan rentang tanggal yang sesuai
        $posts = Transaksi::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();

        return response()->json($posts);
    }

    public function totalPendapatanPerHari(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $id = $request->input('id'); // Menerima ID kasir sebagai array dari permintaan

        $totals = Transaksi::totalPendapatanPerHari($tanggal_awal, $tanggal_akhir, $id);

        return response()->json($totals);
    }

    public function store(Request $request)
    {
        // Pengecekan apakah ada detail transaksi
        if (!isset($request->details) || empty($request->details)) {
            return response()->json(['error' => 'Tidak ada detail transaksi yang dikirimkan'], 400);
        }

        // Inisialisasi total transaksi
        $total_harga = 0;

        foreach ($request->details as $detail) {
            // Ambil harga dari tabel lain berdasarkan idservice
            $harga = Service::findOrFail($detail['idservice'])->harga;
            $total_harga += $harga;
        }

        $jumlah_bayar = $request->input('jumlah_bayar');
        $kembali = $jumlah_bayar - $total_harga;

        // Tentukan nilai status
        $status = $jumlah_bayar ? 1 : 0;

        // Jika status adalah 0, maka kembali juga disetel menjadi 0
        if ($status === 0) {
            $kembali = 0;
        }

        // $transaksi = Transaksi::createNewInvoice([
        //     'nama_pelanggan' => $request->input('nama_pelanggan'),
        //     'total' => $total_harga,
        //     'kembali' => $kembali,
        //     'jumlah_bayar' => $request->input('jumlah_bayar'),
        //     'status' => $status,
        //     'idkasir' => $request->input('idkasir'),
        //     'booking' => $request->input('booking'),
        // ]);

        // // Simpan detail transaksi
        // foreach ($request->details as $detail) {
        //     // Ambil harga dari tabel lain berdasarkan idservice
        //     $harga = Service::findOrFail($detail['idservice'])->harga;

        //     Detail::create([
        //         'idtransaksi' => $transaksi->idtransaksi,
        //         'idservice' => $detail['idservice'],
        //         'harga' => $harga,
        //     ]);
        // }

        // return response()->json(['transaksi' => $transaksi], 201);      

        $nama_pelanggan = $request->input('nama_pelanggan');
        $alamat_pelanggan = $request->input('alamat');
        $telp_pelanggan = $request->input('telp');

        // Cek apakah pelanggan sudah ada berdasarkan nama
        $existing_pelanggan = Pelanggan::where('nama_pelanggan', $nama_pelanggan)->first();

        if ($existing_pelanggan) {
            // Jika pelanggan sudah ada, gunakan data pelanggan yang sudah ada
            $pelanggan_id = $existing_pelanggan->idpelanggan;
        } else {
            // Jika pelanggan belum ada, buat pelanggan baru
            $new_pelanggan = Pelanggan::create([
                'nama_pelanggan' => $nama_pelanggan,
                'alamat' => $alamat_pelanggan,
                'telp' => $telp_pelanggan,
            ]);

            $pelanggan_id = $new_pelanggan->idpelanggan;
        }

        $booking = $request->input('booking');
        $data = [
            'nama_pelanggan' => $request->input('nama_pelanggan'),
            'total' => $total_harga,
            'kembali' => $kembali,
            'jumlah_bayar' => $request->input('jumlah_bayar'),
            'status' => $status,
            'id' => $request->input('id'),
            'booking' => $booking,
        ];

        // Jika booking == 1, gunakan tanggal dan waktu dari input Postman
        if ($booking == 1) {
            $data['tanggal'] = $request->input('tanggal');
            $data['waktu'] = $request->input('waktu');
        } else {
            // Jika booking != 1, gunakan tanggal dan waktu dari sistem
            $data['tanggal'] = date('Y-m-d');
            $data['waktu'] = date('H:i:s');
        }

        $transaksi = Transaksi::createNewInvoice($data);

        // Simpan detail transaksi
        foreach ($request->details as $detail) {
            // Ambil harga dari tabel lain berdasarkan idservice
            $harga = Service::findOrFail($detail['idservice'])->harga;
            $idkategori = Service::findOrFail($detail['idservice'])->idkategori;

            Detail::create([
                'idtransaksi' => $transaksi->idtransaksi,
                'idservice' => $detail['idservice'],
                'idkategori' => $idkategori,
                // 'kategori' => $idkategori,
                'harga' => $harga,
            ]);
        }

        // foreach ($request->pelanggans as $pelanggan) {
        //     Pelanggan::create([
        //         'nama_pelanggan' => $pelanggan['nama_pelanggan'],
        //         'alamat' => $pelanggan['alamat'],
        //         'telp' => $pelanggan['telp'],
        //     ]);
        // }

        return response()->json(['transaksi' => $transaksi], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi, $id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Data Transaksi Tidak Ditemukan'], 404);
        }

        return response()->json($transaksi, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Inisialisasi total transaksi
$total_harga = 0;

foreach ($request->details as $detail) {
    // Ambil harga dari tabel lain berdasarkan idservice
    $harga = Service::findOrFail($detail['idservice'])->harga;
    $total_harga += $harga;
}

$jumlah_bayar = $request->input('jumlah_bayar');
$kembali = $jumlah_bayar - $total_harga;

// Tentukan nilai status
$status = $jumlah_bayar ? 1 : 0;

// Jika status adalah 0, maka kembali juga disetel menjadi 0
if ($status === 0) {
    $kembali = 0;
}

$transaksi = Transaksi::find($id);

if (!$transaksi) {
    return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
}

$transaksi->update([
    'jumlah_bayar' => $jumlah_bayar,
    'kembali' => $kembali,
    'status' => $status,
]);

return response()->json(['message' => 'Transaksi berhasil diperbarui', 'transaksi' => $transaksi], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Data Transaksi Tidak Ditemukan'], 404);
        }

        $transaksi->delete();

        return response()->json(['message' => 'Data Transaksi berhasil dihapus'], 200);
    }
}
