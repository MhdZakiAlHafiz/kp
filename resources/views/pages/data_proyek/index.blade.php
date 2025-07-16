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
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                        
                        @if (count($data_proyeks)<1)
                        <tbody>
                            <tr>
                                <td colspan="14">
                                    <p class="pt-3 text-center">tidak ada data</p>
                                </td>
                            </tr>
                        </tbody>
                        @else
                        <tbody>
                            @foreach ($data_proyeks as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
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
                                <td>{{ $item->pic_perencana }}</td>
                                <td>{{ $item->pic_pelaksana }}</td>
                                <td>{{ $item->keterangan }}</td>
                                <td>{{ number_format($item->progres, 2) }}%</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->nomor_catatan_permintaan }}</td>
                                {{-- <td>
                                    <div class="d-flex">
                                        <a href="/data_proyek/{id}"class="d-inline-block mr-2 btn btn-sm btn-warning">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <a href="/data_proyek/{id}"class="btn btn-sm btn-danger">
                                            <i class="fas fa-eraser"></i>
                                        </a>
                                    </div>
                                </td> --}}
                                
                            </tr>
                            @endforeach
                        </tbody>
                        @endif
                        
                    </table>                    
                </div>
            </div>
        </div>
    </div>
@endsection
