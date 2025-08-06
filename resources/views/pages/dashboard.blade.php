@extends('layout.home')

@section('content')
    <div class="container-fluid pt-4">

        {{-- Header --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        {{-- Baris Kartu Statistik --}}
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Proyek</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProyek ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-folder-open fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Proyek Selesai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $proyekSelesai ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Proyek Berjalan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $proyekBerjalan ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-spinner fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Akun Menunggu</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $akunMenunggu ?? 0 }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-user-plus fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Baris untuk Grafik Batang --}}
        <div class="row">
            {{-- Grafik Tren Proyek Bulanan --}}
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-line fa-fw mr-2"></i>Tren
                            Proyek (6 Bulan Terakhir)</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area" style="height: 320px;">
                            <canvas id="projectTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grafik Beban Kerja PIC --}}
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users fa-fw mr-2"></i>Beban Kerja PIC
                            Aktif</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar" style="height: 320px;">
                            <canvas id="picWorkloadChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Baris Konten (Tabel & Grafik Pie) --}}
        <div class="row">
            {{-- Kolom Kiri: Tabel Target Proyek --}}
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tasks fa-fw mr-2"></i>Proyek
                            Mendekati Target</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No. CR</th>
                                        <th>Owner</th>
                                        <th class="text-right">Sisa Waktu Target</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($semuaProyek as $proyek)
                                        <tr>
                                            <td>
                                                <a href="{{ route('data_proyek.kegiatan_detail', $proyek->id) }}">
                                                    <strong>{{ $proyek->nomor_cr }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                {{-- DIPERBAIKI: Logika untuk menangani format data yang tidak konsisten --}}
                                                @php
                                                    $owners = $proyek->owner;
                                                    if (is_string($owners)) {
                                                        $decoded = json_decode($owners, true);
                                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                            $owners = $decoded;
                                                        }
                                                    }
                                                @endphp
                                                {{ is_array($owners) ? implode(', ', $owners) : $owners }}
                                            </td>
                                            <td class="text-right align-middle">
                                                <span class="{{ $proyek->target_status['class'] }}">
                                                    {{ $proyek->target_status['text'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada proyek yang sedang berjalan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-center">
                        {{ $semuaProyek->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Grafik Pie --}}
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie fa-fw mr-2"></i>Komposisi
                            Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-2" style="height: 250px;">
                            <canvas id="statusProyekChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-sitemap fa-fw mr-2"></i>Komposisi
                            Owner</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-2" style="height: 250px;">
                            <canvas id="ownerProyekChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    {{-- Menggunakan CDN untuk Chart.js agar lebih mudah --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Fungsi helper untuk menghasilkan warna acak
        function getRandomColor(count) {
            const colors = [];
            const baseColors = [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                '#858796', '#5a5c69', '#f8f9fc', '#6f42c1', '#fd7e14'
            ];
            for (let i = 0; i < count; i++) {
                colors.push(baseColors[i % baseColors.length] + 'B3'); // Tambah transparansi
            }
            return colors;
        }

        // Fungsi untuk membuat Pie/Doughnut Chart
        function createPieChart(canvasId, labels, data, title) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;
            const backgroundColors = getRandomColor(data.length);
            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: { labels: labels, datasets: [{ label: title, data: data, backgroundColor: backgroundColors, hoverOffset: 4, borderWidth: 1 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 11 } } } }, cutout: '65%' }
            });
        }

        // Fungsi untuk membuat Bar Chart
        function createBarChart(canvasId, labels, data, title, chartType = 'bar', yAxisTitle = 'Jumlah Proyek') {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;

            const backgroundColors = getRandomColor(data.length);
            const borderColors = backgroundColors.map(color => color.slice(0, 7));

            new Chart(ctx.getContext('2d'), {
                type: chartType,
                data: {
                    labels: labels,
                    datasets: [{
                        label: yAxisTitle,
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: chartType === 'horizontalBar' ? 'y' : 'x',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }


        // Inisialisasi semua Chart saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = {!! json_encode($chartData) !!};

            // Inisialisasi Pie Charts
            if (chartData.status && chartData.status.labels.length > 0) {
                createPieChart('statusProyekChart', chartData.status.labels, chartData.status.data, 'Status Proyek');
            }
            if (chartData.owner && chartData.owner.labels.length > 0) {
                createPieChart('ownerProyekChart', chartData.owner.labels, chartData.owner.data, 'Owner Proyek');
            }

            // Inisialisasi Bar Charts
            if (chartData.trends && chartData.trends.labels.length > 0) {
                createBarChart('projectTrendChart', chartData.trends.labels, chartData.trends.data, 'Tren Proyek');
            }
            if (chartData.workload && chartData.workload.labels.length > 0) {
                createBarChart('picWorkloadChart', chartData.workload.labels, chartData.workload.data, 'Beban Kerja PIC', 'bar');
            }
        });
    </script>
@endpush