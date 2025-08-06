@extends('layout.home')

@section('content')
    <div class="container-fluid pt-4">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ubah Dokumen | No. {{ $data_proyek->nomor_cr }}</h1>
            <a href="{{ url('/data_proyek') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="row">
            <div class="col">
                <form action="/data_proyek/{{ $data_proyek->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card shadow-sm">
                        <div class="card-body">

                            <div class="form-group mb-2">
                                <label>Nomor CR <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_cr" id="nomor_cr" class="form-control"
                                    value="{{ $data_proyek->nomor_cr }}" readonly required>
                            </div>

                            {{-- Dropdown Jenis Surat (Multi-select) --}}
                            <div class="form-group mb-2">
                                <label>Jenis Surat <span class="text-danger">*</span></label>
                                @php $selectedJenisSurat = is_array($data_proyek->jenis_surat) ? $data_proyek->jenis_surat : (json_decode($data_proyek->jenis_surat, true) ?? []); @endphp
                                <select name="jenis_surat[]" class="form-control select2" multiple="multiple" required>
                                    @foreach($jenis_surats as $item)
                                        <option value="{{ $item->name }}" @selected(in_array($item->name, $selectedJenisSurat))>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Dropdown Owner (Multi-select) --}}
                            <div class="form-group mb-2">
                                <label>Owner/Pemilik <span class="text-danger">*</span></label>
                                {{-- DIPERBAIKI: Menambahkan pengecekan is_array untuk keamanan --}}
                                @php $selectedOwners = is_array($data_proyek->owner) ? $data_proyek->owner : (json_decode($data_proyek->owner, true) ?? []); @endphp
                                <select name="owner[]" class="form-control select2" multiple="multiple" required>
                                    @foreach($owners as $item)
                                        <option value="{{ $item->name }}" @selected(in_array($item->name, $selectedOwners))>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Dropdown Jenis Proyek (Multi-select) --}}
                            <div class="form-group mb-2">
                                <label>Jenis <span class="text-danger">*</span></label>
                                @php $selectedJenis = is_array($data_proyek->jenis) ? $data_proyek->jenis : (json_decode($data_proyek->jenis, true) ?? []); @endphp
                                <select name="jenis[]" class="form-control select2" multiple="multiple" required>
                                    @foreach($jenis_proyeks as $item)
                                        <option value="{{ $item->name }}" @selected(in_array($item->name, $selectedJenis))>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Form input lain (tidak berubah) --}}
                            <div class="form-group mb-2">
                                <label>Target <span class="text-danger">*</span></label>
                                <input type="month" name="target" class="form-control"
                                    value="{{ old('target', $data_proyek->target) }}" required>
                            </div>
                            <div class="form-group mb-2">
                                <label>Target Disepakati <span class="text-danger">*</span></label>
                                <input type="month" name="target_disepakati" class="form-control"
                                    value="{{ old('target_disepakati', $data_proyek->target_disepakati) }}" required>
                            </div>
                            <div class="form-group mb-2">
                                <label>Target Kesepakatan <span class="text-danger">*</span></label>
                                <input type="month" name="target_kesepakatan" class="form-control"
                                    value="{{ old('target_kesepakatan', $data_proyek->target_kesepakatan) }}" required>
                            </div>
                            <div class="form-group mb-2">
                                <label>Detail Pengembangan <span class="text-danger">*</span></label>
                                <textarea name="detail_pengembangan" class="form-control" rows="3"
                                    required>{{ old('detail_pengembangan', $data_proyek->detail_pengembangan) }}</textarea>
                            </div>

                            {{-- PIC Plan (Multi-select) --}}
                            <div class="form-group mb-2">
                                <label>PIC Plan<span class="text-danger">*</span></label>
                                {{-- DIPERBAIKI: Menambahkan pengecekan is_array untuk keamanan --}}
                                @php $selectedPicPlan = is_array($data_proyek->pic_perencana) ? $data_proyek->pic_perencana : (json_decode($data_proyek->pic_perencana, true) ?? []); @endphp
                                <select name="pic_perencana[]" class="form-control select2" multiple="multiple">
                                    @foreach($pic_plan as $pic)
                                        <option value="{{ $pic->name }}" @selected(in_array($pic->name, $selectedPicPlan))>
                                            {{ $pic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- PIC Dev (Multi-select) --}}
                            <div class="form-group mb-2">
                                <label>PIC Dev<span class="text-danger">*</span></label>
                                {{-- DIPERBAIKI: Menambahkan pengecekan is_array untuk keamanan --}}
                                @php $selectedPicDev = is_array($data_proyek->pic_pelaksana) ? $data_proyek->pic_pelaksana : (json_decode($data_proyek->pic_pelaksana, true) ?? []); @endphp
                                <select name="pic_pelaksana[]" class="form-control select2" multiple="multiple">
                                    @foreach($pic_dev as $pic)
                                        <option value="{{ $pic->name }}" @selected(in_array($pic->name, $selectedPicDev))>
                                            {{ $pic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Sisa form (tidak berubah) --}}
                            <div class="form-group mb-2">
                                <label>Keterangan<span class="text-danger">*</span></label>
                                <textarea name="keterangan" class="form-control"
                                    rows="2">{{ old('keterangan', $data_proyek->keterangan) }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Progress (%)<span class="text-danger">*</span></label>
                                <input type="number" name="progres" class="form-control" step="0.01" max="100" min="0"
                                    value="{{ old('progres', $data_proyek->progres) }}" readonly required>
                            </div>
                            <div class="form-group mb-2">
                                <label>Status<span class="text-danger">*</span></label>
                                <select class="form-control" disabled>
                                    <option value="{{ $data_proyek->status }}" selected>{{ $data_proyek->status }}</option>
                                </select>
                                <input type="hidden" name="status" value="{{ old('status', $data_proyek->status) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label>Nomor Catatan Permintaan<span class="text-danger">*</span></label>
                                <input type="text" name="nomor_catatan_permintaan" class="form-control"
                                    value="{{ old('nomor_catatan_permintaan', $data_proyek->nomor_catatan_permintaan) }}">
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <a href="/data_proyek" class="btn btn-secondary mr-2">Batal</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save fa-sm"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Script untuk menginisialisasi Select2 dan Generate Nomor CR --}}
    <script>
        $(document).ready(function () {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: "Pilih satu atau lebih",
                allowClear: true
            });

            // --- PERUBAHAN LOGIKA GENERATE NOMOR CR ---

            // 1. Simpan nilai asli saat halaman dimuat
            const originalNomorCr = $('#nomor_cr').val();
            const originalJenisSurat = {!! json_encode(is_array($data_proyek->jenis_surat) ? $data_proyek->jenis_surat : json_decode($data_proyek->jenis_surat, true) ?? []) !!};
            const originalJenisSuratPertama = Array.isArray(originalJenisSurat) ? originalJenisSurat[0] : null;

            const selectJenisSurat = $('select[name="jenis_surat[]"]');

            if (selectJenisSurat.length) {
                selectJenisSurat.on('change', function () {
                    const jenisTerpilih = $(this).val();
                    const jenisPertama = Array.isArray(jenisTerpilih) ? jenisTerpilih[0] : jenisTerpilih;

                    // Jika pilihan dikosongkan, kembalikan ke nomor asli
                    if (!jenisPertama) {
                        $('#nomor_cr').val(originalNomorCr);
                        return;
                    }

                    // 2. Cek apakah pilihan kembali ke jenis surat asli
                    if (jenisPertama === originalJenisSuratPertama) {
                        // 3. Jika ya, kembalikan nomor CR asli
                        $('#nomor_cr').val(originalNomorCr);
                    } else {
                        // 4. Jika tidak, ambil nomor baru dari server
                        fetch(`/generate-nomor-cr/${jenisPertama}`)
                            .then(response => response.json())
                            .then(data => {
                                $('#nomor_cr').val(data.nomor_cr);
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            }
        });
    </script>

    @include('components.sweetalert')
@endpush