<?php

namespace App\Http\Controllers;

use App\Models\data_proyek;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class data_proyekController extends Controller
{
    public function index()
    {
        $data_proyeks = data_proyek::all();

        return view('pages.data_proyek.index', [
            'data_proyeks' => $data_proyeks,
        ]);
    }

    public function create()
    {
        return view('pages.data_proyek.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_surat' => ['required', 'in:BRD,CR'],
            'owner' => ['required', 'string'],
            'jenis' => ['required', 'string'],
            'target' => ['required'],
            'target_disepakati' => ['required'],
            'target_kesepakatan' => ['required'],
            'detail_pengembangan' => ['required'],
            // Validasi untuk array checkbox, bisa kosong jika tidak ada yang dipilih
            'pic_perencana' => ['nullable', 'array'],
            'pic_pelaksana' => ['nullable', 'array'],
            'keterangan' => ['nullable'],
            'progres' => ['nullable', 'numeric', 'between:0,100'],
            'nomor_catatan_permintaan' => ['nullable'],
        ]);

        $tahun = date('Y');
        // Menghitung jumlah proyek dengan jenis surat dan tahun yang sama untuk generate nomor CR
        $count = data_proyek::where('nomor_cr', 'LIKE', '%/' . $data['jenis_surat'] . '/TSI/' . $tahun)->count();
        $nextNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        $data['nomor_cr'] = $nextNumber . '/' . $data['jenis_surat'] . '/TSI/' . $tahun;

        // Mengkonversi array PIC menjadi string yang dipisahkan koma
        $data['pic_perencana'] = is_array($data['pic_perencana']) ? implode(', ', $data['pic_perencana']) : null;
        $data['pic_pelaksana'] = is_array($data['pic_pelaksana']) ? implode(', ', $data['pic_pelaksana']) : null;


        // Menentukan status berdasarkan progres awal
        $progress = $data['progres'] ?? 0;
        if ($progress == 0) {
            $data['status'] = 'Not Started';
        } elseif ($progress < 100) {
            $data['status'] = 'On Progress';
        } else {
            $data['status'] = 'Completed';
        }

        // Inisialisasi struktur kegiatan_detail yang bersarang dalam format JSON
        $data['kegiatan_detail'] = json_encode([
            [
                "no" => "1",
                "kegiatan" => "Pengerjaan Dokumen",
                "bobot" => 20,
                "sub" => [
                    ["no" => "1.1", "kegiatan" => "penyusunan dokumen CR", "bobot" => 10],
                    ["no" => "1.2", "kegiatan" => "diskusi fitur flow", "bobot" => 5],
                    ["no" => "1.3", "kegiatan" => "penyusunan dokumen FSD", "bobot" => 5],
                ]
            ],
            ["no" => "2", "kegiatan" => "Tahap Development", "bobot" => 45],
            ["no" => "3", "kegiatan" => "SIT", "bobot" => 10],
            ["no" => "4", "kegiatan" => "UAT", "bobot" => 10],
            ["no" => "5", "kegiatan" => "Persiapan Migrasi", "bobot" => 5],
            ["no" => "6", "kegiatan" => "Migrasi", "bobot" => 5],
            ["no" => "7", "kegiatan" => "TO", "bobot" => 5],
        ]);

        // Membuat entri data proyek baru di database
        data_proyek::create($data);

        return redirect('/data_proyek')->with('sukses', 'menambahkan data');
    }

    public function generateNomorCr($jenis_surat)
    {
        $tahun = date('Y');
        // Menghitung jumlah proyek dengan jenis surat dan tahun yang sama
        $count = data_proyek::where('nomor_cr', 'LIKE', '%/' . $jenis_surat . '/TSI/' . $tahun)->count();
        $nextNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);

        $nomor_cr = $nextNumber . '/' . $jenis_surat . '/TSI/' . $tahun;

        return response()->json(['nomor_cr' => $nomor_cr]);
    }

    public function edit($id)
    {
        $data_proyek = data_proyek::findOrFail($id);
        return view('pages.data_proyek.edit', [
            'data_proyek' => $data_proyek,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_surat' => ['required', 'in:BRD,CR'],
            'owner' => ['required', 'string'],
            'jenis' => ['required', 'string'],
            'target' => ['required'],
            'target_disepakati' => ['required'],
            'target_kesepakatan' => ['required'],
            'detail_pengembangan' => ['required'],
            'pic_perencana' => ['nullable'],
            'pic_pelaksana' => ['nullable'],
            'keterangan' => ['nullable'],
            'progres' => ['nullable', 'numeric', 'between:0,100'],
            'nomor_catatan_permintaan' => ['nullable'],
        ]);
        data_proyek::findOrFail($id)->update($validated);
        return redirect('/data_proyek')->with('sukses', 'mengubah data');
    }

    public function destroy($id)
    {
        $data_proyek = data_proyek::findOrFail($id);
        $data_proyek->delete();

        return redirect('/data_proyek')->with('sukses', 'menghapus data');
    }

    public function kegiatanDetail($id)
    {
        $data_proyek = data_proyek::findOrFail($id);
        // Mendekode kegiatan_detail dari JSON menjadi array PHP bersarang
        $nested_kegiatan_detail = json_decode($data_proyek->kegiatan_detail, true) ?? [];

        // Fungsi pembantu untuk menghitung progres kegiatan bersarang secara rekursif
        $calculateNestedProgress = function (&$items) use (&$calculateNestedProgress, $data_proyek) {
            foreach ($items as &$item) {
                // Inisialisasi progres jika belum diatur
                $item['progress'] = $item['progress'] ?? 0;

                // Jika kegiatan memiliki sub-kegiatan dan merupakan kegiatan "Pengerjaan Dokumen" (no = 1)
                if (isset($item['sub']) && is_array($item['sub']) && !empty($item['sub']) && $item['no'] === "1") {
                    // Rekursif hitung progres untuk sub-kegiatan terlebih dahulu
                    $calculateNestedProgress($item['sub']);

                    // Logika: Progres Pengerjaan Dokumen adalah JUMLAH progres sub-kegiatan, dibatasi oleh bobotnya sendiri
                    $sumOfSubProgresses = 0;
                    foreach ($item['sub'] as $subItem) {
                        $sumOfSubProgresses += ($subItem['progress'] ?? 0);
                    }
                    // Progres dibatasi oleh bobot kegiatan induk (misal: 20 untuk Pengerjaan Dokumen)
                    $item['progress'] = min($item['bobot'], round($sumOfSubProgresses, 2));
                    $item['__read_only_progress'] = true; // Tandai sebagai read-only untuk view
                } else {
                    $item['__read_only_progress'] = false; // Dapat diedit jika tidak ada sub-kegiatan atau bukan kegiatan no 1
                }

                // Inisialisasi field lain jika belum diatur
                $item['plan_start'] = $item['plan_start'] ?? null;
                $item['plan_end'] = $item['plan_end'] ?? null;
                $item['actual_start'] = $item['actual_start'] ?? null;
                $item['actual_end'] = $item['actual_end'] ?? null;

                // --- Perubahan Baru: Mengisi Keterangan dan PIC dari data_proyek ---
                // Jika keterangan belum ada di kegiatan_detail, ambil dari detail_pengembangan proyek utama
                if (empty($item['keterangan'])) {
                    $item['keterangan'] = $data_proyek->detail_pengembangan ?? null;
                }

                // Jika PIC belum ada di kegiatan_detail, gabungkan dari pic_perencana dan pic_pelaksana proyek utama
                if (empty($item['pic'])) {
                    $pic_parts = [];
                    if (!empty($data_proyek->pic_perencana)) {
                        $pic_parts[] = $data_proyek->pic_perencana;
                    }
                    if (!empty($data_proyek->pic_pelaksana)) {
                        $pic_parts[] = $data_proyek->pic_pelaksana;
                    }
                    // Pastikan tidak ada duplikasi nama jika PIC Plan dan PIC Dev memiliki nama yang sama
                    $item['pic'] = implode(', ', array_unique($pic_parts));
                }
                // --- Akhir Perubahan Baru ---
            }
        };

        // Terapkan perhitungan rekursif ke kegiatan utama
        $calculateNestedProgress($nested_kegiatan_detail);

        // Sekarang ratakan struktur untuk view
        $flattened_kegiatan_detail = [];
        $index = 0;
        $flattenForView = function ($items, $parentPath = '', $isSub = false) use (&$flattened_kegiatan_detail, &$index, &$flattenForView) {
            foreach ($items as $key => $item) {
                $currentPath = $parentPath === '' ? (string) $key : $parentPath . '.' . $key;

                $item['__flat_index'] = $index;
                $item['__is_sub'] = $isSub;
                $item['__original_path'] = $currentPath;

                $flattened_kegiatan_detail[] = $item;
                $index++;

                if (isset($item['sub']) && is_array($item['sub'])) {
                    $flattenForView($item['sub'], $currentPath, true);
                }
            }
        };

        $flattenForView($nested_kegiatan_detail);

        return view('pages.data_proyek.kegiatan_detail', [
            'data_proyek' => $data_proyek,
            'kegiatan_detail' => $flattened_kegiatan_detail, // Kirim array yang sudah diratakan ke view
        ]);
    }

    public function updateKegiatanDetail(Request $request, $id)
    {
        // Validasi data yang masuk dari form (array datar)
        $validatedData = $request->validate([
            'kegiatan_detail' => ['required', 'array'],
            'kegiatan_detail.*.no' => ['required'],
            'kegiatan_detail.*.kegiatan' => ['required'],
            'kegiatan_detail.*.bobot' => ['required', 'numeric'],
            // Validasi progress: numeric, min 0, dan tidak boleh melebihi nilai bobot untuk baris tersebut
            'kegiatan_detail.*.progress' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    // Ekstrak indeks dari nama atribut, misal: kegiatan_detail.0.progress -> 0
                    preg_match('/kegiatan_detail\.(\d+)\.progress/', $attribute, $matches);
                    $index = $matches[1];

                    $bobot = $request->input("kegiatan_detail.{$index}.bobot");
                    $kegiatan_no = $request->input("kegiatan_detail.{$index}.no");

                    // Validasi ini hanya berlaku untuk kegiatan yang progresnya diinput manual
                    // Kegiatan 'Pengerjaan Dokumen' (no=1) progresnya dihitung, bukan diinput
                    if ($kegiatan_no !== "1" && $value > $bobot) {
                        $fail("Progres untuk kegiatan " . $kegiatan_no . " tidak boleh melebihi bobotnya (" . $bobot . ").");
                    }
                },
            ],
            'kegiatan_detail.*.plan_start' => ['nullable', 'date'],
            'kegiatan_detail.*.plan_end' => ['nullable', 'date'],
            'kegiatan_detail.*.actual_start' => ['nullable', 'date'],
            'kegiatan_detail.*.actual_end' => ['nullable', 'date'],
            'kegiatan_detail.*.keterangan' => ['nullable', 'string'],
            'kegiatan_detail.*.pic' => ['nullable', 'string'],
            'kegiatan_detail.*.__original_path' => ['required', 'string'], // Hidden field untuk path asli
        ]);

        $data_proyek = data_proyek::findOrFail($id);
        // Mendekode kegiatan_detail yang ada di DB untuk mendapatkan struktur bersarang asli
        $nested_kegiatan_detail = json_decode($data_proyek->kegiatan_detail, true) ?? [];

        // Ubah array datar yang disubmit menjadi koleksi dengan kunci original_path
        $submitted_flat_data = collect($validatedData['kegiatan_detail'])->keyBy('__original_path');

        // Fungsi untuk memperbarui struktur bersarang asli dengan data yang disubmit
        $updateNested = function (&$items, $parentPath = '') use (&$updateNested, $submitted_flat_data) {
            foreach ($items as $key => &$item) { // Gunakan & untuk referensi agar bisa memodifikasi array asli
                $currentPath = $parentPath === '' ? (string) $key : $parentPath . '.' . $key;

                // Jika item ini ada di data yang disubmit (berdasarkan original_path)
                if ($submitted_flat_data->has($currentPath)) {
                    $submittedItem = $submitted_flat_data->get($currentPath);

                    // Hanya perbarui progres jika bukan kegiatan induk yang progresnya dihitung (no = 1)
                    if ($item['no'] !== "1") {
                        // Progres dibatasi oleh bobotnya sendiri
                        $item['progress'] = min($item['bobot'], (float) ($submittedItem['progress'] ?? 0));
                    }

                    $item['plan_start'] = $submittedItem['plan_start'] ?? null;
                    $item['plan_end'] = $submittedItem['plan_end'] ?? null;
                    $item['actual_start'] = $submittedItem['actual_start'] ?? null;
                    $item['actual_end'] = $submittedItem['actual_end'] ?? null;
                    $item['keterangan'] = $submittedItem['keterangan'] ?? null;
                    $item['pic'] = $submittedItem['pic'] ?? null;
                }

                // Jika ada sub-kegiatan, panggil rekursif
                if (isset($item['sub']) && is_array($item['sub'])) {
                    $updateNested($item['sub'], $currentPath);
                }
            }
        };

        // Terapkan pembaruan dari form ke struktur bersarang
        $updateNested($nested_kegiatan_detail);

        // Sekarang, hitung ulang progres kegiatan induk (khususnya "Pengerjaan Dokumen")
        // setelah semua sub-kegiatan telah diperbarui
        $recalculateParentProgress = function (&$items) use (&$recalculateParentProgress) {
            foreach ($items as &$item) {
                if (isset($item['sub']) && is_array($item['sub']) && !empty($item['sub']) && $item['no'] === "1") {
                    $recalculateParentProgress($item['sub']); // Rekursif hitung anak-anak terlebih dahulu

                    // Logika: Progres Pengerjaan Dokumen adalah JUMLAH progres sub-kegiatan, dibatasi oleh bobotnya sendiri
                    $sumOfSubProgresses = 0;
                    foreach ($item['sub'] as $subItem) {
                        $sumOfSubProgresses += ($subItem['progress'] ?? 0);
                    }
                    // Progres dibatasi oleh bobot kegiatan induk (misal: 20 untuk Pengerjaan Dokumen)
                    $item['progress'] = min($item['bobot'], round($sumOfSubProgresses, 2));
                }
            }
        };
        $recalculateParentProgress($nested_kegiatan_detail);


        // Encode struktur bersarang yang sudah diperbarui kembali ke JSON
        $data_proyek->kegiatan_detail = json_encode($nested_kegiatan_detail);

        // --- Perubahan Logika Perhitungan Progres Proyek Keseluruhan ---
        // Progres proyek keseluruhan adalah penjumlahan progres dari setiap kegiatan utama (level teratas)
        $totalProjectProgress = 0;
        foreach ($nested_kegiatan_detail as $item) {
            $totalProjectProgress += ($item['progress'] ?? 0);
        }

        // Pastikan total progres proyek tidak melebihi 100
        $data_proyek->progres = min(100, round($totalProjectProgress, 2));

        // Menentukan status proyek berdasarkan progres keseluruhan
        if ($data_proyek->progres == 0) {
            $data_proyek->status = 'Not Started';
        } elseif ($data_proyek->progres < 100) {
            $data_proyek->status = 'On Progress';
        } else {
            $data_proyek->status = 'Completed';
        }

        $data_proyek->save();

        return redirect()->route('data_proyek.index')->with('sukses', 'Kegiatan detail diperbarui');
    }
}
