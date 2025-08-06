@extends('layout.home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profil Pengguna</div>

                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bergabung Pada</label>
                        <input type="text" class="form-control" value="{{ $user->created_at->format('d F Y') }}" readonly>
                    </div>

                    <a href="{{ route('change-password') }}" class="btn btn-primary">Ubah Kata Sandi</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection