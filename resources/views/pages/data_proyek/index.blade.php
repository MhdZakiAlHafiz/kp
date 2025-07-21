@extends('layout.home')

@section('content')
<!-- Page Heading -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Proyek</h1>
            <a href="/data_proyek/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
        class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
    </div>

    {{-- tabel --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-responsive table-bordered table-hovered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor CR</th>
                                <th>Owner</th>
                                <th>Jenis</th>
                                <th>Target</th>
                                <th>Target Disepakati</th>
                                <th>Target Kesepakatan</th>
                                <th>Detail Pengembangan</th>
                                <th>PIC Plan</th>
                                <th>PIC Dev</th>
                                <th>Keterangan</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Nomor Catatan Permintaan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        
                        @if (count($data_proyeks)<1)
                        <tbody>
                            <tr>
                                <td colspan="15">
                                    <p class="pt-3 text-center">tidak ada data</p>
                                </td>
                            </tr>
                        </tbody>
                        @else
                        <tbody>
                            @foreach ($data_proyeks as $item)
                            <tr>
                                {{-- Menggunakan $loop->iteration untuk nomor urut yang berurutan --}}
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('data_proyek.kegiatan_detail', $item->id) }}">
                                        {{ $item->nomor_cr }}
                                    </a>
                                </td>
                                <td>{{ $item->owner }}</td>
                                <td>{{ $item->jenis }}</td>
                                <td>{{ $item->target }}</td>
                                <td>{{ $item->target_disepakati }}</td>
                                <td>{{ $item->target_kesepakatan }}</td>
                                <td>{{ $item->detail_pengembangan }}</td>
                                <td>
                                    @php
                                        $picPlan = $item->pic_perencana;
                                        $decodedPicPlan = json_decode($picPlan, true);
                                        if (is_array($decodedPicPlan)) {
                                            echo implode(', ', $decodedPicPlan);
                                        } else {
                                            echo $picPlan; // Jika bukan JSON array, tampilkan apa adanya (asumsi sudah string koma)
                                        }
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $picDev = $item->pic_pelaksana;
                                        $decodedPicDev = json_decode($picDev, true);
                                        if (is_array($decodedPicDev)) {
                                            echo implode(', ', $decodedPicDev);
                                        } else {
                                            echo $picDev; // Jika bukan JSON array, tampilkan apa adanya (asumsi sudah string koma)
                                        }
                                    @endphp
                                </td>
                                <td>{{ $item->keterangan }}</td>
                                <td>{{ number_format($item->progres, 2) }}%</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->nomor_catatan_permintaan }}</td>
                                <td>
                                    <div class="d-flex">
                                        {{-- Edit Button --}}
                                        <a href="/data_proyek/{{ $item->id }}" class="d-inline-block mr-2 btn btn-sm btn-warning">
                                            <i class="fas fa-pen"></i>
                                        </a>                                        
                                        <!-- Tombol Delete -->
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}">
                                            <i class="fas fa-eraser"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('pages.data_proyek.delete')
                            @endforeach
                        </tbody>
                        @endif
                        
                    </table>                
                </div>
            </div>
        </div>
    </div>
@endsection
