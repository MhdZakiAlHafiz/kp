<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicDev extends Model
{
    use HasFactory;

    // Hapus baris protected $table agar Laravel otomatis menggunakan tabel 'pic_devs'
    protected $fillable = ['name']; // Kolom yang bisa diisi
}
