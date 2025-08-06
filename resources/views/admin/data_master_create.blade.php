@extends('layout.home')

@section('content')
    <div class="container-fluid pt-4">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Master Data</h1>
            <a href="{{ route('admin.master.manage') }}" class="btn btn-outline-primary">
                <i class="fas fa-cogs fa-sm"></i> Kelola Master Data
            </a>
        </div>

        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-lg-6">
                <!-- Card Tambah Jenis Surat -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-file-alt fa-fw mr-2"></i>Tambah Jenis
                            Surat</h6>
                    </div>
                    <div class="card-body">
                        {{-- KOREKSI: Tambahkan class="master-form" --}}
                        <form action="{{ route('admin.master.jenis_surat.store') }}" method="POST" class="master-form">
                            @csrf
                            <div class="form-group">
                                <label for="name_jenis_surat">Nama Jenis Surat</label>
                                <input type="text" name="name" id="name_jenis_surat" class="form-control"
                                    placeholder="Contoh: CR" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Simpan</button>
                        </form>
                    </div>
                </div>

                <!-- Card Tambah Jenis Proyek -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i
                                class="fas fa-project-diagram fa-fw mr-2"></i>Tambah Jenis Proyek</h6>
                    </div>
                    <div class="card-body">
                        {{-- KOREKSI: Tambahkan class="master-form" --}}
                        <form action="{{ route('admin.master.jenis_proyek.store') }}" method="POST" class="master-form">
                            @csrf
                            <div class="form-group">
                                <label for="name_jenis_proyek">Nama Jenis Proyek</label>
                                <input type="text" name="name" id="name_jenis_proyek" class="form-control"
                                    placeholder="Contoh: PKLD" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Simpan</button>
                        </form>
                    </div>
                </div>

                <!-- Card Tambah PIC Planning -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-tie fa-fw mr-2"></i>Tambah PIC
                            Planning</h6>
                    </div>
                    <div class="card-body">
                        {{-- KOREKSI: Tambahkan class="master-form" --}}
                        <form action="{{ route('admin.master.pic_plan.store') }}" method="POST" class="master-form">
                            @csrf
                            <div class="form-group">
                                <label for="name_pic_plan">Nama PIC Planning</label>
                                <input type="text" name="name" id="name_pic_plan" class="form-control"
                                    placeholder="Contoh: Budi" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-lg-6">
                <!-- Card Tambah Owner -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-sitemap fa-fw mr-2"></i>Tambah
                            Owner/Pemilik</h6>
                    </div>
                    <div class="card-body">
                        {{-- KOREKSI: Tambahkan class="master-form" --}}
                        <form action="{{ route('admin.master.owner.store') }}" method="POST" class="master-form">
                            @csrf
                            <div class="form-group">
                                <label for="name_owner">Nama Owner</label>
                                <input type="text" name="name" id="name_owner" class="form-control"
                                    placeholder="Contoh: Divisi TSI" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Simpan</button>
                        </form>
                    </div>
                </div>

                <!-- Card Tambah PIC Development -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-cog fa-fw mr-2"></i>Tambah PIC
                            Development</h6>
                    </div>
                    <div class="card-body">
                        {{-- KOREKSI: Tambahkan class="master-form" --}}
                        <form action="{{ route('admin.master.pic_dev.store') }}" method="POST" class="master-form">
                            @csrf
                            <div class="form-group">
                                <label for="name_pic_dev">Nama PIC Development</label>
                                <input type="text" name="name" id="name_pic_dev" class="form-control"
                                    placeholder="Contoh: Ani" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.sweetalert')
    {{-- KOREKSI: Tambahkan blok script AJAX --}}
    <script>
        $(document).ready(function () {
            // Setup header AJAX untuk mengirim CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Event listener untuk semua form dengan class 'master-form'
            $('.master-form').on('submit', function (e) {
                e.preventDefault(); // Mencegah form submit dan reload halaman

                var form = $(this);
                var url = form.attr('action');
                var data = form.serialize();
                var submitButton = form.find('button[type="submit"]');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    beforeSend: function () {
                        // Menonaktifkan tombol dan menampilkan spinner
                        submitButton.prop('disabled', true);
                        submitButton.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                    },
                    success: function (response) {
                        form.trigger('reset'); // Mengosongkan input form
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        // Menangani error validasi dari Laravel
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Terjadi kesalahan. ';
                        if (errors && errors.name) {
                            // Menampilkan pesan error spesifik dari validasi
                            errorMessage = errors.name[0];
                        }
                        Swal.fire('Gagal!', errorMessage, 'error');
                    },
                    complete: function () {
                        // Mengembalikan tombol ke keadaan semula
                        submitButton.prop('disabled', false);
                        submitButton.html('Simpan');
                    }
                });
            });
        });
    </script>
@endpush