@extends('layout.home')

@section('content')
<!-- Page Heading -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ubah Data Proyek</h1>
    </div>

    <div class="row">
    <div class="col">
        <form action="/data_proyek/{{ $data_proyek->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                {{-- <div class="card-header">
                    <h5>Form Input Data Proyek</h5>
                </div> --}}

                <div class="card-body">

                    <div class="form-group mb-2">
                        <label>Nomor CR <span class="text-danger">*</span></label>
                        {{-- Nilai nomor_cr selalu diambil dari data_proyek karena readonly --}}
                        <input type="text" name="nomor_cr" id="nomor_cr" class="form-control" value="{{ $data_proyek->nomor_cr }}" readonly required>
                    </div>

                    {{-- Script untuk generate Nomor CR ini sebenarnya lebih relevan di halaman 'create'.
                        Untuk halaman 'edit', Nomor CR seharusnya sudah fixed.
                        Namun, jika Anda ingin tetap ada fungsionalitas ini, pastikan logic di controller mendukung update nomor CR.
                        Saat ini, input nomor_cr adalah readonly, jadi script ini tidak akan berpengaruh.
                    --}}
                    {{-- <script>
                        document.querySelector('select[name="jenis_surat"]').addEventListener('change', function () {
                            const jenis = this.value;
                            if (!jenis) return;

                            fetch(`/generate-nomor-cr/${jenis}`)
                                .then(response => response.json())
                                .then(data => {
                                    document.getElementById('nomor_cr').value = data.nomor_cr;
                                });
                        });
                    </script> --}}


                    <div class="form-group mb-2">
                        <label>Jenis Surat <span class="text-danger">*</span></label>
                        <select name="jenis_surat" class="form-control" required>
                            <option value="">-- Pilih Jenis Surat --</option>
                            @php
                                $jenisSuratOptions = ['BRD', 'CR'];
                            @endphp
                            @foreach($jenisSuratOptions as $option)
                                <option value="{{ $option }}" @selected(old('jenis_surat', $data_proyek->jenis_surat) == $option)>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label>Owner/Pemilik <span class="text-danger">*</span></label>
                        <select name="owner" class="form-control" required>
                            <option value="">-- Pilih Divisi --</option>
                            @php
                                $ownerOptions = ['Divisi TSI', 'Divisi OPS', 'Divisi MDM'];
                            @endphp
                            @foreach($ownerOptions as $option)
                                <option value="{{ $option }}" @selected(old('owner', $data_proyek->owner) == $option)>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label>Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            @php
                                $jenisOptions = ['PKLD', 'TAMBAHAN'];
                            @endphp
                            @foreach($jenisOptions as $option)
                                <option value="{{ $option }}" @selected(old('jenis', $data_proyek->jenis) == $option)>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label>Target <span class="text-danger">*</span></label>
                        <input type="month" name="target" class="form-control" value="{{ old('target', $data_proyek->target) }}" required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Target Disepakati <span class="text-danger">*</span></label>
                        <input type="month" name="target_disepakati" class="form-control" value="{{ old('target_disepakati', $data_proyek->target_disepakati) }}" required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Target Kesepakatan <span class="text-danger">*</span></label>
                        <input type="month" name="target_kesepakatan" class="form-control" value="{{ old('target_kesepakatan', $data_proyek->target_kesepakatan) }}" required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Detail Pengembangan <span class="text-danger">*</span></label>
                        <textarea name="detail_pengembangan" class="form-control" rows="3" required>{{ old('detail_pengembangan', $data_proyek->detail_pengembangan) }}</textarea>
                    </div>

                    {{-- PIC Perencana Checkbox Group --}}
                    <div class="form-group mb-2">
                        <label>PIC Plan<span class="text-danger">*</span></label>
                        <div>
                            @php
                                $picPerencanaOptions = [
                                    'Ronaldy', 'Lutfi', 'Wildan', 'Ori', 'Bima',
                                    'Koiri', 'Ardi', 'Nanda', 'Fikri', 'Zahra',
                                    'Rizky', 'Dinda', 'Andi', 'Tari', 'Fajar',
                                    'Mega', 'Putra', 'Salma', 'Iqbal', 'Novi'
                                ];
                                // Logic untuk membaca PIC dari database: coba decode JSON, jika gagal, explode string
                                $dbPicPerencana = $data_proyek->pic_perencana;
                                $tempPicPerencanaArray = [];
                                if ($dbPicPerencana) {
                                    $decoded = json_decode($dbPicPerencana, true);
                                    if (is_array($decoded)) {
                                        $tempPicPerencanaArray = $decoded;
                                    } else {
                                        $tempPicPerencanaArray = explode(', ', $dbPicPerencana);
                                    }
                                }
                                $currentPicPerencana = old('pic_perencana', $tempPicPerencanaArray);
                            @endphp
                            @foreach($picPerencanaOptions as $pic)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="pic_perencana_{{ Str::slug($pic) }}" name="pic_perencana[]" value="{{ $pic }}"
                                        @checked(in_array($pic, $currentPicPerencana))>
                                    <label class="form-check-label" for="pic_perencana_{{ Str::slug($pic) }}">{{ $pic }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- PIC Pelaksana Checkbox Group --}}
                    <div class="form-group mb-2">
                        <label>PIC Dev<span class="text-danger">*</span></label>
                        <div>
                            @php
                                $picPelaksanaOptions = [
                                    'Ronaldy', 'Lutfi', 'Wildan', 'Ori', 'Bima',
                                    'Koiri', 'Ardi', 'Nanda', 'Fikri', 'Zahra',
                                    'Rizky', 'Dinda', 'Andi', 'Tari', 'Fajar',
                                    'Mega', 'Putra', 'Salma', 'Iqbal', 'Novi'
                                ];
                                // Logic untuk membaca PIC dari database: coba decode JSON, jika gagal, explode string
                                $dbPicPelaksana = $data_proyek->pic_pelaksana;
                                $tempPicPelaksanaArray = [];
                                if ($dbPicPelaksana) {
                                    $decoded = json_decode($dbPicPelaksana, true);
                                    if (is_array($decoded)) {
                                        $tempPicPelaksanaArray = $decoded;
                                    } else {
                                        $tempPicPelaksanaArray = explode(', ', $dbPicPelaksana);
                                    }
                                }
                                $currentPicPelaksana = old('pic_pelaksana', $tempPicPelaksanaArray);
                            @endphp
                            @foreach($picPelaksanaOptions as $pic)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="pic_pelaksana_{{ Str::slug($pic) }}" name="pic_pelaksana[]" value="{{ $pic }}"
                                        @checked(in_array($pic, $currentPicPelaksana))>
                                    <label class="form-check-label" for="pic_pelaksana_{{ Str::slug($pic) }}">{{ $pic }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label>Keterangan<span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $data_proyek->keterangan) }}</textarea>
                    </div>

                    <div class="form-group mb-2">
                        <label>Progress (%)<span class="text-danger">*</span></label>
                        {{-- Progress di halaman edit ini adalah progress total proyek, yang readonly --}}
                        <input type="number" name="progres" class="form-control" step="0.01" max="100" min="0" placeholder="0 - 100%" value="{{ old('progres', $data_proyek->progres) }}"  readonly required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Status<span class="text-danger">*</span></label>
                        {{-- Status juga readonly, nilai diambil dari data_proyek --}}
                        <select class="form-control" disabled>
                            <option value="{{ $data_proyek->status }}" selected>{{ $data_proyek->status }}</option>
                        </select>
                        <input type="hidden" name="status" value="{{ old('status', $data_proyek->status) }}">
                    </div>


                    <div class="form-group mb-3">
                        <label>Nomor Catatan Permintaan<span class="text-danger">*</span></label>
                        <input type="text" name="nomor_catatan_permintaan" class="form-control" value="{{ old('nomor_catatan_permintaan', $data_proyek->nomor_catatan_permintaan) }}">
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <a href="/data_proyek" class="btn btn-outline-secondary mr-2">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
