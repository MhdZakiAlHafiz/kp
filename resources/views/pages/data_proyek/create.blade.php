@extends('layout.home')

@section('content')
<!-- Page Heading -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Data Proyek</h1>
    </div>

    <div class="row">
    <div class="col">
        <form action="/data_proyek" method="POST">
            @csrf
            @method('POST')
            <div class="card">
                {{-- <div class="card-header">
                    <h5>Form Input Data Proyek</h5>
                </div> --}}

                <div class="card-body">

                    <div class="form-group mb-2">
                        <label>Nomor CR <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_cr" id="nomor_cr" class="form-control" readonly required>
                    </div>

                    <script>
                        document.querySelector('select[name="jenis_surat"]').addEventListener('change', function () {
                            const jenis = this.value;
                            if (!jenis) return;

                            fetch(`/generate-nomor-cr/${jenis}`)
                                .then(response => response.json())
                                .then(data => {
                                    document.getElementById('nomor_cr').value = data.nomor_cr;
                                });
                        });
                    </script>


                    <div class="form-group mb-2">
                        <label>Jenis Surat <span class="text-danger">*</span></label>
                        <select name="jenis_surat" class="form-control" required>
                            <option value="">-- Pilih Jenis Surat --</option>
                            <option value="BRD">BRD </option>
                            <option value="CR">CR </option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label>Owner/Pemilik <span class="text-danger">*</span></label>
                        <select name="owner" class="form-control" required>
                            <option value="">-- Pilih Divisi --</option>
                            <option value="Divisi TSI">Divisi TSI</option>
                            <option value="Divisi OPS">Divisi OPS</option>
                            <option value="Divisi MDM">Divisi MDM</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label>Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="PKLD">PKLD</option>
                            <option value="TAMBAHAN">TAMBAHAN</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label>Target <span class="text-danger">*</span></label>
                        <input type="month" name="target" class="form-control" required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Target Disepakati <span class="text-danger">*</span></label>
                        <input type="month" name="target_disepakati" class="form-control" required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Target Kesepakatan <span class="text-danger">*</span></label>
                        <input type="month" name="target_kesepakatan" class="form-control" required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Detail Pengembangan <span class="text-danger">*</span></label>
                        <textarea name="detail_pengembangan" class="form-control" rows="3" required></textarea>
                    </div>

                    {{-- PIC Perencana Checkbox Group --}}
                    <div class="form-group mb-2">
                        <label>PIC Plan<span class="text-danger">*</span></label>
                        <div>
                            @php
                                $picPerencanaOptions = [
                                                            'Ronaldy', 'Lutfi', 'Wildan', 'Ori', 'Bima',
                                                            'Koiri', 'Ardi', 'Nanda', 'Fikri', 'Rizky', 'Dinda', 'Andi', 'Tari', 'Fajar',
                                                            'Mega', 'Putra', 'Salma', 'Iqbal', 'Novi'
                                                        ];
                            @endphp
                            @foreach($picPerencanaOptions as $pic)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="pic_perencana_{{ Str::slug($pic) }}" name="pic_perencana[]" value="{{ $pic }}">
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
                                                            'Koiri', 'Ardi', 'Nanda', 'Fikri', 'Rizky', 'Dinda', 'Andi', 'Tari', 'Fajar',
                                                            'Mega', 'Putra', 'Salma', 'Iqbal', 'Novi'
                                                        ];
                            @endphp
                            @foreach($picPelaksanaOptions as $pic)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="pic_pelaksana_{{ Str::slug($pic) }}" name="pic_pelaksana[]" value="{{ $pic }}">
                                    <label class="form-check-label" for="pic_pelaksana_{{ Str::slug($pic) }}">{{ $pic }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label>Keterangan<span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="form-group mb-2">
                        <label>Progress (%)<span class="text-danger">*</span></label>
                        <input type="number" name="progres" class="form-control" step="0.01" max="100" min="0" placeholder="0 - 100%" value="0"  readonly required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Status<span class="text-danger">*</span></label>
                        <select class="form-control" disabled>
                            <option value="Not Started" selected>Not Started</option>
                        </select>
                        <input type="hidden" name="status" value="Not Started">
                    </div>


                    <div class="form-group mb-3">
                        <label>Nomor Catatan Permintaan<span class="text-danger">*</span></label>
                        <input type="text" name="nomor_catatan_permintaan" class="form-control">
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
