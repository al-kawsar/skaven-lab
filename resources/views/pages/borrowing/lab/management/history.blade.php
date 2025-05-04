@extends('layouts.app-layout')

@section('title', 'History Peminjaman Lab')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h4 class="card-title mb-2 mb-md-0">
                    <i class="fas fa-history me-2"></i> History Aktivitas Peminjaman Lab
                </h4>
                <div class="d-flex">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('borrowing.lab.admin.index') }}" class="btn btn-outline-secondary btn-sm ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="collapse" id="filterCollapse">
                <div class="card-body border-bottom bg-light">
                    <form id="filter-form" class="row g-3">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="filter-status" class="form-label">Status</label>
                            <select id="filter-status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="menunggu">Menunggu</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                                <option value="digunakan">Digunakan</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="filter-start-date" class="form-label">Tanggal Mulai</label>
                            <input type="date" id="filter-start-date" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="filter-end-date" class="form-label">Tanggal Akhir</label>
                            <input type="date" id="filter-end-date" class="form-control form-control-sm">
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="filter-user" class="form-label">Peminjam</label>
                            <select id="filter-user" class="form-select form-select-sm">
                                <option value="">Semua Peminjam</option>
                                <!-- Users will be loaded via AJAX -->
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" id="btn-reset-filter" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-search me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table star-student table-hover align-middle mb-0" id="history-table">
                        <thead class="student-thread">
                            <tr>
                                <th>Waktu</th>
                                <th>Kode</th>
                                <th>Peminjam</th>
                                <th>Aktivitas</th>
                                <th>Oleh</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody id="history-table-body">
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <div id="loading-indicator" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>

                <div id="empty-data" class="text-center py-5 d-none">
                    <img src="{{ asset('assets/images/empty-state.svg') }}" alt="No data" class="mb-3"
                        style="max-width: 200px">
                    <h5>Tidak ada data history</h5>
                    <p class="text-muted">Tidak ada aktivitas peminjaman yang ditemukan dengan filter yang dipilih</p>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <label for="items-per-page" class="me-2 text-nowrap">Tampilkan:</label>
                        <select id="items-per-page" class="form-select form-select-sm" style="width: 70px">
                            <option value="15">15</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <nav aria-label="History pagination">
                        <ul class="pagination pagination-sm mb-0" id="pagination-container">
                            <!-- Pagination will be loaded here -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Aktivitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Informasi Aktivitas</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Waktu</th>
                                        <td id="modal-timestamp"></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td id="modal-status"></td>
                                    </tr>
                                    <tr>
                                        <th>Catatan</th>
                                        <td id="modal-notes"></td>
                                    </tr>
                                    <tr>
                                        <th>Oleh</th>
                                        <td id="modal-user"></td>
                                    </tr>
                                </table>

                                <div id="modal-metadata-container" class="mt-3">
                                    <h6 class="fw-bold">Metadata</h6>
                                    <div class="card bg-light">
                                        <div class="card-body py-2">
                                            <div><strong>IP:</strong> <span id="modal-ip"></span></div>
                                            <div><strong>User Agent:</strong> <span id="modal-user-agent"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold">Informasi Peminjaman</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Kode</th>
                                        <td id="modal-borrow-code"></td>
                                    </tr>
                                    <tr>
                                        <th>Acara</th>
                                        <td id="modal-event"></td>
                                    </tr>
                                    <tr>
                                        <th>Peminjam</th>
                                        <td id="modal-borrower"></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td id="modal-date"></td>
                                    </tr>
                                    <tr>
                                        <th>Waktu</th>
                                        <td id="modal-time"></td>
                                    </tr>
                                </table>
                                <div class="d-grid">
                                    <a href="#" id="modal-detail-link" class="btn btn-sm btn-outline-primary"
                                        target="_blank">
                                        <i class="fas fa-external-link-alt me-1"></i> Lihat Detail Peminjaman
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .history-item-status {
            width: 100px;
        }

        .table td {
            vertical-align: middle;
        }

        /* Status icons */
        .status-icon-menunggu::before {
            content: '\f017';
            font-family: 'Font Awesome 5 Free';
            margin-right: 4px;
        }

        .status-icon-disetujui::before {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-right: 4px;
        }

        .status-icon-ditolak::before {
            content: '\f00d';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-right: 4px;
        }

        .status-icon-selesai::before {
            content: '\f560';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-right: 4px;
        }

        .status-icon-dibatalkan::before {
            content: '\f05e';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-right: 4px;
        }

        .status-icon-kadaluarsa::before {
            content: '\f017';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-right: 4px;
        }

        .status-icon-digunakan::before {
            content: '\f04b';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-right: 4px;
        }

        #filter-form .form-label {
            font-size: 14px;
            font-weight: 500;
        }

        @media (max-width: 767.98px) {
            .table-responsive {
                border-top: 1px solid #dee2e6;
            }

            .pagination {
                justify-content: center;
                margin-top: 1rem;
            }

            .history-item-status {
                width: auto;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            // Variables
            let currentPage = 1;
            let perPage = 15;
            let totalPages = 0;
            let filters = {
                status: '',
                start_date: '',
                end_date: '',
                user_id: ''
            };

            // Initial load
            loadUsers();
            loadHistoryData();

            // Event listeners
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                filters.status = $('#filter-status').val();
                filters.start_date = $('#filter-start-date').val();
                filters.end_date = $('#filter-end-date').val();
                filters.user_id = $('#filter-user').val();
                currentPage = 1;
                loadHistoryData();
            });

            $('#btn-reset-filter').on('click', function() {
                $('#filter-status').val('');
                $('#filter-start-date').val('');
                $('#filter-end-date').val('');
                $('#filter-user').val('');
                filters = {
                    status: '',
                    start_date: '',
                    end_date: '',
                    user_id: ''
                };
                currentPage = 1;
                loadHistoryData();
            });

            $('#items-per-page').on('change', function() {
                perPage = $(this).val();
                currentPage = 1;
                loadHistoryData();
            });

            $(document).on('click', '.view-detail', function() {
                const historyId = $(this).data('id');
                const data = $(this).data('history');
                populateModal(data);
                $('#detailModal').modal('show');
            });

            // Functions to load data
            function loadHistoryData() {
                showLoading(true);

                $.ajax({
                    url: "{{ route('borrowing.lab.admin.history.data') }}",
                    type: 'GET',
                    data: {
                        page: currentPage,
                        per_page: perPage,
                        status: filters.status,
                        start_date: filters.start_date,
                        end_date: filters.end_date,
                        user_id: filters.user_id
                    },
                    success: function(response) {
                        renderHistoryTable(response.data);
                        renderPagination(response.pagination);
                        showLoading(false);
                    },
                    error: function(xhr) {
                        console.error('Error loading history data', xhr);
                        showLoading(false);

                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data history. Silakan coba lagi nanti.'
                        });
                    }
                });
            }

            function loadUsers() {
                $.ajax({
                    url: "{{ route('api.users') }}",
                    type: 'GET',
                    success: function(response) {
                        const userSelect = $('#filter-user');
                        userSelect.empty();
                        userSelect.append('<option value="">Semua Peminjam</option>');

                        response.data.forEach(user => {
                            userSelect.append(
                                `<option value="${user.id}">${user.name}</option>`);
                        });
                    },
                    error: function(xhr) {
                        console.error('Error loading users', xhr);
                    }
                });
            }

            // Functions to render UI
            function renderHistoryTable(data) {
                const tableBody = $('#history-table-body');
                tableBody.empty();

                if (data.data.length === 0) {
                    $('#empty-data').removeClass('d-none');
                    $('#history-table').addClass('d-none');
                    return;
                }

                $('#empty-data').addClass('d-none');
                $('#history-table').removeClass('d-none');

                data.data.forEach(item => {
                    const datetime = new Date(item.created_at);
                    const formattedDate = datetime.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                    const formattedTime = datetime.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const borrowDate = new Date(item.borrowing.borrow_date);
                    const formattedBorrowDate = borrowDate.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });

                    const row = `
                    <tr>
                        <td>
                            <div>${formattedDate}</div>
                            <small class="text-muted">${formattedTime}</small>
                        </td>
                        <td>
                            <span class="badge badge-soft-primary">${item.borrowing.borrow_code}</span>
                        </td>
                        <td>
                            <div>${item.borrowing.user.name}</div>
                            <small class="text-muted">${item.borrowing.user.email}</small>
                        </td>
                        <td>
                            <div>${item.notes}</div>
                            <small class="text-muted">
                                <i class="far fa-calendar-alt me-1"></i>${formattedBorrowDate}
                            </small>
                        </td>
                        <td>
                            ${item.user ? item.user.name : '<span class="text-muted">System</span>'}
                        </td>
                        <td class="history-item-status">
                            <span class="badge badge-soft-primary status-${item.status} status-icon-${item.status}">
                                ${capitalizeFirstLetter(item.status)}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-info view-detail" 
                                    data-id="${item.id}" data-history='${JSON.stringify(item)}'>
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;

                    tableBody.append(row);
                });
            }

            function renderPagination(pagination) {
                const container = $('#pagination-container');
                container.empty();

                totalPages = pagination.last_page;

                if (totalPages <= 1) {
                    return;
                }

                // Previous button
                container.append(`
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `);

                // Page numbers
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, startPage + 4);

                if (endPage - startPage < 4) {
                    startPage = Math.max(1, endPage - 4);
                }

                for (let i = startPage; i <= endPage; i++) {
                    container.append(`
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
                }

                // Next button
                container.append(`
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `);

                // Add event listeners
                $('.page-link').on('click', function(e) {
                    e.preventDefault();
                    const newPage = $(this).data('page');

                    if (newPage >= 1 && newPage <= totalPages) {
                        currentPage = newPage;
                        loadHistoryData();
                    }
                });
            }

            function populateModal(data) {
                // History data
                $('#modal-timestamp').text(formatDateTime(data.created_at));
                $('#modal-status').html(
                    `<span class="badge status-${data.status}">${capitalizeFirstLetter(data.status)}</span>`);
                $('#modal-notes').text(data.notes);
                $('#modal-user').text(data.user ? data.user.name : 'System');

                // Metadata
                if (data.metadata) {
                    $('#modal-metadata-container').show();
                    $('#modal-ip').text(data.metadata.ip || 'N/A');
                    $('#modal-user-agent').text(data.metadata.user_agent || 'N/A');
                } else {
                    $('#modal-metadata-container').hide();
                }

                // Borrowing data
                $('#modal-borrow-code').text(data.borrowing.borrow_code);
                $('#modal-event').text(data.borrowing.event);
                $('#modal-borrower').text(data.borrowing.user.name);
                $('#modal-date').text(formatDate(data.borrowing.borrow_date));
                $('#modal-time').text(
                    `${data.borrowing.start_time.substr(0, 5)} - ${data.borrowing.end_time.substr(0, 5)}`);

                // Link to detail
                $('#modal-detail-link').attr('href', `{{ url('borrowing/lab') }}/${data.borrowing.id}/detail`);
            }

            // Helper functions
            function showLoading(show) {
                if (show) {
                    $('#loading-indicator').removeClass('d-none');
                    $('#history-table').addClass('d-none');
                    $('#empty-data').addClass('d-none');
                } else {
                    $('#loading-indicator').addClass('d-none');
                }
            }

            function formatDateTime(datetimeStr) {
                const datetime = new Date(datetimeStr);
                return datetime.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }

            function formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                    weekday: 'long'
                });
            }

            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        });
    </script>
@endpush
