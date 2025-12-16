@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<!-- Statistik Cards -->
<div class="row">
    <!-- Pesanan Hari Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-primary">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $pesananHariIni }}</div>
                    <div>Pesanan Hari Ini</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                </div>
            </div>
            <div class="mt-3 mx-3" style="height:70px;">
                <canvas class="chart" id="card-chart1" height="70"></canvas>
            </div>
        </div>
    </div>

    <!-- Pendapatan Hari Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-success">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">Rp {{ number_format($pendapatanHariIni / 1000, 0) }}K</div>
                    <div>Pendapatan Hari Ini</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                </div>
            </div>
            <div class="mt-3 mx-3" style="height:70px;">
                <canvas class="chart" id="card-chart2" height="70"></canvas>
            </div>
        </div>
    </div>

    <!-- Stok Gas -->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-warning">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $stokGas }}</div>
                    <div>Stok Gas</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-fire fa-2x opacity-75"></i>
                </div>
            </div>
            <div class="mt-3" style="height:70px;">
                <canvas class="chart" id="card-chart3" height="70"></canvas>
            </div>
        </div>
    </div>

    <!-- Stok Galon -->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-info">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $stokGalon }}</div>
                    <div>Stok Galon</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-tint fa-2x opacity-75"></i>
                </div>
            </div>
            <div class="mt-3 mx-3" style="height:70px;">
                <canvas class="chart" id="card-chart4" height="70"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Grafik Penjualan -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">Penjualan</h4>
                        <div class="small text-medium-emphasis">7 Hari Terakhir</div>
                    </div>
                    <div class="btn-toolbar d-none d-md-block" role="toolbar">
                        <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">
                            <input class="btn-check" id="option1" type="radio" name="options" autocomplete="off">
                            <label class="btn btn-outline-secondary">Hari</label>
                            <input class="btn-check" id="option2" type="radio" name="options" autocomplete="off" checked="">
                            <label class="btn btn-outline-secondary active">Minggu</label>
                            <input class="btn-check" id="option3" type="radio" name="options" autocomplete="off">
                            <label class="btn btn-outline-secondary">Bulan</label>
                        </div>
                    </div>
                </div>
                <div style="height:300px;margin-top:40px;">
                    <canvas class="chart" id="main-chart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <strong>Pesanan Terbaru</strong>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($pesananTerbaru as $pesanan)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $pesanan->nama_pembeli }}</strong>
                                <div class="small text-medium-emphasis">
                                    {{ $pesanan->tanggal->format('d M Y') }}
                                </div>
                            </div>
                            <div>
                                @if($pesanan->status == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($pesanan->status == 'diproses')
                                    <span class="badge bg-warning text-dark">Diproses</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong class="text-primary">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-medium-emphasis">
                        Belum ada pesanan
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('pesanan.index') }}" class="btn btn-sm btn-primary w-100">
                    Lihat Semua Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Notifikasi -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <strong>Notifikasi Terbaru</strong>
            </div>
            <div class="card-body">
                @forelse($notifikasi as $notif)
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="me-3">
                        <i class="fas fa-bell fa-lg {{ $notif->status == 'belum_dibaca' ? 'text-warning' : 'text-secondary' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0 {{ $notif->status == 'belum_dibaca' ? 'fw-bold' : '' }}">
                            {{ $notif->pesan }}
                        </p>
                        <small class="text-medium-emphasis">{{ $notif->tanggal->diffForHumans() }}</small>
                    </div>
                </div>
                @empty
                <p class="text-center text-medium-emphasis">Tidak ada notifikasi</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Barang Terlaris -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <strong>Barang Terlaris</strong>
            </div>
            <div class="card-body">
                @forelse($barangTerlaris as $barang)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">{{ $barang['nama_barang'] }}</span>
                        <span class="badge bg-primary">{{ $barang['total_terjual'] }} Terjual</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" 
                             style="width: {{ ($barang['total_terjual'] / $maxTerjual) * 100 }}%"
                             aria-valuenow="{{ $barang['total_terjual'] }}" 
                             aria-valuemin="0" 
                             aria-valuemax="{{ $maxTerjual }}">
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-medium-emphasis">Belum ada data penjualan</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Grafik Penjualan Utama
    const mainChartCtx = document.getElementById('main-chart');
    if (mainChartCtx) {
        const mainChart = new Chart(mainChartCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: @json($chartData),
                    backgroundColor: 'rgba(50, 31, 219, 0.1)',
                    borderColor: 'rgba(50, 31, 219, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Mini Charts untuk Cards
    const cardChart1 = document.getElementById('card-chart1');
    if (cardChart1) {
        new Chart(cardChart1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    data: [65, 59, 84, 84, 51, 55, 40],
                    backgroundColor: 'transparent',
                    borderColor: 'rgba(255,255,255,.55)',
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                maintainAspectRatio: false,
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                elements: {
                    line: { borderWidth: 2, tension: 0.4 },
                    point: { radius: 0, hitRadius: 10, hoverRadius: 4 }
                }
            }
        });
    }

    const cardChart2 = document.getElementById('card-chart2');
    if (cardChart2) {
        new Chart(cardChart2, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    data: [1, 18, 9, 17, 34, 22, 11],
                    backgroundColor: 'transparent',
                    borderColor: 'rgba(255,255,255,.55)',
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                maintainAspectRatio: false,
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                elements: {
                    line: { borderWidth: 2, tension: 0.4 },
                    point: { radius: 0, hitRadius: 10, hoverRadius: 4 }
                }
            }
        });
    }

    const cardChart3 = document.getElementById('card-chart3');
    if (cardChart3) {
        new Chart(cardChart3, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    data: [78, 81, 80, 45, 34, 12, 40],
                    backgroundColor: 'transparent',
                    borderColor: 'rgba(255,255,255,.55)',
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                maintainAspectRatio: false,
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                elements: {
                    line: { borderWidth: 2, tension: 0.4 },
                    point: { radius: 0, hitRadius: 10, hoverRadius: 4 }
                }
            }
        });
    }

    const cardChart4 = document.getElementById('card-chart4');
    if (cardChart4) {
        new Chart(cardChart4, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    data: [35, 23, 56, 22, 97, 23, 64],
                    backgroundColor: 'transparent',
                    borderColor: 'rgba(255,255,255,.55)',
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                maintainAspectRatio: false,
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                elements: {
                    line: { borderWidth: 2, tension: 0.4 },
                    point: { radius: 0, hitRadius: 10, hoverRadius: 4 }
                }
            }
        });
    }
</script>
@endpush