<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarangKeluar extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_masuk_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
