@extends('layout.home')

@section('content')
    {{-- PERUBAHAN: Menambahkan wrapper 'container-fluid' dengan padding atas (pt-4)
    agar konten tidak menempel langsung pada navbar. --}}
    <div class="container-fluid pt-4">

        {{-- PERUBAHAN: Membuat baris header menggunakan Flexbox untuk mensejajarkan judul
        di kiri dan tombol aksi (opsional) di kanan, memberikan tampilan yang lebih rapi. --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Update Progres Dokumen | Nomor: {{ $data_proyek->nomor_cr }}</h4>
            {{-- Tombol "Kembali" opsional untuk navigasi yang lebih mudah --}}
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
        </div>

        <form method="POST" action="{{ route('data_proyek.kegiatan_detail.update', $data_proyek->id) }}">
            @csrf

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        {{-- PERBAIKAN: 'table-hover' sudah benar. 'w-100' memastikan lebar penuh. --}}
                        <table class="table table-bordered table-hover w-100" id="kegiatanDetailTable" cellspacing="0">
                            <thead>
                                <tr>
                                    {{-- PERUBAHAN: min-width untuk kolom dipertahankan, ini praktik yang bagus. --}}
                                    <th style="min-width: 50px;">No</th>
                                    <th style="min-width: 250px;">Kegiatan</th>
                                    <th style="min-width: 70px;">Bobot</th>
                                    <th style="min-width: 180px;">Progress (%)</th>
                                    <th style="min-width: 140px;">Plan Start</th>
                                    <th style="min-width: 140px;">Plan End</th>
                                    <th style="min-width: 140px;">Actual Start</th>
                                    <th style="min-width: 140px;">Actual End</th>
                                    <th style="min-width: 200px;">Keterangan</th>
                                    <th style="min-width: 150px;">PIC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kegiatan_detail as $item)
                                    <tr class="{{ $item['__is_sub'] ? 'table-secondary' : '' }}">
                                        <td>{{ $item['no'] }}</td>
                                        <td style="{{ $item['__is_sub'] ? 'padding-left: 30px;' : '' }}">
                                            {{ $item['kegiatan'] }}
                                            {{-- Hidden inputs penting untuk form, jadi dipertahankan. --}}
                                            <input type="hidden" name="kegiatan_detail[{{ $item['__flat_index'] }}][no]"
                                                value="{{ $item['no'] }}">
                                            <input type="hidden" name="kegiatan_detail[{{ $item['__flat_index'] }}][kegiatan]"
                                                value="{{ $item['kegiatan'] }}">
                                            <input type="hidden" name="kegiatan_detail[{{ $item['__flat_index'] }}][bobot]"
                                                value="{{ $item['bobot'] }}">
                                            <input type="hidden"
                                                name="kegiatan_detail[{{ $item['__flat_index'] }}][__original_path]"
                                                value="{{ $item['__original_path'] }}">
                                        </td>
                                        <td>{{ $item['bobot'] }}</td>
                                        <td>
                                            @if(!$item['__read_only_progress'])
                                                <input type="number" step="0.01" min="0" max="{{ $item['bobot'] }}"
                                                    name="kegiatan_detail[{{ $item['__flat_index'] }}][progress]"
                                                    value="{{ $item['progress'] }}" class="form-control form-control-sm mb-1">
                                            @endif
                                            <div class="progress" style="height: 15px;">
                                                {{-- PERUBAHAN: Logika untuk warna progress bar dinamis sudah bagus,
                                                dipertahankan. --}}
                                                @php
                                                    $progressPercentage = $item['bobot'] > 0 ? ($item['progress'] / $item['bobot'] * 100) : 0;
                                                    $progressBarClass = '';
                                                    if ($progressPercentage >= 100) {
                                                        $progressBarClass = 'bg-success'; // Hijau jika selesai
                                                    } elseif ($progressPercentage > 0) {
                                                        $progressBarClass = 'bg-info';   // Biru jika sedang berjalan
                                                    } else {
                                                        $progressBarClass = 'bg-light text-dark'; // Abu-abu jika belum mulai
                                                    }
                                                @endphp
                                                <div class="progress-bar progress-bar-striped {{ $progressBarClass }}"
                                                    role="progressbar" style="width: {{ $progressPercentage }}%;"
                                                    aria-valuenow="{{ $item['progress'] }}" aria-valuemin="0"
                                                    aria-valuemax="{{ $item['bobot'] }}">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ round($item['progress'], 2) }} /
                                                {{ $item['bobot'] }}</small>
                                        </td>
                                        <td>
                                            <input type="date" name="kegiatan_detail[{{ $item['__flat_index'] }}][plan_start]"
                                                value="{{ $item['plan_start'] ?? '' }}" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="date" name="kegiatan_detail[{{ $item['__flat_index'] }}][plan_end]"
                                                value="{{ $item['plan_end'] ?? '' }}" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="date" name="kegiatan_detail[{{ $item['__flat_index'] }}][actual_start]"
                                                value="{{ $item['actual_start'] ?? '' }}" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="date" name="kegiatan_detail[{{ $item['__flat_index'] }}][actual_end]"
                                                value="{{ $item['actual_end'] ?? '' }}" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="text" name="kegiatan_detail[{{ $item['__flat_index'] }}][keterangan]"
                                                value="{{ $item['keterangan'] ?? '' }}" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="text" name="kegiatan_detail[{{ $item['__flat_index'] }}][pic]"
                                                value="{{ $item['pic'] ?? '' }}" class="form-control form-control-sm" readonly
                                                required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- PERUBAHAN: Menggunakan 'text-end' (standar Bootstrap 5) untuk perataan kanan
                dan menambahkan spasi antar tombol untuk tampilan yang lebih bersih. --}}
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    @include('components.sweetalert')
@endpush