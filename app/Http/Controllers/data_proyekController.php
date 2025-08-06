<?php

namespace App\Http\Controllers;

use App\Models\data_proyek;
use App\Models\dataProyek;
use App\Models\PicDev; // Import model PicDev
use App\Models\PicPlan; // Import model PicPlan
use App\Models\User; // Tetap gunakan User jika perlu filter berdasarkan bidang untuk tujuan lain
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\JenisSurat;  // <-- TAMBAHKAN INI
use App\Models\Owner;       // <-- TAMBAHKAN INI
use App\Models\JenisProyek; // <-- TAMBAHKAN INI
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class data_proyekController extends Controller
{
    public function index(Request $request)
    {
        $query = dataProyek::query();

        // Logika Pencarian
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nomor_cr', 'like', '%' . $searchTerm . '%')
                    ->orWhere('owner', 'like', '%' . $searchTerm . '%')
                    ->orWhere('jenis', 'like', '%' . $searchTerm . '%')
                    ->orWhere('target', 'like', '%' . $searchTerm . '%')
                    ->orWhere('target_disepakati', 'like', '%' . $searchTerm . '%')
                    ->orWhere('target_kesepakatan', 'like', '%' . $searchTerm . '%')
                    ->orWhere('detail_pengembangan', 'like', '%' . $searchTerm . '%')
                    ->orWhere('pic_perencana', 'like', '%' . $searchTerm . '%') // Diubah untuk mencari di JSON
                    ->orWhere('pic_pelaksana', 'like', '%' . $searchTerm . '%') // Diubah untuk mencari di JSON
                    ->orWhere('keterangan', 'like', '%' . $searchTerm . '%')
                    ->orWhere('progres', 'like', '%' . $searchTerm . '%')
                    ->orWhere('status', 'like', '%' . $searchTerm . '%')
                    ->orWhere('nomor_catatan_permintaan', 'like', '%' . $searchTerm . '%');
            });
        }

        $data_proyeks = $query->latest()->paginate(5);

        return view('pages.data_proyek.index', [
            'data_proyeks' => $data_proyeks,
        ]);
    }
    public function create()
    {
        // Mengambil semua data dari tabel masing-masing untuk dropdown
        $pic_plan = PicPlan::all();
        $pic_dev = PicDev::all();
        $jenis_surats = JenisSurat::all();
        $owners = Owner::all();
        $jenis_proyeks = JenisProyek::all();

        // Mengirimkan semua variabel ke view
        return view('pages.data_proyek.create', compact(
            'pic_plan',
            'pic_dev',
            'jenis_surats',
            'owners',
            'jenis_proyeks'
        ));
    }

    // Pastikan method store() Anda seperti ini
    public function store(Request $request)
    {
        // Aturan validasi yang mengharapkan array
        $data = $request->validate([
            'jenis_surat' => ['required', 'array'],
            'owner' => ['required', 'array'],
            'jenis' => ['required', 'array'],
            'target' => ['required', 'date_format:Y-m'],
            'target_disepakati' => ['required', 'date_format:Y-m'],
            'target_kesepakatan' => ['required', 'date_format:Y-m'],
            'detail_pengembangan' => ['required'],
            'pic_perencana' => ['nullable', 'array'],
            'pic_pelaksana' => ['nullable', 'array'],
            'keterangan' => ['nullable'],
            'progres' => ['nullable', 'numeric', 'between:0,100'],
            'nomor_catatan_permintaan' => ['nullable'],
        ]);

        // Logika pembuatan nomor CR
        $jenisSuratPertama = $request->input('jenis_surat')[0] ?? 'CR';
        $tahun = date('Y');
        $count = dataProyek::where('nomor_cr', 'LIKE', '%/' . $jenisSuratPertama . '/TSI/' . $tahun)->count();
        $nextNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        $data['nomor_cr'] = $nextNumber . '/' . $jenisSuratPertama . '/TSI/' . $tahun;

        // Konversi semua input array menjadi JSON sebelum disimpan
        $data['jenis_surat'] = json_encode($data['jenis_surat']);
        $data['owner'] = json_encode($data['owner']);
        $data['jenis'] = json_encode($data['jenis']);
        $data['pic_perencana'] = is_array($data['pic_perencana']) ? json_encode($data['pic_perencana']) : null;
        $data['pic_pelaksana'] = is_array($data['pic_pelaksana']) ? json_encode($data['pic_pelaksana']) : null;

        // Logika status
        $progress = $data['progres'] ?? 0;
        if ($progress == 0) {
            $data['status'] = 'Not Started';
        } elseif ($progress < 100) {
            $data['status'] = 'On Progress';
        } else {
            $data['status'] = 'Completed';
        }

        // Default kegiatan_detail
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

        dataProyek::create($data);

        return redirect('/data_proyek')->with('sukses', 'Dokumen baru berhasil ditambahkan!');
    }



    public function generateNomorCr($jenis_surat)
    {
        $tahun = date('Y');
        $count = dataProyek::where('nomor_cr', 'LIKE', '%/' . $jenis_surat . '/TSI/' . $tahun)->count();
        $nextNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        $nomor_cr = $nextNumber . '/' . $jenis_surat . '/TSI/' . $tahun;
        return response()->json(['nomor_cr' => $nomor_cr]);
    }

    // GANTI METHOD LAMA DENGAN INI
    public function edit($id)
    {
        $data_proyek = dataProyek::findOrFail($id);

        // Mengambil semua data dari tabel masing-masing
        $pic_plan = PicPlan::all();
        $pic_dev = PicDev::all();
        $jenis_surats = JenisSurat::all();
        $owners = Owner::all();
        $jenis_proyeks = JenisProyek::all();

        // Mengirimkan semua variabel ke view
        return view('pages.data_proyek.edit', compact(
            'data_proyek',
            'pic_plan',
            'pic_dev',
            'jenis_surats',
            'owners',
            'jenis_proyeks'
        ));
    }
    // GANTI METHOD LAMA DENGAN INI

    public function update(Request $request, $id)
    {
        $data_proyek = dataProyek::findOrFail($id);

        $validated = $request->validate([
            // BARU: Tambahkan validasi untuk nomor_cr
            'nomor_cr' => [
                'required',
                // Pastikan nomor unik, kecuali untuk data yang sedang diedit
                Rule::unique('data_proyeks')->ignore($data_proyek->id),
            ],
            'jenis_surat' => ['required', 'array'],
            'owner' => ['required', 'array'],
            'jenis' => ['required', 'array'],
            'target' => ['required', 'date_format:Y-m'],
            'target_disepakati' => ['required', 'date_format:Y-m'],
            'target_kesepakatan' => ['required', 'date_format:Y-m'],
            'detail_pengembangan' => ['required'],
            'pic_perencana' => ['nullable', 'array'],
            'pic_pelaksana' => ['nullable', 'array'],
            'keterangan' => ['nullable'],
            'progres' => ['nullable', 'numeric', 'between:0,100'],
            'nomor_catatan_permintaan' => ['nullable'],
        ]);

        // Konversi data array menjadi JSON (tidak berubah)
        $validated['jenis_surat'] = json_encode($validated['jenis_surat']);
        $validated['owner'] = json_encode($validated['owner']);
        $validated['jenis'] = json_encode($validated['jenis']);
        $validated['pic_perencana'] = is_array($validated['pic_perencana']) ? json_encode($validated['pic_perencana']) : null;
        $validated['pic_pelaksana'] = is_array($validated['pic_pelaksana']) ? json_encode($validated['pic_pelaksana']) : null;

        // Simpan semua data yang sudah divalidasi
        $data_proyek->update($validated);

        return redirect('/data_proyek')->with('sukses', 'Data dokumen berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data_proyek = dataProyek::findOrFail($id);
        $data_proyek->delete();

        return redirect('/data_proyek')->with('sukses', 'Dokumen berhasil dihapus!');
    }

    public function kegiatanDetail($id)
    {
        $data_proyek = dataProyek::findOrFail($id);
        $nested_kegiatan_detail = $data_proyek->kegiatan_detail ?? [];

        // Pastikan ini array
        if (is_string($nested_kegiatan_detail)) {
            $decoded = json_decode($nested_kegiatan_detail, true);
            $nested_kegiatan_detail = is_array($decoded) ? $decoded : [];
        }


        $calculateNestedProgress = function (&$items) use (&$calculateNestedProgress, $data_proyek) {
            foreach ($items as &$item) {
                $item['progress'] = $item['progress'] ?? 0;

                if (isset($item['sub']) && is_array($item['sub']) && !empty($item['sub']) && $item['no'] === "1") {
                    $calculateNestedProgress($item['sub']);
                    $sumOfSubProgresses = 0;
                    foreach ($item['sub'] as $subItem) {
                        $sumOfSubProgresses += ($subItem['progress'] ?? 0);
                    }
                    $item['progress'] = min($item['bobot'], round($sumOfSubProgresses, 2));
                    $item['__read_only_progress'] = true;
                } else {
                    $item['__read_only_progress'] = false;
                }

                $item['plan_start'] = $item['plan_start'] ?? null;
                $item['plan_end'] = $item['plan_end'] ?? null;
                $item['actual_start'] = $item['actual_start'] ?? null;
                $item['actual_end'] = $item['actual_end'] ?? null;

                if (empty($item['keterangan'])) {
                    $item['keterangan'] = $data_proyek->detail_pengembangan ?? null;
                }

                if (empty($item['pic'])) {
                    $pic_parts = [];

                    // Decode jika masih string JSON
                    $decoded_pic_perencana = is_string($data_proyek->pic_perencana)
                        ? json_decode($data_proyek->pic_perencana, true)
                        : $data_proyek->pic_perencana;

                    $decoded_pic_pelaksana = is_string($data_proyek->pic_pelaksana)
                        ? json_decode($data_proyek->pic_pelaksana, true)
                        : $data_proyek->pic_pelaksana;

                    $pic_parts = [];

                    if (is_array($decoded_pic_perencana)) {
                        $pic_parts = array_merge($pic_parts, $decoded_pic_perencana);
                    }

                    if (is_array($decoded_pic_pelaksana)) {
                        $pic_parts = array_merge($pic_parts, $decoded_pic_pelaksana);
                    }

                    $item['pic'] = implode(', ', array_unique($pic_parts));

                }
            }
        };
        $calculateNestedProgress($nested_kegiatan_detail);
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
            'kegiatan_detail' => $flattened_kegiatan_detail,
        ]);
    }

    public function updateKegiatanDetail(Request $request, $id)
    {
        $validatedData = $request->validate([
            'kegiatan_detail' => ['required', 'array'],
            'kegiatan_detail.*.no' => ['required'],
            'kegiatan_detail.*.kegiatan' => ['required'],
            'kegiatan_detail.*.bobot' => ['required', 'numeric'],
            'kegiatan_detail.*.progress' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    preg_match('/kegiatan_detail\.(\d+)\.progress/', $attribute, $matches);
                    $index = $matches[1];
                    $bobot = $request->input("kegiatan_detail.{$index}.bobot");
                    $kegiatan_no = $request->input("kegiatan_detail.{$index}.no");
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
            'kegiatan_detail.*.__original_path' => ['required', 'string'],
        ]);

        /** @var \App\Models\dataProyek $data_proyek */
        $data_proyek = dataProyek::findOrFail($id);

        $raw_kegiatan = $data_proyek->kegiatan_detail;
        $nested_kegiatan_detail = is_string($raw_kegiatan) ? json_decode($raw_kegiatan, true) : ($raw_kegiatan ?? []);

        $submitted_flat_data = collect($validatedData['kegiatan_detail'])->keyBy('__original_path');

        $updateNested = function (&$items, $parentPath = '') use (&$updateNested, $submitted_flat_data) {
            foreach ($items as $key => &$item) {
                $currentPath = $parentPath === '' ? (string) $key : $parentPath . '.' . $key;
                if ($submitted_flat_data->has($currentPath)) {
                    $submittedItem = $submitted_flat_data->get($currentPath);
                    if ($item['no'] !== "1") {
                        $item['progress'] = min($item['bobot'], (float) ($submittedItem['progress'] ?? 0));
                    }
                    $item['plan_start'] = $submittedItem['plan_start'] ?? null;
                    $item['plan_end'] = $submittedItem['plan_end'] ?? null;
                    $item['actual_start'] = $submittedItem['actual_start'] ?? null;
                    $item['actual_end'] = $submittedItem['actual_end'] ?? null;
                    $item['keterangan'] = $submittedItem['keterangan'] ?? null;
                    $item['pic'] = $submittedItem['pic'] ?? null;
                }
                if (isset($item['sub']) && is_array($item['sub'])) {
                    $updateNested($item['sub'], $currentPath);
                }
            }
        };

        $updateNested($nested_kegiatan_detail);

        $recalculateParentProgress = function (&$items) use (&$recalculateParentProgress) {
            foreach ($items as &$item) {
                if (isset($item['sub']) && is_array($item['sub']) && !empty($item['sub']) && $item['no'] === "1") {
                    $recalculateParentProgress($item['sub']);
                    $sumOfSubProgresses = 0;
                    foreach ($item['sub'] as $subItem) {
                        $sumOfSubProgresses += ($subItem['progress'] ?? 0);
                    }
                    $item['progress'] = min($item['bobot'], round($sumOfSubProgresses, 2));
                }
            }
        };
        $recalculateParentProgress($nested_kegiatan_detail);

        $data_proyek->kegiatan_detail = json_encode($nested_kegiatan_detail);

        $totalProjectProgress = 0;
        foreach ($nested_kegiatan_detail as $item) {
            $totalProjectProgress += ($item['progress'] ?? 0);
        }

        // Total progres yang sudah dijumlahkan disimpan ke kolom 'progres'
        $data_proyek->progres = min(100, round($totalProjectProgress, 2));

        // Status juga diperbarui berdasarkan nilai progres baru
        if ($data_proyek->progres == 0) {
            $data_proyek->status = 'Not Started';
        } elseif ($data_proyek->progres < 100) {
            $data_proyek->status = 'On Progress';
        } else {
            $data_proyek->status = 'Completed';
        }

        // Perubahan disimpan ke database
        $data_proyek->save();

        return redirect()->route('data_proyek.index')->with('sukses', 'Update progres dokumen diperbarui');
    }

    public function dashboard()
    {
        // 1. Data untuk Kartu Statistik
        $totalProyek = dataProyek::count();
        $proyekSelesai = dataProyek::where('status', 'Completed')->count();
        $proyekBerjalan = dataProyek::where('status', 'On Progress')->count();
        $akunMenunggu = User::where('status', 'submitted')->count();

        // 2. Data untuk tabel "Status Target Proyek"
        $semuaProyek = dataProyek::where('status', '!=', 'Completed')
            ->orderBy('target_kesepakatan', 'asc')
            ->paginate(5, ['*'], 'proyek_page');

        // --- DIPERBAIKI: Transformasi data owner untuk tampilan tabel agar tidak terlalu panjang ---
        $semuaProyek->getCollection()->transform(function ($proyek) {
            $owners = $proyek->owner; // Ini sudah di-cast menjadi array oleh model

            // Lakukan pengecekan untuk memastikan $owners adalah array
            if (!is_array($owners)) {
                $decoded = json_decode($owners, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $owners = $decoded;
                }
            }

            if (is_array($owners)) {
                // Batasi jumlah owner yang ditampilkan menjadi 2
                if (count($owners) > 2) {
                    $proyek->owner = implode(', ', array_slice($owners, 0, 2)) . ', ...';
                } else {
                    $proyek->owner = implode(', ', $owners);
                }
            }
            // Jika $owners bukan array setelah semua pengecekan, biarkan apa adanya.

            return $proyek;
        });
        // --- AKHIR PERBAIKAN ---

        // 3. Data untuk Grafik
        // Query untuk chart status
        $statusCounts = dataProyek::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status');

        // Query untuk chart owner (Logika baru yang lebih aman)
        $ownerData = dataProyek::whereNotNull('owner')->pluck('owner');
        $ownerCounts = $ownerData
            ->flatMap(function ($owner) {
                return is_array($owner) ? $owner : (json_decode($owner, true) ?? []);
            })
            ->filter() // Hapus nilai kosong setelah decode
            ->countBy();

        // Query untuk chart tren bulanan
        $monthlyTrends = dataProyek::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->keyBy('month');

        $trendLabels = [];
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthName = $date->translatedFormat('F Y');

            $trendLabels[] = $monthName;
            $trendData[] = $monthlyTrends->get($monthKey)->total ?? 0;
        }

        // Query untuk chart beban kerja PIC (Logika baru yang lebih aman)
        $activeProjects = dataProyek::where('status', '!=', 'Completed')->select('pic_perencana', 'pic_pelaksana')->get();
        $picWorkload = $activeProjects
            ->flatMap(function ($project) {
                $perencana = is_array($project->pic_perencana) ? $project->pic_perencana : (json_decode($project->pic_perencana, true) ?? []);
                $pelaksana = is_array($project->pic_pelaksana) ? $project->pic_pelaksana : (json_decode($project->pic_pelaksana, true) ?? []);
                return array_merge($perencana, $pelaksana);
            })
            ->filter() // Hapus nilai kosong setelah decode
            ->countBy();

        // Menyiapkan semua data chart untuk dikirim ke view
        $chartData = [
            'status' => ['labels' => $statusCounts->keys(), 'data' => $statusCounts->values()],
            'owner' => ['labels' => $ownerCounts->keys(), 'data' => $ownerCounts->values()],
            'trends' => ['labels' => $trendLabels, 'data' => $trendData],
            'workload' => ['labels' => $picWorkload->keys(), 'data' => $picWorkload->values()],
        ];

        // 4. Mengirim semua data ke view
        return view('pages.dashboard', compact(
            'totalProyek',
            'proyekSelesai',
            'proyekBerjalan',
            'akunMenunggu',
            'semuaProyek',
            'chartData'
            // Variabel $proyekSaya dihapus karena tidak digunakan lagi
        ));
    }


    public function generatePDF($id)
    {
        // Ambil data proyek berdasarkan ID
        $data_proyek = dataProyek::findOrFail($id);

        // Siapkan data untuk dikirim ke view PDF
        $data = [
            'data_proyek' => $data_proyek
        ];

        // Muat view pdf.blade.php dengan data
        $pdf = Pdf::loadView('pages.data_proyek.pdf', $data);

        // --- PERBAIKAN: Ganti karakter '/' dengan '-' pada nama file ---
        $safe_nomor_cr = str_replace('/', '-', $data_proyek->nomor_cr);
        $fileName = 'Dokumen-' . $safe_nomor_cr . '.pdf';
        // --- AKHIR PERBAIKAN ---

        // Tampilkan PDF di browser (stream)
        return $pdf->stream($fileName);
    }

    public function updateStatusAjax(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);

        try {
            $proyek = dataProyek::findOrFail($id);
            $proyek->status = $request->input('status');
            $proyek->save();

            return response()->json(['success' => true, 'message' => 'Status proyek berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status.'], 500);
        }
    }

}