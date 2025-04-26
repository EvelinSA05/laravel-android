<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaksi extends Model
{
    use HasFactory;

    protected $primaryKey = 'idtransaksi';

    protected $fillable = [
        'no_faktur',
        'tanggal',
        'waktu',
        'nama_pelanggan',
        'total',
        'jumlah_bayar',
        'kembali',
        'status',
        'no_antrian',
        'id',
        'booking'
    ];

    public static function totalPendapatanPerHari($tanggal_awal, $tanggal_akhir, $id)
    {
        return static::select('id', DB::raw('SUM(total) as total_pendapatan'))
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->whereIn('id', $id)
            ->groupBy('id')
            ->get();
    }

    public static function transaksiByBooking($bookingValue)
    {
        return static::where('booking', $bookingValue)->get();
    }

    public static function generateNumber()
    {
        $lastInvoice = self::orderBy('idtransaksi', 'desc')->first();
        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->no_faktur, -4)); // Ambil 4 digit terakhir
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // return date('Ymd') . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        return date('d') . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public static function generateQueueNumber()
    {
        $todayDate = date('Y-m-d');
        $lastInvoiceToday = self::whereDate('tanggal', $todayDate)->orderBy('no_antrian', 'desc')->first();
        $nextQueueNumber = ($lastInvoiceToday) ? $lastInvoiceToday->no_antrian + 1 : 1;

        return $nextQueueNumber;
    }

    public static function isQueueNumberUsed($queueNumber)
    {
        return self::where('no_antrian', $queueNumber)->exists();
    }

    public static function createNewInvoice($data)
    {

        // if (isset($data['total']) && isset($data['jumlah_bayar'])) {
        //     $data['kembali'] = $data['jumlah_bayar'] - $data['total'];
        // }

        // if ($data['jumlah_bayar'] != 0) {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = date('Y-m-d');
        //     $data['no_antrian'] = self::generateQueueNumber();

        //     return self::create($data);
        // } else{
        //     $data['no_faktur'] = 0;
        //     $data['tanggal'] = date('Y-m-d');
        //     $data['no_antrian'] = 0;

        //     return self::create($data);
        // }

        // if ($data['jumlah_bayar'] != 0) {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = date('Y-m-d');
        //     $data['waktu'] =  date('H:i:s', time());
        //     $data['no_antrian'] = self::generateQueueNumber();
        //     return self::create($data);
        // } else {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = date('Y-m-d');
        //     $data['waktu'] =  date('H:i:s', time());
        //     $data['no_antrian'] = 0;
        //     return self::create($data);
        // }

        // if ($data['booking'] == 1) {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = $data['tanggal'];
        //     $data['waktu'] = $data['waktu'];
        //     $data['no_antrian'] = 0;
        //     return self::create($data);
        // } else {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = date('Y-m-d');
        //     $data['waktu'] =  date('H:i:s', time());;
        //     $data['no_antrian'] = 0;
        //     return self::create($data);
        // }

        // if ($data['jumlah_bayar'] != 0 || $data['booking'] == 1) {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = ($data['booking'] == 1) ? $data['tanggal'] : date('Y-m-d');
        //     $data['waktu'] = ($data['booking'] == 1) ? $data['waktu'] : date('H:i:s');
        //     $data['no_antrian'] = ($data['jumlah_bayar'] != 0) ? self::generateQueueNumber() : 0;
        //     return self::create($data);
        // } else {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = date('Y-m-d');
        //     $data['waktu'] = date('H:i:s');
        //     $data['no_antrian'] = 0;
        //     return self::create($data);
        // }

        // if ($data['jumlah_bayar'] != 0 || $data['booking'] == 1) {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = ($data['booking'] == 1) ? $data['tanggal'] : date('Y-m-d');
        //     $data['waktu'] = ($data['booking'] == 1) ? $data['waktu'] : date('H:i:s');
        //     $data['no_antrian'] = ($data['booking'] == 1) ? 0 : self::generateQueueNumber();
        //     return self::create($data);
        // } else {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = date('Y-m-d');
        //     $data['waktu'] = date('H:i:s');
        //     $data['no_antrian'] = 0;
        //     return self::create($data);
        // }

        // if ($data['jumlah_bayar'] != 0 || $data['booking'] == 1) {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = ($data['booking'] == 1) ? $data['tanggal'] : date('Y-m-d');
        //     $data['waktu'] = ($data['booking'] == 1) ? $data['waktu'] : date('H:i:s');
        //     $data['no_antrian'] = ($data['booking'] == 1) ? 0 : self::generateQueueNumber();
        //     return self::create($data);
        // } else {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = date('Y-m-d');
        //     $data['waktu'] = date('H:i:s');
        //     $data['no_antrian'] = 0;
        //     return self::create($data);
        // }

        // if ($data['jumlah_bayar'] != 0 || $data['booking'] == 1) {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = ($data['booking'] == 1) ? $data['tanggal'] : date('Y-m-d');
        //     $data['waktu'] = ($data['booking'] == 1) ? $data['waktu'] : date('H:i:s');
        //     $data['no_antrian'] = ($data['booking'] == 1) ? 0 : self::getLastQueueNumber() + 1;
        //     return self::create($data);
        // } else {
        //     $data['no_faktur'] = self::generateNumber();
        //     $data['tanggal'] = date('Y-m-d');
        //     $data['waktu'] = date('H:i:s');
        //     $data['no_antrian'] = 0;

        //     // Tambahkan perulangan untuk mendapatkan nomor antrian yang belum digunakan
        //     do {
        //         $queueNumber = self::getLastQueueNumber() + 1;
        //     } while (self::isQueueNumberUsed($queueNumber)); // Buat fungsi untuk memeriksa apakah nomor antrian telah digunakan

        //     $data['no_antrian'] = $queueNumber;
        //     return self::create($data);
        // }

        if ($data['jumlah_bayar'] != 0 || $data['booking'] == 1) {
            $data['no_faktur'] = self::generateNumber();
            $data['tanggal'] = ($data['booking'] == 1) ? $data['tanggal'] : date('Y-m-d');
            $data['waktu'] = ($data['booking'] == 1) ? $data['waktu'] : date('H:i:s');
            $data['no_antrian'] = ($data['booking'] == 1) ? 0 : self::generateQueueNumber();
            return self::create($data);
        } else {
            $data['no_faktur'] = self::generateNumber();
            $data['tanggal'] = date('Y-m-d');
            $data['waktu'] = date('H:i:s');

            // Tambahkan perulangan untuk mendapatkan nomor antrian yang belum digunakan
            do {
                $queueNumber = self::generateQueueNumber();
            } while (self::isQueueNumberUsed($queueNumber)); // Buat fungsi untuk memeriksa apakah nomor antrian telah digunakan

            $data['no_antrian'] = $queueNumber;
            return self::create($data);
        }
    }

    // public static function createNewInvoice($data)
    // {
    //     if (isset($data['total']) && isset($data['jumlah_bayar'])) {
    //         $data['kembali'] = $data['jumlah_bayar'] - $data['total'];
    //     }

    //     // Generate nomor faktur dan nomor antrian
    //     $data['no_faktur'] = self::generateNumber();
    //     $data['tanggal'] = date('Y-m-d');
    //     $data['no_antrian'] = self::generateQueueNumber();

    //     // Buat dan simpan transaksi
    //     return self::create($data);
    // }
}
