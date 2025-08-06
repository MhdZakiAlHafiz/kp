<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

// --- DITAMBAHKAN 1: Import Trait dan class LogOptions dari paket ---
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @property string $jenis_surat
 * @property string $nomor_cr
 * @property string $owner
 * @property string $jenis
 * @property string $target
 * @property string $target_disepakati
 * @property string $target_kesepakatan
 * @property string $detail_pengembangan
 * @property array|null $pic_perencana
 * @property array|null $pic_pelaksana
 * @property string|null $keterangan
 * @property float $progres
 * @property string $status
 * @property string|null $nomor_catatan_permintaan
 * @property array|null $kegiatan_detail
 */
class dataProyek extends Model
{
    // --- DITAMBAHKAN 2: Gunakan Trait LogsActivity di dalam class ---
    use HasFactory, LogsActivity;

    protected $table = 'data_proyeks';

    protected $fillable = [
        'jenis_surat',
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
        'kegiatan_detail'
    ];

    protected $casts = [
        'kegiatan_detail' => 'array',
        'pic_perencana' => 'array',
        'pic_pelaksana' => 'array',
        'owner' => 'array',
        'target_kesepakatan' => 'date',
    ];

    /**
     * =================================================================
     * ACCESSORS & MUTATORS (Kode Anda yang sudah ada - TIDAK DIUBAH)
     * =================================================================
     */

    protected function targetStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->target_kesepakatan) {
                    return [
                        'text' => 'Tidak diatur',
                        'class' => 'text-muted'
                    ];
                }
                $deadline = $this->target_kesepakatan;
                $sisaHari = now()->startOfDay()->diffInDays($deadline, false);
                $warna = 'text-dark';
                $teks = "Sisa {$sisaHari} hari";

                if ($sisaHari < 0) {
                    $warna = 'text-secondary font-weight-bold';
                    $teks = "Terlambat " . abs($sisaHari) . " hari";
                } elseif ($sisaHari <= 7) {
                    $warna = 'text-danger font-weight-bold';
                } elseif ($sisaHari <= 14) {
                    $warna = 'text-warning font-weight-bold';
                }

                return [
                    'text' => $teks,
                    'class' => $warna
                ];
            }
        );
    }

    /**
     * =================================================================
     * ACTIVITY LOG CONFIGURATION (Kode baru untuk logging)
     * =================================================================
     */

    // --- DITAMBAHKAN 3: Method untuk mengkonfigurasi log ---
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Memberi nama log agar mudah difilter
            ->useLogName('Proyek')

            // Catat log hanya jika ada atribut yang benar-benar berubah
            ->logOnlyDirty()

            // Tentukan atribut (kolom) mana saja yang ingin Anda lacak perubahannya
            ->logOnly(['nomor_cr', 'status', 'progres', 'pic_perencana', 'pic_pelaksana', 'target_kesepakatan'])

            // Membuat deskripsi log menjadi lebih mudah dibaca
            ->setDescriptionForEvent(function (string $eventName) {
                $eventMap = [
                    'created' => 'membuat dokumen proyek baru',
                    'updated' => 'memperbarui data dokumen proyek',
                    'deleted' => 'menghapus dokumen proyek'
                ];
                return "Telah " . ($eventMap[$eventName] ?? $eventName);
            });
    }
}