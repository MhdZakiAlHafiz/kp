<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_proyek extends Model
{
    protected $tabel = 'data_proyeks';

    protected $guarded = [];

    protected $fillable = [
        'jenis_surat', // ✅ Tambahkan ini
        'nomor_cr',
        'owner',
        'jenis',
        'target',
        'target_disepakati',
        'target_kesepakatan',
        'detail_pengembangan',
        'pic_perencana',
        'pic_pelaksana',
        'keterangan',
        'progres',
        'status',
        'nomor_catatan_permintaan',
        'kegiatan_detail' // jika ada
    ];
}