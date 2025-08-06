@php
    // --- PERSIAPAN DATA & KALKULASI UNTUK VISUALISASI ---
    $kegiatan_detail = json_decode($data_proyek->kegiatan_detail, true) ?? [];

    // --- KALKULASI LINIMASA PROYEK ---
    $start_date_str = $data_proyek->created_at ?? null;
    $target_date_str = $data_proyek->target_kesepakatan ?? null;

    $days_remaining = 'N/A';
    $time_progress_percent = 0;
    $timeline_label = 'Durasi belum ditentukan';

    if ($start_date_str && $target_date_str) {
        $start_date = \Carbon\Carbon::parse($start_date_str)->startOfDay();
        $target_date = \Carbon\Carbon::parse($target_date_str)->startOfDay();
        $today = \Carbon\Carbon::now()->startOfDay();

        if ($target_date->isAfter($start_date)) {
            $total_duration_days = $start_date->diffInDays($target_date) ?: 1; // Hindari pembagian dengan nol
            $days_elapsed = $start_date->diffInDays($today);

            if ($days_elapsed < 0)
                $days_elapsed = 0;
            if ($days_elapsed > $total_duration_days)
                $days_elapsed = $total_duration_days;

            $time_progress_percent = ($days_elapsed / $total_duration_days) * 100;

            $remaining = $today->diffInDays($target_date, false); // false agar bisa negatif
            if ($remaining >= 0) {
                $days_remaining = $remaining . ' hari lagi';
            } else {
                $days_remaining = 'Terlewat ' . abs($remaining) . ' hari';
            }
            $timeline_label = 'Waktu telah berjalan ' . number_format($time_progress_percent, 0) . '%';
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Progres Proyek - {{ $data_proyek->nomor_cr }}</title>
    <style>
        /* --- GAYA BARU UNTUK TABEL DETAIL KEGIATAN --- */
        .detail-table-new {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table-new th,
        .detail-table-new td {
            padding: 10px;
            border: 1px solid var(--border-color);
            text-align: left;
            vertical-align: middle;
        }

        .detail-table-new thead th {
            background-color: var(--bg-color);
            font-size: 10px;
            text-transform: uppercase;
            text-align: center;
        }

        .main-task-row-new {
            font-weight: bold;
            background-color: #fdfdfd;
        }

        .sub-task-row-new td:first-child {
            border-left: none;
            border-right: none;
        }

        .sub-task-row-new .task-name {
            padding-left: 25px;
            /* Indentasi untuk sub-tugas */
            font-weight: normal;
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        :root {
            /* Warna disesuaikan dengan gambar referensi */
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            /* Biru untuk status 'Completed' */
            --text-color: #34495e;
            --light-text-color: #95a5a6;
            /* Abu-abu untuk label */
            --bg-color: #ffffff;
            /* Latar kartu menjadi putih */
            --border-color: #ecf0f1;
            --time-bar-color: #9b59b6;
            /* Warna ungu untuk linimasa */
        }

        @page {
            margin: 35px;
        }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            color: var(--text-color);
            background-color: #fff;
        }

        .wrapper {
            padding: 0;
        }

        /* --- HEADER --- */
        .header {
            padding-bottom: 20px;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--border-color);
            /* Garis header lebih tipis */
        }

        .header .logo {
            float: left;
            width: 160px;
            height: auto;
        }

        .header .title-group {
            float: right;
            text-align: right;
            padding-top: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            color: var(--primary-color);
        }

        .header p {
            margin: 5px 0 0;
            font-size: 13px;
            color: var(--light-text-color);
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* --- VISUALISASI KPI (Gaya Baru) --- */
        .kpi-section {
            margin-bottom: 35px;
            page-break-inside: avoid;
        }

        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 20px 0;
        }

        .kpi-table td.kpi-card {
            width: 33.33%;
            background-color: var(--bg-color);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            vertical-align: top;
        }

        .kpi-title {
            font-size: 11px;
            /* Lebih kecil */
            font-weight: bold;
            color: var(--light-text-color);
            text-transform: uppercase;
            margin-bottom: 20px;
            text-align: center;
        }

        .kpi-big-number {
            font-size: 48px;
            /* Sedikit lebih kecil agar rapi */
            font-weight: bold;
            color: var(--primary-color);
            text-align: center;
        }

        .kpi-big-number-label {
            text-align: center;
            font-size: 12px;
            color: var(--light-text-color);
            margin-top: 5px;
        }

        /* Linimasa Proyek */
        .timeline-roadmap {
            position: relative;
            height: 20px;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .timeline-line {
            position: absolute;
            top: 4px;
            left: 6px;
            right: 6px;
            height: 4px;
            background-color: var(--border-color);
            border-radius: 2px;
        }

        .timeline-progress {
            position: absolute;
            top: 4px;
            left: 6px;
            height: 4px;
            background-color: var(--time-bar-color);
            border-radius: 2px;
        }

        .timeline-dot {
            position: absolute;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: white;
            border: 3px solid var(--time-bar-color);
        }

        .timeline-dot.start {
            left: 0;
        }

        .timeline-dot.end {
            right: 0;
        }

        .timeline-dot-label {
            position: absolute;
            top: 18px;
            font-size: 10px;
            font-weight: bold;
            color: var(--light-text-color);
        }

        .label-start {
            left: 0;
        }

        .label-end {
            right: 0;
        }

        .timeline-info {
            text-align: center;
            font-size: 11px;
            line-height: 1.6;
            color: var(--text-color);
        }

        .timeline-info .value {
            font-weight: bold;
        }

        /* Informasi Proyek (Gaya Baru) */
        .kpi-info-list {
            width: 100%;
            border-collapse: collapse;
        }

        .kpi-info-list tr {
            border-bottom: 1px solid var(--border-color);
        }

        .kpi-info-list tr:last-child {
            border-bottom: none;
        }

        .kpi-info-list td {
            padding: 10px 5px;
            vertical-align: middle;
        }

        .kpi-info-list .label {
            font-size: 11px;
            color: var(--light-text-color);
        }

        .kpi-info-list .data {
            font-size: 12px;
            font-weight: bold;
            text-align: right;
        }

        .kpi-info-list .data.completed {
            color: var(--secondary-color);
        }

        /* --- KONTEN UTAMA --- */
        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .info-grid {
            margin-bottom: 20px;
        }

        .info-col {
            float: left;
            width: 48%;
            margin-right: 4%;
        }

        .info-col.last {
            margin-right: 0;
        }

        .info-item {
            margin-bottom: 18px;
        }

        .info-item .label {
            font-size: 11px;
            font-weight: bold;
            color: var(--light-text-color);
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .info-item .data {
            font-size: 12px;
            line-height: 1.5;
        }

        .info-item .data-justify {
            text-align: justify;
        }

        /* --- DETAIL KEGIATAN --- */
        .task-block,
        .detail-table {
            /* Styling applies to both */
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
        }

        .task-header {
            font-size: 14px;
            font-weight: bold;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .task-body {
            padding: 15px;
        }

        .task-progress-info {
            font-size: 11px;
            color: var(--light-text-color);
            margin-bottom: 5px;
        }

        .task-progress-info .value {
            font-weight: bold;
            color: var(--text-color);
        }

        .progress-bar-container {
            height: 10px;
            width: 100%;
            background-color: var(--border-color);
            border-radius: 5px;
        }

        .progress-bar {
            height: 100%;
            border-radius: 5px;
            background-color: var(--secondary-color);
        }

        .subtask-list {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed var(--border-color);
        }

        .subtask-item {
            margin-bottom: 8px;
            font-size: 11px;
        }

        .subtask-poin {
            font-size: 10px;
            color: var(--light-text-color);
            font-weight: bold;
            float: right;
        }

        /* --- FOOTER --- */
        .footer {
            text-align: center;
            font-size: 10px;
            color: var(--light-text-color);
            padding-top: 20px;
            margin-top: 30px;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header clearfix">
            <img src="{{ public_path('dash/img/logo-brks.png') }}" alt="Logo" class="logo">
            <div class="title-group">
                <h1>Laporan Progres Proyek</h1>
                <p>No. Dokumen: {{ $data_proyek->nomor_cr }}</p>
            </div>
        </div>

        <div class="kpi-section">
            <table class="kpi-table">
                <tr>
                    <td class="kpi-card">
                        <div class="kpi-title">Progres Keseluruhan</div>
                        <div class="kpi-big-number">{{ number_format($data_proyek->progres, 1) }}%</div>
                        <div class="kpi-big-number-label">Tercapai</div>
                    </td>
                    <td class="kpi-card">
                        <div class="kpi-title">Linimasa Proyek</div>
                        <div class="timeline-roadmap">
                            <div class="timeline-line"></div>
                            <div class="timeline-progress" style="width: {{ $time_progress_percent }}%;"></div>
                            <div class="timeline-dot start"></div>
                            <div class="timeline-dot end"></div>
                            <div class="timeline-dot-label label-start">Mulai</div>
                            <div class="timeline-dot-label label-end">Target</div>
                        </div>
                        <div class="timeline-info">
                            <div>{{ $timeline_label }}</div>
                            <div>Sisa Waktu: <span class="value">{{ $days_remaining }}</span></div>
                        </div>
                    </td>
                    <td class="kpi-card">
                        <div class="kpi-title">Informasi Proyek</div>
                        <table class="kpi-info-list">
                            <tr>
                                <td class="label">Status:</td>
                                <td class="data {{ strtolower($data_proyek->status) }}">{{ $data_proyek->status }}</td>
                            </tr>
                            <tr>
                                <td class="label">Target Disepakati:</td>
                                <td class="data">
                                    {{ $data_proyek->target_disepakati ? \Carbon\Carbon::parse($data_proyek->target_disepakati)->translatedFormat('F Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Target Kesepakatan:</td>
                                <td class="data">
                                    {{ $data_proyek->target_kesepakatan ? \Carbon\Carbon::parse($data_proyek->target_kesepakatan)->translatedFormat('F Y') : '-' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Ringkasan Proyek</div>
            <div class="info-item">
                <div class="label">Keterangan</div>
                <div class="data data-justify">{{ $data_proyek->detail_pengembangan }}</div>
            </div>
            <div class="info-grid clearfix">
                <div class="info-col">
                    <div class="info-item">
                        <div class="label">Divisi Pemilik (Owner)</div>
                        <div class="data">
                            {{ is_array($decoded = json_decode($data_proyek->owner, true)) ? implode(', ', $decoded) : $data_proyek->owner }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">PIC Planning</div>
                        <div class="data">
                            {{ is_array($decoded = json_decode($data_proyek->pic_perencana, true)) ? implode(', ', $decoded) : 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="info-col last">
                    <div class="info-item">
                        <div class="label">Jenis Proyek</div>
                        <div class="data">
                            {{ is_array($decoded = json_decode($data_proyek->jenis, true)) ? implode(', ', $decoded) : $data_proyek->jenis }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">PIC Development</div>
                        <div class="data">
                            {{ is_array($decoded = json_decode($data_proyek->pic_pelaksana, true)) ? implode(', ', $decoded) : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Detail Progres Kegiatan</div>
            <table class="detail-table-new">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Kegiatan</th>
                        <th width="15%">Poin</th>
                        <th width="30%">Progres</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kegiatan_detail as $item)
                        <tr class="main-task-row-new">
                            <td class="text-center">{{ $item['no'] }}</td>
                            <td>{{ $item['kegiatan'] }}</td>
                            <td class="text-center">{{ $item['progress'] ?? 0 }} / {{ $item['bobot'] ?? 0 }}</td>
                            <td>
                                @php 
                                    $progress_value = isset($item['progress']) ? $item['progress'] : 0;
                                    $bobot_value = isset($item['bobot']) ? $item['bobot'] : 0;
                                    $progres_persen = ($bobot_value > 0 ? ($progress_value / $bobot_value) * 100 : 0); 
                                @endphp
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $progres_persen }}%;"></div>
                                </div>
                            </td>
                        </tr>

                        @if (isset($item['sub']) && is_array($item['sub']))
                            @foreach ($item['sub'] as $subItem)
                                <tr class="sub-task-row-new">
                                    <td></td>
                                    <td class="task-name">- {{ $subItem['kegiatan'] }}</td>
                                    <td class="text-center" style="font-weight: normal; font-size: 10px;">
                                        ({{ $subItem['bobot'] ?? 0 }} Poin)</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Tidak ada detail kegiatan yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer">
            Dokumen ini dibuat secara otomatis oleh Sistem Informasi Divisi TSI | {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>
</body>

</html>