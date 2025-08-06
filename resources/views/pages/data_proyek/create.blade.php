@extends('layout.home')

@section('content')
    <div class="container-fluid pt-4">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Dokumen Baru</h1>
            <a href="{{ url('/data_proyek') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="row">
            <div class="col">
                <form action="/data_proyek" method="POST">
                    @csrf
                    <div class="card shadow-sm">
                        <div class="card-body">

                            <div class="form-group mb-2">
                                <label>Nomor CR <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_cr" id="nomor_cr" class="form-control" readonly required>
                            </div>

                            {{-- Dropdown Jenis Surat (Multi-select) --}}
                            <div class="form-group mb-2">
                                <label>Jenis Surat <span class="text-danger">*</span></label>
                                <select name="jenis_surat[]" class="form-control select2" multiple="multiple" required>
                                    @foreach($jenis_surats as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Dropdown Owner (Multi-select) --}}
                            <div class="form-group mb-2">
                                <label>Owner/Pemilik <span class="text-danger">*</span></label>
                                <select name="owner[]" class="form-control select2" multiple="multiple" required>
                                    @foreach($owners as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Dropdown Jenis Proyek (Multi-select) --}}
                            <div class="form-group mb-2">
                                <label>Jenis <span class="text-danger">*</span></label>
                                <select name="jenis[]" class="form-control select2" multiple="multiple" required>
                                    @foreach($jenis_proyeks as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Form input lain (tidak berubah) --}}
                            <div class="form-group mb-2">
                                <label>Target <span class="text-danger">*</span></label>
                                <input type="month" name="target" class="form-control" value="{{ old('target') }}" required>
                            </div>
                            <div class="form-group mb-2">
                                <label>Target Disepakati <span class="text-danger">*</span></label>
                                <input type="month" name="target_disepakati" class="form-control"
                                    value="{{ old('target_disepakati') }}" required>
                            </div>
                            <div class="form-group mb-2">
                                <label>Target Kesepakatan <span class="text-danger">*</span></label>
                                <input type="month" name="target_kesepakatan" class="form-control"
                                    value="{{ old('target_kesepakatan') }}" required>
                            </div>
                            <div class="form-group mb-2">
                                <label>Detail Pengembangan <span class="text-danger">*</span></label>
                                <textarea name="detail_pengembangan" class="form-control" rows="3"
                                    required>{{ old('detail_pengembangan') }}</textarea>
                            </div>

                            {{-- PIC Plan (Multi-select) --}}
                            <div class="form-group mb-2">
                                <label>PIC Plan<span class="text-danger">*</span></label>
                                <select name="pic_perencana[]" class="form-control select2" multiple="multiple">
                                    @foreach($pic_plan as $pic)
                                        <option value="{{ $pic->name }}">{{ $pic->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- PIC Dev (Multi-select) --}}
                            <div class="form-group mb-2">
                                <label>PIC Dev<span class="text-danger">*</span></label>
                                <select name="pic_pelaksana[]" class="form-control select2" multiple="multiple">
                                    @foreach($pic_dev as $pic)
                                        <option value="{{ $pic->name }}">{{ $pic->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Sisa form (tidak berubah) --}}
                            <div class="form-group mb-2">
                                <label>Keterangan<span class="text-danger">*</span></label>
                                <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label>Progress (%)<span class="text-danger">*</span></label>
                                <input type="number" name="progres" class="form-control" step="0.01" max="100" min="0"
                                    value="0" readonly required>
                            </div>
                            <div class="form-group mb-2">
                                <label>Status<span class="text-danger">*</span></label>
                                <select class="form-control" disabled>
                                    <option value="Not Started" selected>Not Started</option>
                                </select>
                                <input type="hidden" name="status" value="Not Started">
                            </div>
                            <div class="form-group mb-3">
                                <label>Nomor Catatan Permintaan<span class="text-danger">*</span></label>
                                <input type="text" name="nomor_catatan_permintaan" class="form-control"
                                    value="{{ old('nomor_catatan_permintaan') }}">
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <a href="/data_proyek" class="btn btn-secondary mr-2">Batal</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save fa-sm"></i> Simpan Dokumen
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: "Pilih satu atau lebih",
                allowClear: true
            });

            // Script untuk generate nomor CR
            const selectJenisSurat = $('select[name="jenis_surat[]"]');
            if (selectJenisSurat.length) {
                selectJenisSurat.on('change', function () {
                    const jenis = $(this).val();
                    const jenisPertama = Array.isArray(jenis) ? jenis[0] : jenis;

                    if (!jenisPertama) {
                        document.getElementById('nomor_cr').value = '';
                        return;
                    };

                    fetch(`/generate-nomor-cr/${jenisPertama}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('nomor_cr').value = data.nomor_cr;
                        })
                        .catch(error => console.error('Error:', error));
                });
            }
        });
    </script>

    @include('components.sweetalert')
@endpush