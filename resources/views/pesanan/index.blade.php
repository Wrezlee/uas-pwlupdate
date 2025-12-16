@extends('layouts.app')

@section('title', 'Daftar Pesanan')
@section('breadcrumb', 'Pesanan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Daftar Pesanan</h4>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <a href="{{ route('pesanan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Pesanan
                        </a>
                    </div>
                    <div class="d-flex">
                        <input type="text" class="form-control me-2" placeholder="Cari pesanan..." id="searchInput">
                        <select class="form-select me-2" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="diproses">Diproses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Pembeli</th>
                                <th>No. HP</th>
                                <th>Tanggal</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pesananTable">
                            <!-- Data pesanan akan dimuat di sini -->
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border spinner-border-sm me-2" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        Memuat data pesanan...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">Menampilkan <span id="showingFrom">0</span> - <span id="showingTo">0</span> dari <span id="totalRecords">0</span> pesanan</small>
                    </div>
                    <nav aria-label="Pagination">
                        <ul class="pagination pagination-sm mb-0" id="pagination">
                            <!-- Pagination links akan dimuat di sini -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pesanan -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pesanan #<span id="detailId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Nama Pembeli:</strong> <span id="detailNama"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>No. HP:</strong> <span id="detailHp"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Tanggal:</strong> <span id="detailTanggal"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong> <span id="detailStatus"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Alamat:</strong> <span id="detailAlamat"></span>
                </div>

                <h6>Detail Barang:</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detailBarangTable">
                            <!-- Detail barang akan dimuat di sini -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th id="detailTotal">Rp 0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="btn btn-primary" id="editBtn">Edit Pesanan</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pesanan <strong id="deleteNama"></strong>?</p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Routes untuk aksi tombol - DIPERBAIKI
const routes = {
    edit: '{{ url("pesanan") }}/',  
    destroy: '{{ url("pesanan") }}/',
    confirmDelete: '{{ url("pesanan") }}/'
};

// Data pesanan dari controller
let sampleData = @json($pesanan ?? []);

let currentPage = 1;
let itemsPerPage = 10;
let filteredData = [...sampleData];

function formatRupiah(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-secondary">Pending</span>',
        'diproses': '<span class="badge bg-warning text-dark">Diproses</span>',
        'selesai': '<span class="badge bg-success">Selesai</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

function renderTable(data, page = 1) {
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = data.slice(startIndex, endIndex);

    const tbody = document.getElementById('pesananTable');
    tbody.innerHTML = '';

    if (pageData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    Tidak ada data pesanan ditemukan
                </td>
            </tr>
        `;
        return;
    }

    pageData.forEach(pesanan => {
        const row = `
            <tr>
                <td>${pesanan.id}</td>
                <td>${pesanan.nama_pembeli}</td>
                <td>${pesanan.no_hp}</td>
                <td>${new Date(pesanan.tanggal).toLocaleDateString('id-ID')}</td>
                <td>${formatRupiah(pesanan.total_harga)}</td>
                <td>${getStatusBadge(pesanan.status)}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ url('pesanan') }}/${pesanan.id}" class="btn btn-outline-primary" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="${routes.edit}${pesanan.id}/edit" class="btn btn-outline-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-outline-danger" onclick="confirmDelete(${pesanan.id}, '${pesanan.nama_pembeli}')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });

    // Update info
    document.getElementById('showingFrom').textContent = startIndex + 1;
    document.getElementById('showingTo').textContent = Math.min(endIndex, data.length);
    document.getElementById('totalRecords').textContent = data.length;
}

function renderPagination(data) {
    const totalPages = Math.ceil(data.length / itemsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    if (totalPages <= 1) return;

    // Previous button
    const prevBtn = `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">Previous</a>
    </li>`;
    pagination.innerHTML += prevBtn;

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        const pageBtn = `<li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
        </li>`;
        pagination.innerHTML += pageBtn;
    }

    // Next button
    const nextBtn = `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">Next</a>
    </li>`;
    pagination.innerHTML += nextBtn;
}

function changePage(page) {
    currentPage = page;
    renderTable(filteredData, currentPage);
    renderPagination(filteredData);
    return false;
}

function filterData() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;

    filteredData = sampleData.filter(pesanan => {
        const matchesSearch = pesanan.nama_pembeli.toLowerCase().includes(searchTerm) ||
                             pesanan.no_hp.includes(searchTerm) ||
                             pesanan.alamat.toLowerCase().includes(searchTerm);
        const matchesStatus = !statusFilter || pesanan.status === statusFilter;
        return matchesSearch && matchesStatus;
    });

    currentPage = 1;
    renderTable(filteredData, currentPage);
    renderPagination(filteredData);
}

function showDetail(id) {
    const pesanan = sampleData.find(p => p.id === id);
    if (!pesanan) return;

    document.getElementById('detailId').textContent = pesanan.id;
    document.getElementById('detailNama').textContent = pesanan.nama_pembeli;
    document.getElementById('detailHp').textContent = pesanan.no_hp;
    document.getElementById('detailTanggal').textContent = new Date(pesanan.tanggal).toLocaleDateString('id-ID');
    document.getElementById('detailStatus').innerHTML = getStatusBadge(pesanan.status);
    document.getElementById('detailAlamat').textContent = pesanan.alamat;
    document.getElementById('detailTotal').textContent = formatRupiah(pesanan.total_harga);
    document.getElementById('editBtn').href = `${routes.edit}${pesanan.id}/edit`;

    const tbody = document.getElementById('detailBarangTable');
    tbody.innerHTML = '';
    pesanan.details.forEach(detail => {
        const row = `
            <tr>
                <td>${detail.nama_barang}</td>
                <td>${detail.jumlah}</td>
                <td>${formatRupiah(detail.harga)}</td>
                <td>${formatRupiah(detail.subtotal)}</td>
            </tr>
        `;
        tbody.innerHTML += row;
    });

    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
}

function confirmDelete(id, nama) {
    // Redirect to hapus confirmation page
    window.location.href = `${routes.confirmDelete}${id}/hapus`;
}

// Event listeners
document.getElementById('searchInput').addEventListener('input', filterData);
document.getElementById('statusFilter').addEventListener('change', filterData);

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderTable(filteredData, currentPage);
    renderPagination(filteredData);
});
</script>
@endpush