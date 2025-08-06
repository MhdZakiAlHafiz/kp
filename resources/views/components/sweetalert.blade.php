<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Skenario 1: Notifikasi SUKSES (disamakan dengan script AJAX)
        @if (session('sukses'))
            Swal.fire({
                toast: true,                         // BARU: Mengaktifkan mode toast
                position: 'top-end',
                icon: 'success',
                title: '{{ session('sukses') }}',
                showConfirmButton: false,
                timer: 3000,                         // BARU: Durasi diubah menjadi 3 detik
                timerProgressBar: true               // BARU: Menambahkan baris progress durasi
            });

            // Skenario 2: Notifikasi GAGAL (disamakan dengan script AJAX)
        @elseif (session('gagal'))
            Swal.fire({
                // UBAH: 'width' dihapus agar kembali ke ukuran standar yang lebih besar
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('gagal') }}',
                confirmButtonColor: '#d33'
            });

            // Skenario 3: Notifikasi ERROR VALIDASI (disamakan dengan script AJAX)
        @elseif ($errors->any())
            Swal.fire({
                // UBAH: 'width' dihapus agar kembali ke ukuran standar yang lebih besar
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: `
                            <ul style="text-align: left; list-style-position: inside; padding-left: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        `,
                confirmButtonColor: '#d33'
            });
        @endif
    });
</script>