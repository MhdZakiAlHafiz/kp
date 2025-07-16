@extends('layout.home')
@section('content')
<h4>Form Kegiatan Detail - {{ $data_proyek->nomor_cr }}</h4>

<form method="POST" action="{{ route('data_proyek.kegiatan_detail.update', $data_proyek->id) }}">
    @csrf

    <table class="table">
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
            @foreach($kegiatan_detail as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><input type="text" name="kegiatan_detail[{{ $i }}][kegiatan]" value="{{ $item['kegiatan'] }}" class="form-control" readonly></td>
                <td><input type="number" name="kegiatan_detail[{{ $i }}][bobot]" value="{{ $item['bobot'] }}" class="form-control" readonly></td>
                <td><input type="number" step="0.01" name="kegiatan_detail[{{ $i }}][progress]" value="{{ $item['progress'] }}" class="form-control"></td>
                <td><input type="date" name="kegiatan_detail[{{ $i }}][plan_start]" value="{{ $item['plan_start'] ?? '' }}" class="form-control"></td>
                <td><input type="date" name="kegiatan_detail[{{ $i }}][plan_end]" value="{{ $item['plan_end'] ?? '' }}" class="form-control"></td>
                <td><input type="date" name="kegiatan_detail[{{ $i }}][actual_start]" value="{{ $item['actual_start'] ?? '' }}" class="form-control"></td>
                <td><input type="date" name="kegiatan_detail[{{ $i }}][actual_end]" value="{{ $item['actual_end'] ?? '' }}" class="form-control"></td>
                <td><input type="text" name="kegiatan_detail[{{ $i }}][keterangan]" value="{{ $item['keterangan'] ?? '' }}" class="form-control"></td>
                <td><input type="text" name="kegiatan_detail[{{ $i }}][pic]" value="{{ $item['pic'] ?? '' }}" class="form-control"></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
</form>
@endsection
