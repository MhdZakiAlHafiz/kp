<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisSurat;
use App\Models\Owner;
use App\Models\JenisProyek;
use App\Models\PicDev;
use App\Models\PicPlan;

class MasterDataController extends Controller
{
    public function create()
    {
        return view('admin.data_master_create');
    }

    public function manage()
    {
        $data = [
            'jenis_surats' => JenisSurat::latest()->get(),
            'owners' => Owner::latest()->get(),
            'jenis_proyeks' => JenisProyek::latest()->get(),
            'pic_devs' => PicDev::latest()->get(),
            'pic_plans' => PicPlan::latest()->get(),
        ];
        return view('admin.data_master_manage', $data);
    }

    // --- METHODS UNTUK STORE DATA (DENGAN KOREKSI) ---

    public function storeJenisSurat(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255|unique:jenis_surats']);
        $jenisSurat = JenisSurat::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jenis Surat berhasil ditambahkan!',
                'data' => $jenisSurat
            ]);
        }
        return redirect()->route('admin.master.create')->with('sukses', 'Jenis Surat berhasil ditambahkan!');
    }

    public function storeOwner(Request $request)
    {
        // KOREKSI: Gunakan hasil validasi dan tambahkan blok AJAX
        $validated = $request->validate(['name' => 'required|string|max:255|unique:owners']);
        $owner = Owner::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Owner berhasil ditambahkan!', 'data' => $owner]);
        }
        return redirect()->route('admin.master.create')->with('sukses', 'Owner berhasil ditambahkan!');
    }

    public function storeJenisProyek(Request $request)
    {
        // KOREKSI: Gunakan hasil validasi dan tambahkan blok AJAX
        $validated = $request->validate(['name' => 'required|string|max:255|unique:jenis_proyeks']);
        $jenisProyek = JenisProyek::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jenis Proyek berhasil ditambahkan!', 'data' => $jenisProyek]);
        }
        return redirect()->route('admin.master.create')->with('sukses', 'Jenis Proyek berhasil ditambahkan!');
    }

    public function storePicDev(Request $request)
    {
        // KOREKSI: Gunakan hasil validasi dan tambahkan blok AJAX
        $validated = $request->validate(['name' => 'required|string|max:255|unique:pic_devs']);
        $picDev = PicDev::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'PIC Development berhasil ditambahkan!', 'data' => $picDev]);
        }
        return redirect()->route('admin.master.create')->with('sukses', 'PIC Development berhasil ditambahkan!');
    }

    public function storePicPlan(Request $request)
    {
        // KOREKSI: Gunakan hasil validasi dan tambahkan blok AJAX
        $validated = $request->validate(['name' => 'required|string|max:255|unique:pic_plans']);
        $picPlan = PicPlan::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'PIC Planning berhasil ditambahkan!', 'data' => $picPlan]);
        }
        return redirect()->route('admin.master.create')->with('sukses', 'PIC Planning berhasil ditambahkan!');
    }

    // --- METHODS UNTUK DESTROY DATA (DENGAN KOREKSI) ---

    public function destroyJenisSurat(Request $request, $id)
    {
        JenisSurat::findOrFail($id)->delete();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jenis Surat berhasil dihapus.']);
        }
        return redirect()->route('admin.master.manage')->with('sukses', 'Jenis Surat berhasil dihapus.');
    }

    public function destroyOwner(Request $request, $id)
    {
        // KOREKSI: Tambahkan Request $request dan blok AJAX
        Owner::findOrFail($id)->delete();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Owner berhasil dihapus.']);
        }
        return redirect()->route('admin.master.manage')->with('sukses', 'Owner berhasil dihapus.');
    }

    public function destroyJenisProyek(Request $request, $id)
    {
        // KOREKSI: Tambahkan Request $request dan blok AJAX
        JenisProyek::findOrFail($id)->delete();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jenis Proyek berhasil dihapus.']);
        }
        return redirect()->route('admin.master.manage')->with('sukses', 'Jenis Proyek berhasil dihapus.');
    }

    public function destroyPicDev(Request $request, $id)
    {
        // KOREKSI: Tambahkan Request $request dan blok AJAX
        PicDev::findOrFail($id)->delete();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'PIC Development berhasil dihapus.']);
        }
        return redirect()->route('admin.master.manage')->with('sukses', 'PIC Development berhasil dihapus.');
    }

    public function destroyPicPlan(Request $request, $id)
    {
        // KOREKSI: Tambahkan Request $request dan blok AJAX
        PicPlan::findOrFail($id)->delete();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'PIC Planning berhasil dihapus.']);
        }
        return redirect()->route('admin.master.manage')->with('sukses', 'PIC Planning berhasil dihapus.');
    }
}