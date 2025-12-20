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

@if(isset($error))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Perhatian!</strong> {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

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
                            <input class="btn-check" id="option2" type="radio" name="options" autocomplete="off" checked>
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
                @if($pesananTerbaru->count() > 0)
                <span class="badge bg-primary float-end">{{ $pesananTerbaru->count() }}</span>
                @endif
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($pesananTerbaru as $pesanan)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $pesanan->nama_pembeli }}</strong>
                                <div class="small text-medium-emphasis">
                                    @if($pesanan->tanggal instanceof \Carbon\Carbon)
                                        {{ $pesanan->tanggal->format('d M Y') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y') }}
                                    @endif
                                </div>
                            </div>
                            <div>
                                @php
                                    $statusClass = match($pesanan->status) {
                                        'selesai' => 'success',
                                        'diproses' => 'warning text-dark',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($pesanan->status) }}</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong class="text-primary">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-medium-emphasis py-4">
                        <i class="fas fa-shopping-cart fa-2x mb-2 text-muted"></i><br>
                        Belum ada pesanan
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('pesanan.index') }}" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-list me-1"></i> Lihat Semua Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
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
                             style="width: {{ $maxTerjual > 0 ? ($barang['total_terjual'] / $maxTerjual) * 100 : 0 }}%"
                             aria-valuenow="{{ $barang['total_terjual'] }}" 
                             aria-valuemin="0" 
                             aria-valuemax="{{ $maxTerjual }}">
                        </div>
                    </div>
                    @if($barang['total_pendapatan'] > 0)
                    <small class="text-muted d-block mt-1">
                        Rp {{ number_format($barang['total_pendapatan'], 0, ',', '.') }}
                    </small>
                    @endif
                </div>
                @empty
                <div class="text-center text-medium-emphasis py-4">
                    <i class="fas fa-chart-line fa-2x mb-2 text-muted"></i><br>
                    Belum ada data penjualan
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Laporan Penjualan -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <strong>Laporan Penjualan</strong>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-3 fw-bold text-primary">{{ $pesananHariIni }}</div>
                            <small class="text-muted">Pesanan Hari Ini</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-3 fw-bold text-success">Rp {{ number_format($pendapatanHariIni / 1000, 0) }}K</div>
                            <small class="text-muted">Pendapatan Hari Ini</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-3 fw-bold text-warning">{{ $stokGas }}</div>
                            <small class="text-muted">Stok Gas Tersedia</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-3 fw-bold text-info">{{ $stokGalon }}</div>
                            <small class="text-muted">Stok Galon Tersedia</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('laporan.penjualan') }}" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-chart-bar me-1"></i> Lihat Laporan Lengkap
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Aktivitas Terbaru -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Aktivitas Terbaru</strong>
                <button type="button" class="btn btn-sm btn-primary" onclick="loadLatestNotifications()">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">Waktu</th>
                                <th width="15%">Jenis</th>
                                <th width="40%">Deskripsi</th>
                                <th width="25%">Detail</th>
                                <th width="10%">Status</th>
                            </tr>
                        </thead>
                        <tbody id="realtimeNotifications">
                            <!-- Notifikasi akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Aktivitas otomatis diperbarui setiap 30 detik
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    
    /* Notifikasi Styles */
    .notification-new {
        animation: pulse 2s infinite;
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    @keyframes pulse {
        0% { background-color: rgba(255, 193, 7, 0.1); }
        50% { background-color: rgba(255, 193, 7, 0.2); }
        100% { background-color: rgba(255, 193, 7, 0.1); }
    }
    
    .notification-type-pesanan { border-left: 4px solid #007bff; }
    .notification-type-stok-masuk { border-left: 4px solid #28a745; }
    .notification-type-stok-keluar { border-left: 4px solid #dc3545; }
    .notification-type-system { border-left: 4px solid #6c757d; }
    
    /* Laporan Styles */
    .stats-box {
        transition: all 0.3s ease;
    }
    
    .stats-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        setupAutoRefresh();
        loadLatestNotifications(); // Load notifications on page load
        
        // Setup polling for new notifications
        setupNotificationPolling();
    });
    
    function initializeCharts() {
        // Main Chart
        const mainChartCtx = document.getElementById('main-chart');
        if (mainChartCtx) {
            const chartLabels = @json($chartLabels);
            const chartData = @json($chartData);
            
            console.log('Chart Labels:', chartLabels);
            console.log('Chart Data:', chartData);
            
            // Ensure data is valid
            const validData = chartData.map(value => {
                return value !== null ? parseFloat(value) : 0;
            });
            
            const chartConfig = {
                labels: chartLabels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: validData,
                    backgroundColor: 'rgba(50, 31, 219, 0.1)',
                    borderColor: 'rgba(50, 31, 219, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(50, 31, 219, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            };
            
            try {
                new Chart(mainChartCtx, {
                    type: 'line',
                    data: chartConfig,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    },
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    borderDash: [2, 2]
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'nearest'
                        }
                    }
                });
                console.log('Chart berhasil dibuat');
            } catch (error) {
                console.error('Error membuat chart:', error);
                // Fallback: tampilkan pesan jika chart gagal
                mainChartCtx.parentElement.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                        <p>Grafik penjualan tidak dapat ditampilkan</p>
                        <small>Data: ${validData.join(', ')}</small>
                    </div>
                `;
            }
        } else {
            console.error('Chart canvas tidak ditemukan');
        }
        
        // Mini Charts for Cards (gunakan data dummy)
        createMiniChart('card-chart1', [30, 40, 35, 50, 45, 60, 55]);
        createMiniChart('card-chart2', [100000, 150000, 120000, 180000, 200000, 160000, 220000]);
        createMiniChart('card-chart3', [80, 75, 85, 70, 90, 80, 85]);
        createMiniChart('card-chart4', [50, 55, 60, 52, 58, 62, 59]);
    }
    
    function createMiniChart(canvasId, data) {
        const ctx = document.getElementById(canvasId);
        if (ctx) {
            try {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            data: data,
                            backgroundColor: 'transparent',
                            borderColor: 'rgba(255,255,255,.85)',
                            borderWidth: 2,
                            tension: 0.4
                        }]
                    },
                    options: {
                        plugins: { 
                            legend: { display: false } 
                        },
                        maintainAspectRatio: false,
                        scales: {
                            x: { 
                                display: false 
                            },
                            y: { 
                                display: false 
                            }
                        },
                        elements: {
                            line: { 
                                borderWidth: 2, 
                                tension: 0.4 
                            },
                            point: { 
                                radius: 0, 
                                hitRadius: 10, 
                                hoverRadius: 4 
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error membuat mini chart:', canvasId, error);
            }
        }
    }
    
    // Load latest notifications
    function loadLatestNotifications() {
        fetch('{{ route("dashboard.notifications") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const container = document.getElementById('realtimeNotifications');
            if (container && data.success) {
                container.innerHTML = data.html;
                
                // Play sound if there are new notifications
                if (data.hasNewNotifications) {
                    playNotificationSound();
                    showToast('Ada aktivitas baru!', 'info');
                }
            }
        })
        .catch(error => console.error('Error loading notifications:', error));
    }
    
    // Setup polling for new notifications every 30 seconds
    function setupNotificationPolling() {
        setInterval(() => {
            loadLatestNotifications();
        }, 30000); // 30 seconds
    }
    
    // Auto refresh dashboard setiap 2 menit
    function setupAutoRefresh() {
        setTimeout(function() {
            window.location.reload();
        }, 120000); // 120000 ms = 2 menit
    }
    
    // Play notification sound
    function playNotificationSound() {
        try {
            const audio = new Audio('{{ asset("sounds/notification.mp3") }}');
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Audio play failed:', e));
        } catch (e) {
            console.log('Audio not available:', e);
        }
    }
    
    // Show toast notification
    function showToast(message, type = 'info') {
        // Create toast container if not exists
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'position-fixed top-0 end-0 p-3';
            toastContainer.style = 'z-index: 11';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast align-items-center text-white bg-${type === 'info' ? 'primary' : type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        // Determine icon
        let icon = 'info-circle';
        if (type === 'success') icon = 'check-circle';
        if (type === 'warning') icon = 'exclamation-triangle';
        if (type === 'danger') icon = 'exclamation-triangle';
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${icon} me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Initialize and show toast
        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();
        
        // Remove toast after hide
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }
    
    // Mark notification as read
    function markAsRead(notificationId) {
        fetch(`/dashboard/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notificationRow = document.querySelector(`tr[data-id="${notificationId}"]`);
                if (notificationRow) {
                    notificationRow.classList.remove('notification-new');
                }
            }
        });
    }
</script>
@endpush