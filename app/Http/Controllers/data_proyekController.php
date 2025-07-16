<?php

namespace App\Http\Controllers;

use App\Models\data_proyek;
use Illuminate\Http\Request;

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
            'pic_perencana' => ['nullable'],
            'pic_pelaksana' => ['nullable'],
            'keterangan' => ['nullable'],
            'progres' => ['nullable', 'numeric', 'between:0,100'],
            'nomor_catatan_permintaan' => ['nullable'],
        ]);

        $tahun = date('Y');
        $count = data_proyek::where('nomor_cr', 'LIKE', '%/' . $data['jenis_surat'] . '/TSI/' . $tahun)->count();
        $nextNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        $data['nomor_cr'] = $nextNumber . '/' . $data['jenis_surat'] . '/TSI/' . $tahun;

        $progress = $data['progres'] ?? 0;
        if ($progress == 0) {
            $data['status'] = 'Not Started';
        } elseif ($progress < 100) {
            $data['status'] = 'On Progress';
        } else {
            $data['status'] = 'Completed';
        }

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

        data_proyek::create($data);

        return redirect('/data_proyek')->with('sukses', 'menambahkan data');
    }

    public function generateNomorCr($jenis_surat)
    {
        $tahun = date('Y');
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
        $kegiatan_detail = json_decode($data_proyek->kegiatan_detail, true) ?? []; // ← Tambahkan `json_decode(..., true)` dan fallback `[]`

        return view('pages.data_proyek.kegiatan_detail', compact('data_proyek', 'kegiatan_detail'));
    }



    public function updateKegiatanDetail(Request $request, $id)
    {
        $data = $request->validate([
            'kegiatan_detail' => ['required', 'array'],
            'kegiatan_detail.*.kegiatan' => ['required'],
            'kegiatan_detail.*.bobot' => ['required', 'numeric'],
            'kegiatan_detail.*.progress' => ['nullable', 'numeric'],
        ]);

        $data_proyek = data_proyek::findOrFail($id);
        $data_proyek->kegiatan_detail = $data['kegiatan_detail'];

        $totalBobot = collect($data['kegiatan_detail'])->sum('bobot');
        $totalProgress = collect($data['kegiatan_detail'])->sum(function ($item) {
            return ($item['bobot'] * $item['progress']) / 100;
        });

        $progresPersen = $totalBobot ? ($totalProgress / $totalBobot) * 100 : 0;
        $data_proyek->progres = round($progresPersen, 2);

        if ($progresPersen == 0) {
            $data_proyek->status = 'Not Started';
        } elseif ($progresPersen < 100) {
            $data_proyek->status = 'On Progress';
        } else {
            $data_proyek->status = 'Completed';
        }

        $data_proyek->keterangan = $data['kegiatan_detail'][0]['keterangan'] ?? '-';

        $data_proyek->save();

        return redirect()->route('data_proyek.index')->with('sukses', 'Kegiatan detail diperbarui');
    }
}
