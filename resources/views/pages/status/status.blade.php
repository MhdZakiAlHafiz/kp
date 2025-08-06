@extends('layout.home')

@push('styles')
    {{-- CDN untuk SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-2 text-gray-800">Manajemen Akun Pengguna</h1>
        <p class="mb-4">Kelola persetujuan pengguna baru dan daftar akun yang sudah terdaftar.</p>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('status') ? 'active' : '' }}"
                            href="{{ route('status.index') }}">Persetujuan Akun</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('daftar-akun') ? 'active' : '' }}"
                            href="{{ route('daftar-akun') }}">Daftar Akun</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">

                    {{-- TAB 1: PERSETUJUAN AKUN (SEKARANG MENGGUNAKAN AJAX) --}}
                    @if(request()->is('status'))
                        <div class="table-responsive">
                            <p>Daftar pengguna baru yang menunggu persetujuan.</p>
                            <table class="table table-bordered" id="approvalTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        {{-- Beri ID unik pada <tr> agar bisa dihapus dari UI --}}
                                        <tr id="approval-row-{{ $user->id }}">
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td><span class="badge badge-warning">Menunggu</span></td>
                                            <td class="text-center">
                                                {{-- Tombol Setujui dengan data-attributes untuk AJAX --}}
                                                <button class="btn btn-success btn-sm process-approval-btn"
                                                    data-id="{{ $user->id }}" data-action="approve">
                                                    Setujui
                                                </button>
                                                {{-- Tombol Tolak dengan data-attributes untuk AJAX --}}
                                                <button class="btn btn-danger btn-sm process-approval-btn" data-id="{{ $user->id }}"
                                                    data-action="reject">
                                                    Tolak
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada pendaftar baru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif

                    {{-- TAB 2: DAFTAR AKUN (SUDAH MENGGUNAKAN AJAX) --}}
                    @if(request()->is('daftar-akun'))
                        <div class="table-responsive">
                            <p>Daftar semua akun yang telah diproses (Aktif/Tidak Aktif).</p>
                            <table class="table table-bordered" id="daftarAkunTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($processedUsers as $index => $user)
                                        <tr id="user-row-{{ $user->id }}">
                                            <td>{{ $processedUsers->firstItem() + $index }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span id="status-badge-{{ $user->id }}"
                                                    class="badge {{ $user->status == 'approved' ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $user->status == 'approved' ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button
                                                    class="btn btn-sm toggle-status-btn {{ $user->status == 'approved' ? 'btn-warning' : 'btn-success' }}"
                                                    data-id="{{ $user->id }}" id="toggle-btn-{{ $user->id }}">
                                                    {{ $user->status == 'approved' ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                                <form action="{{ route('akun.hapus', $user->id) }}" method="POST"
                                                    class="d-inline delete-form-akun" data-user-name="{{ $user->name }}">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada akun yang telah diproses.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $processedUsers->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Script Utama --}}
    <script>
        $(document).ready(function () {

            // === Setup CSRF Token untuk AJAX ===
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // === TAB: Persetujuan Akun ===
            $('#approvalTable').on('click', '.process-approval-btn', function (e) {
                e.preventDefault();

                const button = $(this);
                const userId = button.data('id');
                const action = button.data('action');
                const url = `/status/${userId}/${action}`;

                button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: url,
                    type: 'PATCH',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });

                            $('#approval-row-' + userId).fadeOut(500, function () {
                                $(this).remove();

                                // Cek jika tidak ada baris lagi
                                if ($('#approvalTable tbody tr').length === 0) {
                                    $('#approvalTable tbody').html('<tr><td colspan="4" class="text-center">Tidak ada pendaftar baru.</td></tr>');
                                }
                            });
                        } else {
                            Swal.fire('Gagal', response.message || 'Aksi gagal diproses.', 'error');
                        }
                    },
                    error: function (xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan pada server.';
                        Swal.fire('Error!', errorMsg, 'error');
                        button.prop('disabled', false).html(action === 'approve' ? 'Setujui' : 'Tolak');
                    }
                });
            });

            // === TAB: Konfirmasi Hapus Akun ===
            $('#daftarAkunTable').on('submit', '.delete-form-akun', function (e) {
                e.preventDefault();

                const form = $(this);
                const userName = form.data('user-name');

                Swal.fire({
                    title: 'Anda Yakin?',
                    text: `Akun '${userName}' akan dihapus secara permanen!`,
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

            // === TAB: Daftar Akun - Toggle Status ===
            $('#daftarAkunTable').on('click', '.toggle-status-btn', function (e) {
                e.preventDefault();

                const button = $(this);
                const userId = button.data('id');
                const url = `/akun/ubah-status/${userId}`;

                button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            const statusBadge = $('#status-badge-' + userId);
                            statusBadge.text(response.status_text);

                            if (response.new_status === 'approved') {
                                statusBadge.removeClass('badge-danger').addClass('badge-success');
                                button.removeClass('btn-success').addClass('btn-warning').text('Nonaktifkan');
                            } else {
                                statusBadge.removeClass('badge-success').addClass('badge-danger');
                                button.removeClass('btn-warning').addClass('btn-success').text('Aktifkan');
                            }

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } else {
                            Swal.fire('Gagal', response.message || 'Gagal mengubah status.', 'error');
                        }
                    },
                    error: function (xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan pada server.';
                        Swal.fire('Error!', errorMsg, 'error');
                    },
                    complete: function () {
                        button.prop('disabled', false);
                    }
                });
            });
        });
    </script>

    {{-- Script untuk Auto-Generate Nomor CR --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectJenisSurat = document.querySelector('select[name="jenis_surat"]');

            if (selectJenisSurat) {
                selectJenisSurat.addEventListener('change', function () {
                    const jenis = this.value;
                    if (!jenis) return;

                    fetch(`/generate-nomor-cr/${jenis}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            document.getElementById('nomor_cr').value = data.nomor_cr;
                        })
                        .catch(error => {
                            console.error('Gagal mengambil nomor CR:', error);
                        });
                });
            }
        });
    </script>

    {{-- SweetAlert Komponen --}}
    @include('components.sweetalert')
@endpush