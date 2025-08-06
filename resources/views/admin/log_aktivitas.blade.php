@extends('layout.home')

@section('content')
    <div class="container-fluid pt-4">
        <h1 class="h3 mb-4 text-gray-800">Log Aktivitas Sistem</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Deskripsi</th>
                                <th>Subjek</th>
                                <th>Pelaku</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activities as $activity)
                                <tr>
                                    <td>{{ $activity->description }}</td>
                                    <td>
                                        @if ($activity->subject)
                                            {{-- Tampilkan nama subjek jika ada (misal: nama proyek atau nama user) --}}
                                            {{ $activity->subject->name ?? ($activity->subject->nomor_cr ?? 'Data') }}
                                            <span class="text-muted d-block small">({{ $activity->log_name }})</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Tampilkan nama pelaku jika ada, jika tidak, tampilkan 'Sistem' --}}
                                        {{ $activity->causer->name ?? 'Sistem' }}
                                    </td>
                                    <td>
                                        {{-- Tampilkan waktu dalam format yang mudah dibaca --}}
                                        {{ $activity->created_at->translatedFormat('d F Y, H:i') }}
                                        <span
                                            class="text-muted d-block small">({{ $activity->created_at->diffForHumans() }})</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada aktivitas yang tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{-- Tampilkan link paginasi --}}
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection