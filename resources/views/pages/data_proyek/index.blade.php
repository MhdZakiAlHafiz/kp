@extends('layout.home')

@push('styles')
    <style>
        /* Style untuk memastikan tombol aksi tidak memakan banyak tempat */
        .table-actions {
            width: 150px;
        }
    </style>
@endpush

@section('content')
    @php
        /**
         * Fungsi helper untuk memformat kolom yang seharusnya array.
         * Ini akan menangani data yang sudah menjadi array, data JSON yang valid,
         * dan data dengan format JSON yang salah seperti '["a"]["b"]'.
         */
        $formatJsonArray = function ($value) {
            if (is_array($value)) {
                return implode(', ', $value);
            }
            if (is_string($value)) {
                // Coba bersihkan format seperti '["a"]["b"]' menjadi '["a","b"]'
                $cleanedJson = str_replace('][', ',', $value);
                $decoded = json_decode($cleanedJson, true);
                // Jika berhasil di-decode, gabungkan. Jika tidak, kembalikan nilai asli.
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return implode(', ', $decoded);
                }
            }
            return $value; // Fallback jika format tidak dikenali
        };
    @endphp

    <div class="container-fluid pt-4">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Dokumen Proyek</h1>

            <a href="{{ url('/data_proyek/create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i>
                <span class="d-none d-sm-inline-block ms-1">Tambah Dokumen</span>
            </a>
        </div>

        <!-- Card Utama untuk Tabel Data -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Proyek</h6>
                {{-- Form Pencarian --}}
                <div class="w-50">
                    <form action="{{ route('data_proyek.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search"
                                placeholder="Cari berdasarkan No. CR, Owner, atau Jenis..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                @if(request('search'))
                                    <a href="{{ route('data_proyek.index') }}" class="btn btn-outline-secondary">&times;</a>
                                @endif
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nomor CR</th>
                                <th>Jenis Surat</th>
                                <th>Owner</th>
                                <th>Jenis</th>
                                <th>Target</th>
                                <th>Target Disepakati</th>
                                <th>Target Kesepakatan</th>
                                <th>Detail Pengembangan</th>
                                <th>PIC Plan</th>
                                <th>PIC Dev</th>
                                <th>Keterangan</th>
                                <th>No. Catatan Permintaan</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th class="text-center table-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_proyeks as $index => $item)
                                <tr>
                                    <td>{{ $data_proyeks->firstItem() + $index }}</td>
                                    <td>
                                        <a href="{{ route('data_proyek.kegiatan_detail', $item->id) }}"
                                            title="Lihat Detail Kegiatan">
                                            <strong>{{ $item->nomor_cr }}</strong>
                                        </a>
                                    </td>
                                    {{-- DIPERBAIKI: Menggunakan helper function --}}
                                    <td>{{ $formatJsonArray($item->jenis_surat) }}</td>
                                    <td>{{ $formatJsonArray($item->owner) }}</td>
                                    <td>{{ $formatJsonArray($item->jenis) }}</td>

                                    <td>{{ \Carbon\Carbon::parse($item->target)->translatedFormat('F Y') }}</td>
                                    <td>{{ $item->target_disepakati ? \Carbon\Carbon::parse($item->target_disepakati)->translatedFormat('F Y') : '-' }}
                                    </td>
                                    <td>{{ $item->target_kesepakatan ? \Carbon\Carbon::parse($item->target_kesepakatan)->translatedFormat('F Y') : '-' }}
                                    </td>
                                    <td>{{ Str::limit($item->detail_pengembangan, 30) }}</td>

                                    {{-- DIPERBAIKI: Menggunakan helper function --}}
                                    <td>{{ $formatJsonArray($item->pic_perencana) }}</td>
                                    <td>{{ $formatJsonArray($item->pic_pelaksana) }}</td>

                                    <td>{{ Str::limit($item->keterangan, 30) }}</td>
                                    <td>{{ $item->nomor_catatan_permintaan }}</td>
                                    <td style="min-width: 150px;">
                                        <div class="font-weight-bold text-dark">{{ number_format($item->progres, 2) }}%</div>
                                        <div class="progress" style="height: 15px;">
                                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                                style="width: {{ $item->progres }}%;"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $item->status }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('data_proyek.pdf', $item->id) }}" class="btn btn-sm btn-secondary"
                                                data-toggle="tooltip" title="Cetak Dokumen PDF" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a href="{{ url('/data_proyek/' . $item->id) }}" class="btn btn-sm btn-warning"
                                                data-toggle="tooltip" title="Edit Dokumen">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ url('/data_proyek/' . $item->id) }}" method="POST"
                                                class="d-inline delete-form" data-cr-number="{{ $item->nomor_cr }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                    title="Hapus Dokumen">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center py-4">Tidak ada data ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                {{ $data_proyeks->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.sweetalert')
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();

            $('.delete-form').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var crNumber = form.data('cr-number');

                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Dokumen '" + crNumber + "' akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.get(0).submit();
                    }
                });
            });
        });
    </script>
@endpush