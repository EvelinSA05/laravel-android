<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    protected $primaryKey = 'iddetail';

    protected $fillable = [
       'idtransaksi',
       'idservice',
       'idkategori',
       'harga',
    ];
}
