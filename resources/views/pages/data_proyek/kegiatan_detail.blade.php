@extends('layout.home')
@section('content')
<h4>Form Kegiatan Detail - {{ $data_proyek->nomor_cr }}</h4>

<form method="POST" action="{{ route('data_proyek.kegiatan_detail.update', $data_proyek->id) }}">
    @csrf

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hovered" id="kegiatanDetailTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kegiatan</th>
                            <th>Bobot</th>
                            <th>Progress (%)</th>
                            <th>Plan Start</th>
                            <th>Plan End</th>
                            <th>Actual Start</th>
                            <th>Actual End</th>
                            <th>Keterangan</th>
                            <th>PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Iterasi melalui array kegiatan_detail yang sudah diratakan --}}
                        @foreach($kegiatan_detail as $item)
                        <tr class="{{ $item['__is_sub'] ? 'table-secondary' : '' }}"> {{-- Baris sub-kegiatan akan memiliki latar belakang berbeda --}}
                            <td>{{ $item['no'] }}</td>
                            <td style="{{ $item['__is_sub'] ? 'padding-left: 30px;' : '' }}"> {{-- Indentasi untuk sub-kegiatan --}}
                                {{ $item['kegiatan'] }}
                                {{-- Hidden inputs untuk data yang tidak diedit tapi perlu dikirim kembali --}}
                                <input type="hidden" name="kegiatan_detail[{{ $item['__flat_index'] }}][no]" value="{{ $item['no'] }}">
                                <input type="hidden" name="kegiatan_detail[{{ $item['__flat_index'] }}][kegiatan]" value="{{ $item['kegiatan'] }}">
                                <input type="hidden" name="kegiatan_detail[{{ $item['__flat_index'] }}][bobot]" value="{{ $item['bobot'] }}">
                                {{-- Hidden input untuk path asli, penting untuk rekonstruksi di controller --}}
                                <input type="hidden" name="kegiatan_detail[{{ $item['__flat_index'] }}][__original_path]" value="{{ $item['__original_path'] }}">
                            </td>
                            <td>{{ $item['bobot'] }}</td>
                            <td>
                                <input type="number" step="0.01" min="0" max="{{ $item['bobot'] }}" {{-- max dibatasi oleh bobot item ini --}}
                                    name="kegiatan_detail[{{ $item['__flat_index'] }}][progress]"
                                    value="{{ $item['progress'] }}"
                                    class="form-control form-control-sm"
                                    {{ $item['__read_only_progress'] ? 'readonly' : '' }}> {{-- Tambahkan atribut readonly --}}
                            </td>
                            <td>
                                <input type="date" name="kegiatan_detail[{{ $item['__flat_index'] }}][plan_start]" value="{{ $item['plan_start'] ?? '' }}" class="form-control form-control-sm">
                            </td>
                            <td>
                                <input type="date" name="kegiatan_detail[{{ $item['__flat_index'] }}][plan_end]" value="{{ $item['plan_end'] ?? '' }}" class="form-control form-control-sm">
                            </td>
                            <td>
                                <input type="date" name="kegiatan_detail[{{ $item['__flat_index'] }}][actual_start]" value="{{ $item['actual_start'] ?? '' }}" class="form-control form-control-sm">
                            </td>
                            <td>
                                <input type="date" name="kegiatan_detail[{{ $item['__flat_index'] }}][actual_end]" value="{{ $item['actual_end'] ?? '' }}" class="form-control form-control-sm">
                            </td>
                            <td>
                                <input type="text" name="kegiatan_detail[{{ $item['__flat_index'] }}][keterangan]" value="{{ $item['keterangan'] ?? '' }}" class="form-control form-control-sm">
                            </td>
                            <td>
                                <input type="text" name="kegiatan_detail[{{ $item['__flat_index'] }}][pic]" value="{{ $item['pic'] ?? '' }}" class="form-control form-control-sm">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
    </div>
</form>
@endsection
