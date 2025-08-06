@extends('layout.home')

@section('content')
    <div class="container-fluid pt-4">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kelola Master Data</h1>
            <a href="{{ route('admin.master.create') }}" class="btn btn-primary">
                <i class="fas fa-plus fa-sm"></i> Tambah Master Data
            </a>
        </div>

        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-lg-6">
                <!-- Card Kelola Jenis Surat -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Jenis Surat</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                @forelse ($jenis_surats as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-right">
                                            <form class="d-inline delete-form"
                                                action="{{ route('admin.master.jenis_surat.destroy', $item->id) }}"
                                                method="POST" data-name="{{ $item->name }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">Data kosong.</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card Kelola Jenis Proyek -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Jenis Proyek</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                @forelse ($jenis_proyeks as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-right">
                                            <form class="d-inline delete-form"
                                                action="{{ route('admin.master.jenis_proyek.destroy', $item->id) }}"
                                                method="POST" data-name="{{ $item->name }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">Data kosong.</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card Kelola PIC Planning -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">PIC Planning</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                @forelse ($pic_plans as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-right">
                                            <form class="d-inline delete-form"
                                                action="{{ route('admin.master.pic_plan.destroy', $item->id) }}" method="POST"
                                                data-name="{{ $item->name }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">Data kosong.</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-lg-6">
                <!-- Card Kelola Owner -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Owner/Pemilik</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                @forelse ($owners as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-right">
                                            <form class="d-inline delete-form"
                                                action="{{ route('admin.master.owner.destroy', $item->id) }}" method="POST"
                                                data-name="{{ $item->name }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">Data kosong.</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card Kelola PIC Development -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">PIC Development</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                @forelse ($pic_devs as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-right">
                                            <form class="d-inline delete-form"
                                                action="{{ route('admin.master.pic_dev.destroy', $item->id) }}" method="POST"
                                                data-name="{{ $item->name }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">Data kosong.</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.sweetalert')
    <script>
        $(document).ready(function () {
            $('.delete-form').on('submit', function (e) {
                e.preventDefault(); // Mencegah form submit biasa
                var form = $(this);
                var url = form.attr('action');
                var crNumber = form.data('cr-number') || form.data('name'); // Ambil nama data

                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data '" + crNumber + "' akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST", // Method form adalah POST (disamarkan sebagai DELETE)
                            url: url,
                            data: form.serialize(), // Kirim data form (termasuk _method: 'DELETE' dan _token)
                            success: function (response) {
                                Swal.fire('Dihapus!', response.message, 'success');
                                // Hapus baris tabel dari tampilan dengan animasi fade out
                                form.closest('tr').fadeOut(500, function () {
                                    $(this).remove();
                                });
                            },
                            error: function (xhr) {
                                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush