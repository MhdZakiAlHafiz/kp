<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicPlan extends Model
{
    use HasFactory;

    // Hapus baris protected $table agar Laravel otomatis menggunakan tabel 'pic_plans'
    protected $fillable = ['name']; // Kolom yang bisa diisi
}
