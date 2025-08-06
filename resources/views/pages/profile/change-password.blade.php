@extends('layout.home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ubah Kata Sandi</div>

                <div class="card-body">
                    {{-- Menampilkan pesan sukses --}}
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('change-password.update') }}">
                        @csrf 

                        {{-- Input Kata Sandi Saat Ini --}}
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                            <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Input Kata Sandi Baru --}}
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Kata Sandi Baru</label>
                            <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required>
                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Input Konfirmasi Kata Sandi Baru --}}
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <input id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('profile') }}" class="btn btn-secondary" style="margin-right: 16px;">Batal</a>
                            <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection