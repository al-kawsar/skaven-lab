@extends('layouts.app-layout')

@section('title', 'Daftar Peminjaman Lab')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0 mb-md-0">Daftar Peminjaman Lab</h5>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            <!-- Toggle View Buttons (hidden on mobile) -->
                            <div class="btn-group d-none d-md-flex" role="group" aria-label="Tampilan">
                                <button type="button" class="btn btn-outline-primary" id="table-view-btn">
                                    <i class="fas fa-table me-1"></i> <span class="d-none d-lg-inline">Tabel</span>
                                </button>
                                <button type="button" class="btn btn-outline-primary active" id="card-view-btn">
                                    <i class="fas fa-th-large me-1"></i> <span class="d-none d-lg-inline">Kartu</span>
                                </button>
                            </div>

                            <a href="{{ route('borrowing.lab.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-1"></i> <span class="d-none d-md-inline">Ajukan
                                    Peminjaman</span>
                                <span class="d-inline d-md-none">Ajukan</span>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Filter Section (Collapsible on Mobile) -->
                        <div class="mb-3 d-md-none">
                            <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="collapse"
                                data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="fas fa-filter me-1"></i> Filter & Pencarian
                            </button>
                        </div>

                        <div class="collapse d-md-block" id="filterCollapse">
                            <div class="row mb-4">
                                <div class="col-md-10">
                                    <div class="row g-2">
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label for="status-filter" class="form-label">Status</label>
                                                <select class="form-control form-control-md" id="status-filter">
                                                    <option value="">Semua Status</option>
                                                    <option value="menunggu">Menunggu</option>
                                                    <option value="disetujui">Disetujui</option>
                                                    <option value="ditolak">Ditolak</option>
                                                    <option value="dibatalkan">Dibatalkan</option>
                                                    <option value="selesai">Selesai</option>
                                                    <option value="kadaluarsa">Kadaluarsa</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label for="date-start" class="form-label">Dari Tanggal</label>
                                                <input type="date" class="form-control form-control-md" id="date-start">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label for="date-end" class="form-label">Sampai Tanggal</label>
                                                <input type="date" class="form-control form-control-md" id="date-end">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label d-md-block">&nbsp;</label>
                                            <button id="filter-btn" class="btn btn-primary w-100">
                                                <i class="fas fa-filter me-1"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mt-2 mt-md-0">
                                    <label class="form-label d-none d-md-block">&nbsp;</label>
                                    <button id="reset-filter-btn" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Table View (Hidden on mobile by default) -->
                        <div id="table-view" class="d-none">
                            <div class="table-responsive">
                                <table id="borrowing-table" class="table border-0 star-student table-center mb-0 w-100">
                                    <thead class="student-thread">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Kegiatan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Card View (Default for all devices) -->
                        <div id="card-view">
                            <!-- Quick Filters for Mobile -->
                            <div class="d-md-none mb-3 status-quick-filters">
                                <div class="d-flex gap-1 overflow-auto pb-2">
                                    <button class="btn btn-sm btn-outline-secondary active" data-status="">Semua</button>
                                    <button class="btn btn-sm btn-outline-warning" data-status="menunggu">Menunggu</button>
                                    <button class="btn btn-sm btn-outline-success"
                                        data-status="disetujui">Disetujui</button>
                                    <button class="btn btn-sm btn-outline-danger" data-status="ditolak">Ditolak</button>
                                    <button class="btn btn-sm btn-outline-secondary" data-status="selesai">Selesai</button>
                                </div>
                            </div>

                            <div class="row" id="borrowing-cards">
                                <!-- Cards will be loaded via AJAX -->
                            </div>

                            <!-- Empty state for card view -->
                            <div id="card-empty-state" class="text-center py-4 d-none">
                                <img src="{{ asset('assets/images/empty-state.svg') }}" alt="No data"
                                    class="img-fluid mb-3" style="max-height: 120px">
                                <h5>Belum Ada Peminjaman</h5>
                                <p class="text-muted">Belum ada peminjaman lab yang diajukan.</p>
                                <a href="{{ route('borrowing.lab.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-1"></i> Ajukan Peminjaman
                                </a>
                            </div>
                        </div>

                        <!-- Loader -->
                        <div id="loader" class="text-center py-5 d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat data...</p>
                        </div>

                        <!-- Empty state for table view -->
                        <div id="table-empty-state" class="text-center py-5 d-none">
                            <img src="{{ asset('assets/images/empty-state.svg') }}" alt="No data" class="img-fluid mb-3"
                                style="max-height: 120px">
                            <h5>Belum Ada Peminjaman</h5>
                            <p class="text-muted">Belum ada peminjaman lab yang diajukan.</p>
                            <a href="{{ route('borrowing.lab.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-1"></i> Ajukan Peminjaman
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile-Optimized Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-4" id="modal-loader">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="detail-content" class="d-none">
                        <!-- Mobile View -->
                        <div class="d-block d-md-none">
                            <div class="text-center mb-3">
                                <span class="badge badge-soft-primary px-3 py-2 fs-6" id="mobile-detail-code"></span>
                                <h4 class="mt-2" id="mobile-detail-event"></h4>
                                <div id="mobile-status-badge" class="mt-2"></div>
                            </div>

                            <div class="info-card mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-day text-primary me-3"></i>
                                    <span class="text-muted">Tanggal:</span>
                                </div>
                                <p class="ms-4 ps-2" id="mobile-detail-date"></p>
                            </div>

                            <div class="info-card mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-primary me-3"></i>
                                    <span class="text-muted">Waktu:</span>
                                </div>
                                <p class="ms-4 ps-2" id="mobile-detail-time"></p>
                            </div>

                            <div class="info-card mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-info-circle text-primary me-3"></i>
                                    <span class="text-muted">Catatan:</span>
                                </div>
                                <p class="ms-4 ps-2" id="mobile-detail-notes"></p>
                            </div>

                            <div class="info-card mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-history text-primary me-3"></i>
                                    <span class="text-muted">Diajukan pada:</span>
                                </div>
                                <p class="ms-4 ps-2" id="mobile-detail-created"></p>
                            </div>

                            <!-- Recurring info mobile -->
                            <div id="mobile-recurring-info" class="mb-3 d-none">
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-sync-alt me-2"></i>
                                        <strong>Peminjaman Berulang</strong>
                                    </div>
                                    <div id="mobile-recurring-details" class="ps-4"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop View -->
                        <div class="d-none d-md-block">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge rounded-pill badge-soft-primary me-2">Kode</span>
                                        <span id="detail-code"></span>
                                    </div>
                                    <h5 id="detail-event" class="mb-3"></h5>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar-day text-primary me-2"></i>
                                        <span id="detail-date"></span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <span id="detail-time"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="status-badge-container" class="mb-3"></div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        <span>Catatan:</span>
                                    </div>
                                    <p id="detail-notes" class="ps-4 mb-3 small"></p>

                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-history text-primary me-2"></i>
                                        <span>Diajukan pada:</span>
                                    </div>
                                    <p id="detail-created" class="ps-4 mb-0 small"></p>
                                </div>
                            </div>

                            <!-- Recurring info if applicable -->
                            <div id="recurring-info" class="mb-3 d-none">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-sync-alt me-2"></i> Peminjaman Berulang</h6>
                                    <div id="recurring-details"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline (same for both views with optimized styling) -->
                        <div class="border-top pt-3 mb-3">
                            <h6 class="mb-3"><i class="fas fa-history me-2"></i> Timeline</h6>
                            <div id="timeline-container" class="timeline-mobile"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-wrap">
                    <div id="detail-actions" class="d-flex gap-2 w-100 justify-content-between flex-wrap">
                        <div class="mb-2 mb-md-0">
                            <a href="#" id="detail-history-btn" class="btn btn-outline-secondary">
                                <i class="fas fa-history me-1"></i> <span class="d-none d-md-inline">Riwayat
                                    Lengkap</span>
                                <span class="d-inline d-md-none">Riwayat</span>
                            </a>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <a href="#" id="detail-print-btn" class="btn btn-outline-primary" target="_blank">
                                <i class="fas fa-print me-1"></i> <span class="d-none d-md-inline">Cetak</span>
                            </a>
                            <button type="button" id="detail-cancel-btn" class="btn btn-danger d-none">
                                <i class="fas fa-times me-1"></i> <span class="d-none d-md-inline">Batalkan</span>
                                <span class="d-inline d-md-none">Batal</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile-Optimized Cancel Confirmation Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="cancel-recurring-warning" class="alert alert-warning d-none mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i> Ini adalah peminjaman berulang. Apakah Anda ingin
                        membatalkan seluruh jadwal terkait?
                    </div>
                    <form id="cancel-form">
                        <input type="hidden" id="cancel-id">
                        <input type="hidden" id="cancel-all-recurring" value="0">
                        <div class="mb-3">
                            <label for="cancel-reason" class="form-label">Alasan Pembatalan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="cancel-reason" rows="3" required></textarea>
                            <div class="invalid-feedback">Alasan pembatalan wajib diisi</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex-wrap">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirm-cancel-btn" class="btn btn-danger">Konfirmasi Pembatalan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Variables
            let borrowingData = [];
            // Default view - card for mobile, keep user preference for desktop
            let currentView = isMobile() ? 'card' : (localStorage.getItem('borrowingView') || 'card');
            let selectedBorrowingId = null;

            // Check if it's a mobile device
            function isMobile() {
                return window.innerWidth < 768;
            }

            // Set initial view based on device
            if (isMobile()) {
                $('#table-view-btn').removeClass('active');
                $('#card-view-btn').addClass('active');
                $('#table-view').addClass('d-none');
                $('#card-view').removeClass('d-none');
            } else {
                // For desktop, set view based on saved preference
                if (currentView === 'table') {
                    $('#card-view-btn').removeClass('active');
                    $('#table-view-btn').addClass('active');
                    $('#card-view').addClass('d-none');
                    $('#table-view').removeClass('d-none');
                } else {
                    $('#table-view-btn').removeClass('active');
                    $('#card-view-btn').addClass('active');
                    $('#table-view').addClass('d-none');
                    $('#card-view').removeClass('d-none');
                }
            }

            // Initialize datatable
            const table = $('#borrowing-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('borrowing.lab.data') }}",
                    data: function(d) {
                        d.status = $('#status-filter').val();
                        d.start_date = $('#date-start').val();
                        d.end_date = $('#date-end').val();
                    }
                },
                columns: [
                    {
                        data: 'borrow_code',
                        name: 'borrow_code',
                        render: function(data) {
                            return `<span class="badge badge-soft-primary">${data}</span>`;
                        }
                    },
                    {
                        data: 'borrow_date',
                        name: 'borrow_date'
                    },
                    {
                        data: null,
                        name: 'time',
                        render: function(data) {
                            return `${formatTime(data.start_time)} - ${formatTime(data.end_time)}`;
                        }
                    },
                    {
                        data: 'event',
                        name: 'event',
                        render: function(data) {
                            return truncateText(data, 30);
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            const status = getStatusBadge(data);
                            return `<span class="badge ${status.class}">${status.text}</span>`;
                        }
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        render: function(data) {
                            let buttons = `
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary view-detail" data-id="${data.id}">
                                        <i class="fas fa-eye"></i>
                                    </button>`;

                            if (data.status === 'menunggu' || data.status === 'disetujui') {
                                buttons += `
                                    <button type="button" class="btn btn-sm btn-danger cancel-booking" data-id="${data.id}">
                                        <i class="fas fa-times"></i>
                                    </button>`;
                            }

                            buttons += `</div>`;
                            return buttons;
                        }
                    }
                ],
                order: [
                    [2, 'desc']
                ],
                responsive: true,
                language: {
                    url: "cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });

            // Initial data load
            if (currentView === 'card') {
                loadCardView();
            }

            // Toggle view buttons
            $('#table-view-btn').click(function() {
                if (!isMobile()) { // Only change view on non-mobile
                    $('#card-view-btn').removeClass('active');
                    $(this).addClass('active');
                    $('#card-view').addClass('d-none');
                    $('#table-view').removeClass('d-none');
                    currentView = 'table';
                    localStorage.setItem('borrowingView', 'table');
                    table.ajax.reload();
                }
            });

            $('#card-view-btn').click(function() {
                $('#table-view-btn').removeClass('active');
                $(this).addClass('active');
                $('#table-view').addClass('d-none');
                $('#card-view').removeClass('d-none');
                currentView = 'card';
                localStorage.setItem('borrowingView', 'card');
                loadCardView();
            });

            // Filter button
            $('#filter-btn').click(function() {
                if (currentView === 'table') {
                    table.ajax.reload();
                } else {
                    loadCardView();
                }

                // Close the collapse on mobile after filtering
                if (isMobile()) {
                    $('#filterCollapse').collapse('hide');
                }
            });

            // Reset filter button
            $('#reset-filter-btn').click(function() {
                $('#status-filter').val('');
                $('#date-start').val('');
                $('#date-end').val('');
                if (currentView === 'table') {
                    table.ajax.reload();
                } else {
                    loadCardView();
                }

                // Reset quick filter buttons active state on mobile
                if (isMobile()) {
                    $('.status-quick-filters button').removeClass('active');
                    $('.status-quick-filters button[data-status=""]').addClass('active');
                    $('#filterCollapse').collapse('hide');
                }
            });

            // Quick filters for mobile
            $('.status-quick-filters button').click(function() {
                $('.status-quick-filters button').removeClass('active');
                $(this).addClass('active');

                const status = $(this).data('status');
                $('#status-filter').val(status);
                loadCardView();
            });

            // Load card view data
            function loadCardView() {
                showLoader();
                $.ajax({
                    url: "{{ route('borrowing.lab.filter') }}",
                    type: "GET",
                    data: {
                        status: $('#status-filter').val(),
                        start_date: $('#date-start').val(),
                        end_date: $('#date-end').val()
                    },
                    success: function(response) {
                        borrowingData = response.data;
                        renderCardView(borrowingData);
                        hideLoader();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading card view:", error);
                        toastr.error("Gagal memuat data peminjaman");
                        hideLoader();
                        showEmptyState();
                    }
                });
            }

            // Render card view
            function renderCardView(data) {
                $('#borrowing-cards').empty();

                if (data.length === 0) {
                    showEmptyState();
                    return;
                }

                hideEmptyState();
                data.forEach(function(item) {
                    const cardHtml = createBorrowingCard(item);
                    $('#borrowing-cards').append(cardHtml);
                });

                attachCardEventListeners();
            }

            // Create a card for a borrowing item
            function createBorrowingCard(item) {
                const statusBadge = getStatusBadge(item.status);
                const canCancel = item.status === 'menunggu' || item.status === 'disetujui';
                const cardClasses = isMobile() ? 'col-12' : 'col-md-6 col-lg-4';

                // Simplified card for mobile
                if (isMobile()) {
                    return `
                        <div class="${cardClasses} mb-3">
                            <div class="card borrowing-card" data-id="${item.id}">
                                <div class="card-body pb-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title text-truncate mb-3" title="${item.event}">${item.event}</h5>
                                        <span class="badge ${statusBadge.class}">${statusBadge.text}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-calendar-day text-primary me-2"></i>
                                                <span class="small">${item.borrow_date}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-primary me-2"></i>
                                                <span class="small">${formatTime(item.start_time)} - ${formatTime(item.end_time)}</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge badge-soft-primary">${item.borrow_code}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top p-2 d-flex justify-content-between align-items-center">
                                    <button class="btn btn-sm btn-primary view-detail-card" data-id="${item.id}">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                    ${canCancel ? `
                                                                                <button class="btn btn-sm btn-danger cancel-booking-card" data-id="${item.id}">
                                                                                    <i class="fas fa-times me-1"></i> Batal
                                                                                </button>
                                                                                ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                }

                // Full card for desktop
                return `
                    <div class="${cardClasses} mb-4">
                        <div class="card h-100 border-${getStatusColor(item.status)} borrowing-card" data-id="${item.id}">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <span class="badge badge-soft-primary">${item.borrow_code}</span>
                                <span class="badge ${statusBadge.class}">${statusBadge.text}</span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-truncate" title="${item.event}">${item.event}</h5>
                                <div class="d-flex mb-2">
                                                                      <div class="me-3">
                                        <i class="fas fa-calendar-day text-primary me-2"></i>
                                    </div>
                                    <div>
                                        ${item.borrow_date}
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                    </div>
                                    <div>
                                        ${formatTime(item.start_time)} - ${formatTime(item.end_time)}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                                <button class="btn btn-sm btn-primary view-detail-card" data-id="${item.id}">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </button>
                                ${canCancel ? `
                                                                            <button class="btn btn-sm btn-danger cancel-booking-card" data-id="${item.id}">
                                                                                <i class="fas fa-times me-1"></i> Batalkan
                                                                            </button>
                                                                            ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }

            // Attach event listeners to cards
            function attachCardEventListeners() {
                $('.view-detail-card').on('click', function(e) {
                    e.stopPropagation();
                    const id = $(this).data('id');
                    openDetailModal(id);
                });

                $('.cancel-booking-card').on('click', function(e) {
                    e.stopPropagation();
                    const id = $(this).data('id');
                    openCancelModal(id);
                });

                // Make entire card clickable for details
                $('.borrowing-card').on('click', function(e) {
                    // Only trigger if not clicking buttons
                    if (!$(e.target).closest('button').length) {
                        const id = $(this).data('id');
                        openDetailModal(id);
                    }
                });
            }

            // Table events
            $('#borrowing-table').on('click', '.view-detail', function() {
                const id = $(this).data('id');
                openDetailModal(id);
            });

            $('#borrowing-table').on('click', '.cancel-booking', function() {
                const id = $(this).data('id');
                openCancelModal(id);
            });

            // Open detail modal
            function openDetailModal(id) {
                selectedBorrowingId = id;
                $('#detailModal').modal('show');
                $('#detail-content').addClass('d-none');
                $('#modal-loader').removeClass('d-none');

                $.ajax({
                    url: `{{ url('borrowings/lab') }}/${id}/detail`,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        const data = response.data;

                        // Populate desktop modal
                        $('#detail-code').text(data.borrow_code);
                        $('#detail-event').text(data.event);
                        $('#detail-date').text(data.borrow_date);
                        $('#detail-time').text(
                            `${formatTime(data.start_time)} - ${formatTime(data.end_time)}`);
                        $('#detail-notes').text(data.notes || 'Tidak ada catatan');
                        $('#detail-created').text(data.created_at);

                        // Populate mobile modal
                        $('#mobile-detail-code').text(data.borrow_code);
                        $('#mobile-detail-event').text(data.event);
                        $('#mobile-detail-date').text(data.borrow_date);
                        $('#mobile-detail-time').text(
                            `${formatTime(data.start_time)} - ${formatTime(data.end_time)}`);
                        $('#mobile-detail-notes').text(data.notes || 'Tidak ada catatan');
                        $('#mobile-detail-created').text(data.created_at);

                        // Status badge for desktop
                        const statusBadge = getStatusBadge(data.status);
                        $('#status-badge-container').html(`
                            <span class="badge ${statusBadge.class} fs-6">
                                <i class="${statusBadge.icon} me-1"></i>
                                ${statusBadge.text}
                            </span>
                        `);

                        // Status badge for mobile
                        $('#mobile-status-badge').html(`
                            <span class="badge ${statusBadge.class} px-3 py-2 fs-6">
                                <i class="${statusBadge.icon} me-1"></i>
                                ${statusBadge.text}
                            </span>
                        `);

                        // Timeline
                        $('#timeline-container').html(response.timelineHtml);

                        // Recursive booking info - desktop
                        if (data.recurrence) {
                            $('#recurring-info').removeClass('d-none');
                            $('#mobile-recurring-info').removeClass('d-none');

                            if (data.recurrence.is_child) {
                                // This is a child booking of a recurring series
                                const childContent = `
                                    <p class="mb-1">Bagian dari peminjaman berulang dengan kode <strong>${data.recurrence.parent_code}</strong></p>
                                    <p class="mb-1">Jadwal ke-${data.recurrence.instance_number} dari ${data.recurrence.total_instances} jadwal</p>
                                    <p class="mb-0">Jadwal pertama pada tanggal ${data.recurrence.parent_date}</p>
                                `;
                                $('#recurring-details').html(childContent);
                                $('#mobile-recurring-details').html(childContent);
                            } else {
                                // This is a parent/main booking
                                const parentContent = `
                                    <p class="mb-1">Tipe perulangan: <strong>${data.recurrence.label}</strong></p>
                                    <p class="mb-1">Total jadwal: <strong>${data.recurrence.total_instances} jadwal</strong></p>
                                    ${data.recurrence.ends_at ? `<p class="mb-0">Berakhir pada: <strong>${data.recurrence.ends_at}</strong></p>` : ''}
                                `;

                                // Add button for desktop view
                                const desktopContent = `
                                    ${parentContent}
                                    <a href="#" class="view-all-instances btn btn-sm btn-outline-info mt-2" data-id="${data.id}">
                                        <i class="fas fa-list me-1"></i> Lihat Semua Jadwal
                                    </a>
                                `;

                                // Simplified for mobile view
                                const mobileContent = `
                                    ${parentContent}
                                    <a href="#" class="view-all-instances btn btn-sm btn-outline-info mt-2" data-id="${data.id}">
                                        <i class="fas fa-list me-1"></i> Lihat Jadwal
                                    </a>
                                `;

                                $('#recurring-details').html(desktopContent);
                                $('#mobile-recurring-details').html(mobileContent);
                            }
                        } else {
                            $('#recurring-info').addClass('d-none');
                            $('#mobile-recurring-info').addClass('d-none');
                        }

                        // Action buttons
                        $('#detail-print-btn').attr('href', `{{ url('borrowings/lab') }}/${id}/print`);
                        $('#detail-history-btn').attr('href',
                            `{{ url('borrowings/lab') }}/${id}/history`);

                        // Show cancel button if applicable
                        if (data.status === 'menunggu' || data.status === 'disetujui') {
                            $('#detail-cancel-btn').removeClass('d-none');
                        } else {
                            $('#detail-cancel-btn').addClass('d-none');
                        }

                        // Show content, hide loader
                        $('#modal-loader').addClass('d-none');
                        $('#detail-content').removeClass('d-none');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading details:", error);
                        $('#modal-loader').addClass('d-none');
                        $('#detail-content').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Gagal memuat detail peminjaman
                            </div>
                        `).removeClass('d-none');
                    }
                });
            }

            // View all instances of recurring booking
            $(document).on('click', '.view-all-instances', function(e) {
                e.preventDefault();
                const id = $(this).data('id');

                // Show a modal or navigate to a page with all instances
                // For simplicity, we'll just show an alert
                alert("Fitur ini akan menampilkan semua jadwal peminjaman berulang");

                // Ideal implementation would be:
                // 1. Fetch all instances via AJAX
                // 2. Display them in a modal or navigate to a dedicated page
            });

            // Detail cancel button
            $('#detail-cancel-btn').click(function() {
                $('#detailModal').modal('hide');
                openCancelModal(selectedBorrowingId);
            });

            // Open cancel modal
            function openCancelModal(id) {
                $('#cancel-id').val(id);
                $('#cancel-reason').val('');
                $('#cancel-all-recurring').val('0');
                $('#cancel-recurring-warning').addClass('d-none');
                $('#cancelModal').modal('show');

                // Check if this is a recurring booking
                const booking = borrowingData.find(b => b.id === id);
                if (booking && booking.is_recurring) {
                    $('#cancel-recurring-warning').removeClass('d-none');
                }
            }

            // Confirm cancel
            $('#confirm-cancel-btn').click(function() {
                const id = $('#cancel-id').val();
                const reason = $('#cancel-reason').val();
                const cancelAllRecurring = $('#cancel-all-recurring').val() === '1';

                if (!reason) {
                    $('#cancel-reason').addClass('is-invalid');
                    return;
                }

                $('#cancel-reason').removeClass('is-invalid');
                $('#confirm-cancel-btn').prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

                $.ajax({
                    url: `{{ url('borrowings/lab') }}/${id}/cancel`,
                    type: "PUT",
                    data: {
                        reason: reason,
                        cancel_all_confirmed: cancelAllRecurring,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(response) {
                        // Check if we need to confirm for recurring bookings
                        if (response.is_recurring) {
                            $('#cancel-all-recurring').val('1');
                            $('#cancel-recurring-warning').removeClass('d-none').html(`
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Perhatian!</strong> Ini adalah peminjaman berulang. Konfirmasi sekali lagi untuk membatalkan seluruh jadwal terkait.
                            `);
                            $('#confirm-cancel-btn').prop('disabled', false).html(
                                'Konfirmasi Pembatalan Semua Jadwal');
                            return;
                        }

                        // Success - regular booking or confirmed recurring
                        $('#cancelModal').modal('hide');
                        toastr.success(response.message);

                        if (currentView === 'table') {
                            table.ajax.reload();
                        } else {
                            loadCardView();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error canceling booking:", error);
                        toastr.error(xhr.responseJSON?.message ||
                            "Gagal membatalkan peminjaman");
                    },
                    complete: function() {
                        $('#confirm-cancel-btn').prop('disabled', false).html(
                            'Konfirmasi Pembatalan');
                    }
                });
            });

            // Helper functions
            function getStatusBadge(status) {
                let badgeClass = 'bg-secondary';
                let icon = 'fas fa-question-circle';
                let text = 'Unknown';

                switch (status) {
                    case 'menunggu':
                        badgeClass = 'bg-warning';
                        icon = 'fas fa-clock';
                        text = 'Menunggu';
                        break;
                    case 'disetujui':
                        badgeClass = 'bg-success';
                        icon = 'fas fa-check-circle';
                        text = 'Disetujui';
                        break;
                    case 'ditolak':
                        badgeClass = 'bg-danger';
                        icon = 'fas fa-times-circle';
                        text = 'Ditolak';
                        break;
                    case 'selesai':
                        badgeClass = 'bg-secondary';
                        icon = 'fas fa-check-double';
                        text = 'Selesai';
                        break;
                    case 'dibatalkan':
                        badgeClass = 'bg-danger bg-opacity-50';
                        icon = 'fas fa-ban';
                        text = 'Dibatalkan';
                        break;
                    case 'kadaluarsa':
                        badgeClass = 'bg-dark';
                        icon = 'fas fa-calendar-times';
                        text = 'Kadaluarsa';
                        break;
                    case 'digunakan':
                        badgeClass = 'bg-info';
                        icon = 'fas fa-play-circle';
                        text = 'Digunakan';
                        break;
                }

                return {
                    class: badgeClass,
                    icon: icon,
                    text: text
                };
            }

            function getStatusColor(status) {
                switch (status) {
                    case 'menunggu':
                        return 'warning';
                    case 'disetujui':
                        return 'success';
                    case 'ditolak':
                        return 'danger';
                    case 'selesai':
                        return 'secondary';
                    case 'dibatalkan':
                        return 'danger';
                    case 'kadaluarsa':
                        return 'dark';
                    case 'digunakan':
                        return 'info';
                    default:
                        return 'primary';
                }
            }

            function formatTime(timeString) {
                if (!timeString) return '';
                const timeParts = timeString.split(':');
                return `${timeParts[0]}:${timeParts[1]}`;
            }

            function truncateText(text, maxLength) {
                if (!text) return '';
                return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
            }

            function showLoader() {
                $('#loader').removeClass('d-none');
                $('#table-view, #card-view').addClass('d-none');
            }

            function hideLoader() {
                $('#loader').addClass('d-none');
                if (currentView === 'table') {
                    $('#table-view').removeClass('d-none');
                } else {
                    $('#card-view').removeClass('d-none');
                }
            }

            function showEmptyState() {
                if (currentView === 'table') {
                    $('#table-empty-state').removeClass('d-none');
                    $('#table-view').addClass('d-none');
                } else {
                    $('#card-empty-state').removeClass('d-none');
                    $('#card-view').addClass('d-none');
                }
            }

            function hideEmptyState() {
                $('#table-empty-state, #card-empty-state').addClass('d-none');
            }

            // Handle window resize to adjust view for mobile/desktop
            $(window).resize(function() {
                if (isMobile() && currentView === 'table') {
                    // Force card view on mobile
                    $('#table-view-btn').removeClass('active');
                    $('#card-view-btn').addClass('active');
                    $('#table-view').addClass('d-none');
                    $('#card-view').removeClass('d-none');
                    currentView = 'card';
                    loadCardView();
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Card hover effect */
        .borrowing-card {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .borrowing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Status quick filters */
        .status-quick-filters {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            /* Firefox */
        }

        .status-quick-filters::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        .status-quick-filters button {
            flex-shrink: 0;
        }

        /* Timeline styling - optimized for mobile */
        .timeline-mobile .timeline-item {
            position: relative;
            padding-left: 30px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .timeline-mobile .timeline-item:before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: -15px;
            width: 2px;
            background-color: #dee2e6;
        }

        .timeline-mobile .timeline-item:last-child:before {
            display: none;
        }

        .timeline-mobile .timeline-badge {
            position: absolute;
            left: 0;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            text-align: center;
            line-height: 16px;
            z-index: 1;
        }

        .timeline-mobile .timeline-content {
            padding: 10px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }

        /* Info card styling for mobile */
        .info-card {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding-bottom: 10px;
        }

        .info-card:last-child {
            border-bottom: none;
        }

        /* Media queries */
        @media (max-width: 767px) {

            /* Optimize buttons for mobile */
            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }

            /* Reduce padding for mobile cards */
            .card-body {
                padding: 1rem;
            }

            /* Full-width buttons in modal footer */
            .modal-footer .btn {
                margin-bottom: 0.25rem;
            }

            /* Condensed timeline for mobile */
            .timeline-mobile .timeline-content {
                padding: 8px;
                font-size: 0.85rem;
            }
        }
    </style>
@endpush
