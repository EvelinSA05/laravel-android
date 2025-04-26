<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $primaryKey = 'idservice';

    protected $fillable = [
        'idkategori',
        'nama_jasa',
        'harga'
    ];
}
