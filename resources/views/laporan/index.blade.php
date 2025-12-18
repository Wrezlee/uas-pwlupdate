@extends('layouts.app')

@section('title', 'Laporan & Analisis')
@section('breadcrumb', 'Laporan')

@push('styles')
<style>
    .report-card {
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
        height: 100%;
    }
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        border-color: #4e73df;
    }
    .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .quick-stats {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
    }
    .date-filter-card {
        background: #f8f9fc;
        border-left: 4px solid #4e73df;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
    }
    .stat-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-bar text-primary me-2"></i>
                Laporan & Analisis
            </h1>
            <p class="mb-0">Analisis data penjualan dan manajemen stok Anda</p>
        </div>
        <div class="d-flex">
            <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#dateFilterModal">
                <i class="fas fa-calendar-alt me-1"></i> Filter Periode
            </button>
            <button class="btn btn-primary" onclick="printReport()">
                <i class="fas fa-print me-1"></i> Cetak Semua Laporan
            </button>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penjualan (Hari Ini)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="todaySales">
                                Rp {{ number_format($todaySales, 0, ',', '.') }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-success mr-2">
                                    <i class="fas fa-arrow-up"></i> {{ $salesGrowth }}%
                                </span>
                                <span>Dari kemarin</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pesanan Selesai (Bulan Ini)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="completedOrders">
                                {{ $completedOrders }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-success mr-2">
                                    <i class="fas fa-arrow-up"></i> {{ $orderGrowth }}%
                                </span>
                                <span>Dari bulan lalu</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Barang
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ $totalProducts }}
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                             style="width: {{ min(($lowStockProducts/$totalProducts)*100, 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 text-xs">
                                <span class="text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ $lowStockProducts }} barang stok rendah
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Rata-rata Nilai Pesanan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($avgOrderValue, 0, ',', '.') }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="{{ $avgOrderGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $avgOrderGrowth >= 0 ? 'up' : 'down' }}"></i> 
                                    {{ abs($avgOrderGrowth) }}%
                                </span>
                                <span>Dari bulan lalu</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Reports Section -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <!-- Featured Report Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Ringkasan Performa
                    </h6>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary active" data-period="monthly">
                            Bulanan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-period="weekly">
                            Mingguan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-period="daily">
                            Harian
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="salesChart" height="250"></canvas>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-wrapper bg-primary">
                                    <i class="fas fa-shopping-cart fa-2x text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="stat-number text-primary">{{ $totalOrders }}</div>
                                    <div class="stat-label">Total Pesanan</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-wrapper bg-success">
                                    <i class="fas fa-money-bill-wave fa-2x text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="stat-number text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                                    <div class="stat-label">Total Pendapatan</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-info">
                                    <i class="fas fa-box-open fa-2x text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="stat-number text-info">{{ $totalItemsSold }}</div>
                                    <div class="stat-label">Barang Terjual</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('laporan.penjualan') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="icon-wrapper bg-primary bg-opacity-10 me-3">
                                <i class="fas fa-file-invoice-dollar text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">Laporan Penjualan Detail</div>
                                <small class="text-muted">Analisis lengkap penjualan</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        
                        <a href="{{ route('laporan.stok') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="icon-wrapper bg-success bg-opacity-10 me-3">
                                <i class="fas fa-chart-bar text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">Laporan Stok & Inventori</div>
                                <small class="text-muted">Analisis pergerakan stok</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        
                        <a href="{{ route('laporan.keuangan') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="icon-wrapper bg-info bg-opacity-10 me-3">
                                <i class="fas fa-chart-line text-info"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">Laporan Keuangan</div>
                                <small class="text-muted">Analisis arus kas & profit</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        
                        <button type="button" class="list-group-item list-group-item-action d-flex align-items-center" 
                                onclick="exportAllReports()">
                            <div class="icon-wrapper bg-warning bg-opacity-10 me-3">
                                <i class="fas fa-file-export text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">Export Semua Data</div>
                                <small class="text-muted">Excel, PDF, CSV</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards Grid -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card report-card shadow">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper bg-primary bg-gradient">
                        <i class="fas fa-chart-line fa-2x text-white"></i>
                    </div>
                    <h5 class="card-title mt-3 mb-2">Laporan Penjualan</h5>
                    <p class="card-text text-muted mb-4">
                        Analisis lengkap penjualan berdasarkan periode, produk terlaris, dan trend penjualan.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('laporan.penjualan') }}" class="btn btn-primary">
                            <i class="fas fa-eye me-2"></i>Lihat Detail
                        </a>
                        <a href="{{ route('laporan.export.penjualan') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Export Excel
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <small class="text-muted">
                        <i class="fas fa-history me-1"></i>
                        Update terakhir: {{ $lastUpdate->format('d M Y H:i') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card report-card shadow">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper bg-success bg-gradient">
                        <i class="fas fa-boxes fa-2x text-white"></i>
                    </div>
                    <h5 class="card-title mt-3 mb-2">Laporan Stok</h5>
                    <p class="card-text text-muted mb-4">
                        Monitoring stok barang, pergerakan inventori, dan analisis stok minimum.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('laporan.stok') }}" class="btn btn-success">
                            <i class="fas fa-eye me-2"></i>Lihat Detail
                        </a>
                        <a href="{{ route('laporan.export.stok') }}" class="btn btn-outline-success">
                            <i class="fas fa-download me-2"></i>Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <small class="text-muted">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        {{ $lowStockProducts }} produk stok rendah
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card report-card shadow">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper bg-info bg-gradient">
                        <i class="fas fa-chart-pie fa-2x text-white"></i>
                    </div>
                    <h5 class="card-title mt-3 mb-2">Laporan Keuangan</h5>
                    <p class="card-text text-muted mb-4">
                        Analisis profitabilitas, arus kas, dan performa keuangan bisnis Anda.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('laporan.keuangan') }}" class="btn btn-info">
                            <i class="fas fa-eye me-2"></i>Lihat Detail
                        </a>
                        <button type="button" class="btn btn-outline-info" onclick="showFinancialSummary()">
                            <i class="fas fa-chart-bar me-2"></i>Quick View
                        </button>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <small class="text-muted">
                        <i class="fas fa-money-bill-wave me-1"></i>
                        Profit bulan ini: Rp {{ number_format($monthlyProfit, 0, ',', '.') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Reports -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-fire me-2"></i>Produk Terlaris
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Terjual</th>
                                    <th class="text-end">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="fas fa-{{ $product->jenis == 'gas' ? 'fire' : 'wine-bottle' }} 
                                                    text-{{ $product->jenis == 'gas' ? 'warning' : 'info' }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                {{ $product->nama_barang }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">{{ $product->total_sold }}</span>
                                    </td>
                                    <td class="text-end text-success fw-bold">
                                        Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($recentActivities as $activity)
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-{{ $activity['color'] }}"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">{{ $activity['title'] }}</span>
                                    <small class="text-muted">{{ $activity['time'] }}</small>
                                </div>
                                <small class="text-muted">{{ $activity['description'] }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Date Filter Modal -->
<div class="modal fade" id="dateFilterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Periode Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="dateFilterForm" method="GET" action="{{ route('laporan.index') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rentang Tanggal</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ request('start_date', date('Y-m-01')) }}">
                            <span class="input-group-text">s/d</span>
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ request('end_date', date('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Laporan</label>
                        <select name="report_type" class="form-select">
                            <option value="all">Semua Laporan</option>
                            <option value="sales">Penjualan Saja</option>
                            <option value="stock">Stok Saja</option>
                            <option value="financial">Keuangan Saja</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="include_details" id="includeDetails">
                        <label class="form-check-label" for="includeDetails">
                            Sertakan detail transaksi
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Financial Summary Modal -->
<div class="modal fade" id="financialSummaryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ringkasan Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Content akan diisi via JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Initialize Sales Chart
let salesChart = null;

function initSalesChart() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    if (salesChart) {
        salesChart.destroy();
    }
    
    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Pendapatan',
                data: @json($chartData),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
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

// Period toggle
document.querySelectorAll('[data-period]').forEach(button => {
    button.addEventListener('click', function() {
        // Update active state
        document.querySelectorAll('[data-period]').forEach(btn => {
            btn.classList.remove('active');
        });
        this.classList.add('active');
        
        // Fetch data for selected period
        const period = this.dataset.period;
        fetch(`/api/laporan/sales-chart?period=${period}`)
            .then(response => response.json())
            .then(data => {
                if (salesChart) {
                    salesChart.data.labels = data.labels;
                    salesChart.data.datasets[0].data = data.data;
                    salesChart.update();
                }
            })
            .catch(error => console.error('Error:', error));
    });
});

// Print Report
function printReport() {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>Laporan Sistem - ${new Date().toLocaleDateString('id-ID')}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @media print {
                    @page { margin: 0.5in; }
                    body { font-size: 12pt; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="text-center mb-4">
                    <h3>Laporan Sistem Manajemen</h3>
                    <p>Periode: ${document.querySelector('input[name="start_date"]').value} s/d ${document.querySelector('input[name="end_date"]').value}</p>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-12">
                        ${document.querySelector('.row:first-child').innerHTML}
                    </div>
                </div>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    window.close();
                }
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

// Export All Reports
function exportAllReports() {
    Swal.fire({
        title: 'Export Laporan',
        html: `
            <div class="text-start">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="exportSales" checked>
                    <label class="form-check-label" for="exportSales">
                        Laporan Penjualan
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="exportStock" checked>
                    <label class="form-check-label" for="exportStock">
                        Laporan Stok
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="exportFinancial" checked>
                    <label class="form-check-label" for="exportFinancial">
                        Laporan Keuangan
                    </label>
                </div>
                <div class="mt-3">
                    <label class="form-label">Format File</label>
                    <select class="form-select" id="exportFormat">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Export',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const format = document.getElementById('exportFormat').value;
            const includeSales = document.getElementById('exportSales').checked;
            const includeStock = document.getElementById('exportStock').checked;
            const includeFinancial = document.getElementById('exportFinancial').checked;
            
            if (!includeSales && !includeStock && !includeFinancial) {
                Swal.showValidationMessage('Pilih minimal satu jenis laporan');
                return false;
            }
            
            return { format, includeSales, includeStock, includeFinancial };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { format, includeSales, includeStock, includeFinancial } = result.value;
            
            // Simulate export process
            Swal.fire({
                title: 'Mengeksport...',
                text: 'Sedang menyiapkan file laporan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    
                    // Simulate API call
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'File laporan siap diunduh',
                            confirmButtonText: 'Unduh',
                            showCancelButton: true,
                            cancelButtonText: 'Nanti'
                        }).then((downloadResult) => {
                            if (downloadResult.isConfirmed) {
                                // Trigger download
                                window.location.href = `/laporan/export/all?format=${format}`;
                            }
                        });
                    }, 1500);
                }
            });
        }
    });
}

// Show Financial Summary
function showFinancialSummary() {
    fetch('/api/laporan/financial-summary')
        .then(response => response.json())
        .then(data => {
            const modalBody = document.querySelector('#financialSummaryModal .modal-body');
            modalBody.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-start border-primary border-4">
                            <div class="card-body">
                                <h6 class="text-primary">Total Pendapatan</h6>
                                <h3>Rp ${data.total_revenue.toLocaleString('id-ID')}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-start border-success border-4">
                            <div class="card-body">
                                <h6 class="text-success">Profit Bersih</h6>
                                <h3>Rp ${data.net_profit.toLocaleString('id-ID')}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr>
                            <th>Kategori</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                        ${data.breakdown.map(item => `
                            <tr>
                                <td>${item.category}</td>
                                <td class="text-end">Rp ${item.amount.toLocaleString('id-ID')}</td>
                            </tr>
                        `).join('')}
                    </table>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('financialSummaryModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Gagal memuat data keuangan', 'error');
        });
}

// Auto-refresh stats every 30 seconds
function refreshStats() {
    fetch('/api/laporan/quick-stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('todaySales').textContent = 'Rp ' + data.today_sales.toLocaleString('id-ID');
            document.getElementById('completedOrders').textContent = data.completed_orders;
        })
        .catch(error => console.error('Error refreshing stats:', error));
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initSalesChart();
    
    // Auto-refresh every 30 seconds
    setInterval(refreshStats, 30000);
    
    // Set max date for date inputs
    const today = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.max = today;
    });
});
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-content {
    padding-bottom: 10px;
}
</style>
@endpush