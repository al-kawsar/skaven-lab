@extends('layouts.app-layout')

@section('title', 'Riwayat Aktivitas Peminjaman')

@section('content')
    <div class="container-fluid">
        <!-- Header mobile-friendly -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <nav aria-label="breadcrumb" class="mb-2 mb-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('borrowing.lab.index') }}">Peminjaman</a></li>
                    <li class="breadcrumb-item active">Riwayat</li>
                </ol>
            </nav>
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Tab Navigation untuk Mobile -->
        <div class="d-md-none mb-3">
            <ul class="nav nav-pills nav-justified" id="historyTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-content"
                        type="button" role="tab" aria-controls="details-content" aria-selected="true">
                        <i class="fas fa-info-circle me-1"></i> Detail
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline-content"
                        type="button" role="tab" aria-controls="timeline-content" aria-selected="false">
                        <i class="fas fa-history me-1"></i> Timeline
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content untuk Mobile -->
        <div class="tab-content d-md-none" id="historyTabContent">
            <!-- Tab Detail Peminjaman -->
            <div class="tab-pane fade show active" id="details-content" role="tabpanel" aria-labelledby="details-tab"
                tabindex="0">
                <!-- Card Detail Mobile -->
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <!-- Header Info -->
                        <div class="text-center mb-3">
                            <span class="badge badge-soft-primary px-3 py-2 fs-6">{{ $borrowing->borrow_code }}</span>
                            <h5 class="mt-2 mb-1">{{ $borrowing->event }}</h5>

                            @php
                                $statusColors = [
                                    'menunggu' => 'warning',
                                    'disetujui' => 'success',
                                    'ditolak' => 'danger',
                                    'selesai' => 'secondary',
                                    'dibatalkan' => 'danger',
                                    'kadaluarsa' => 'dark',
                                    'digunakan' => 'info',
                                ];
                                $statusIcons = [
                                    'menunggu' => 'clock',
                                    'disetujui' => 'check-circle',
                                    'ditolak' => 'times-circle',
                                    'selesai' => 'check-double',
                                    'dibatalkan' => 'ban',
                                    'kadaluarsa' => 'calendar-times',
                                    'digunakan' => 'play-circle',
                                ];
                                $color = $statusColors[$borrowing->status] ?? 'secondary';
                                $icon = $statusIcons[$borrowing->status] ?? 'question-circle';
                            @endphp

                            <span class="badge bg-{{ $color }} px-2 py-1">
                                <i class="fas fa-{{ $icon }} me-1"></i>
                                {{ ucfirst($borrowing->status) }}
                            </span>
                        </div>

                        <!-- Detail Info dengan ikon -->
                        <div class="detail-list">
                            <div class="info-item mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user text-primary me-3"></i>
                                    <span class="text-muted">Peminjam</span>
                                </div>
                                <p class="ms-4 ps-2">{{ $borrowing->user->name }}</p>
                            </div>

                            <div class="info-item mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-flask text-primary me-3"></i>
                                    <span class="text-muted">Laboratorium</span>
                                </div>
                                <p class="ms-4 ps-2">{{ settings('lab_name') }}</p>
                            </div>

                            <div class="info-item mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-day text-primary me-3"></i>
                                    <span class="text-muted">Tanggal</span>
                                </div>
                                <p class="ms-4 ps-2">{{ \App\Helpers\DateHelper::formatLong($borrowing->borrow_date) }}</p>
                            </div>

                            <div class="info-item mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-primary me-3"></i>
                                    <span class="text-muted">Waktu</span>
                                </div>
                                <p class="ms-4 ps-2">{{ substr($borrowing->start_time, 0, 5) }} -
                                    {{ substr($borrowing->end_time, 0, 5) }}</p>
                            </div>

                            @if ($borrowing->notes)
                                <div class="info-item mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-sticky-note text-primary me-3"></i>
                                        <span class="text-muted">Catatan</span>
                                    </div>
                                    <div class="ms-4 ps-2">
                                        <div class="border rounded p-3 bg-light">
                                            {{ $borrowing->notes }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="info-item">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-plus text-primary me-3"></i>
                                    <span class="text-muted">Dibuat pada</span>
                                </div>
                                <p class="ms-4 ps-2">
                                    {{ $borrowing->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
                            </div>
                        </div>

                        <!-- Tombol aksi untuk mobile -->
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('borrowing.lab.print', $borrowing->id) }}"
                                class="btn btn-sm btn-outline-primary w-50 me-1" target="_blank">
                                <i class="fas fa-print me-1"></i> Cetak
                            </a>
                            <a href="{{ route('borrowing.lab.show', $borrowing->id) }}"
                                class="btn btn-sm btn-outline-info w-50 ms-1">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Timeline untuk Mobile -->
            <div class="tab-pane fade" id="timeline-content" role="tabpanel" aria-labelledby="timeline-tab" tabindex="0">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="timeline-container p-3">
                            @if (count($histories) > 0)
                                <ul class="timeline mobile-timeline">
                                    @foreach ($histories as $history)
                                        <li class="timeline-item">
                                            @php
                                                $color = $statusColors[$history['status']] ?? 'secondary';
                                            @endphp

                                            <div class="timeline-badge bg-{{ $color }}">
                                                <i class="fas fa-circle"></i>
                                            </div>

                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span
                                                        class="badge bg-{{ $color }}">{{ ucfirst($history['status']) }}</span>
                                                    <small class="text-muted">{{ $history['time_ago'] }}</small>
                                                </div>

                                                <p class="mb-1 small w-50 pe-4">{{ $history['notes'] }}</p>

                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i> {{ $history['user'] }}
                                                    </small>
                                                </div>

                                                @if (isset($history['metadata']) && !empty($history['metadata']))
                                                    <a class="btn btn-sm btn-link p-0 mt-1" data-bs-toggle="collapse"
                                                        href="#mobile-metadata-{{ $history['id'] }}">
                                                        <small>Lihat metadata</small>
                                                    </a>
                                                    <div class="collapse" id="mobile-metadata-{{ $history['id'] }}">
                                                        <div class="mt-2 p-2 border rounded bg-light small">
                                                            <div><strong>IP:</strong>
                                                                {{ $history['metadata']['ip'] ?? 'N/A' }}</div>
                                                            <div><strong>Browser:</strong>
                                                                {{ $history['metadata']['user_agent'] ?? 'N/A' }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                    <p>Belum ada aktivitas yang tercatat.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Layout (tetap seperti sebelumnya, disembunyikan pada mobile) -->
        <div class="row d-none d-md-flex">
            <!-- Panel Kiri: Informasi Peminjaman -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold text-uppercase"><i class="fas fa-info-circle me-2"></i> Detail Peminjaman
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <span class="badge badge-soft-primary fs-5 p-2 mb-2">{{ $borrowing->borrow_code }}</span>
                            <h4 class="mt-2">{{ $borrowing->event }}</h4>
                            <span class="badge badge-{{ $color }}">
                                <i class="fas fa-{{ $icon }} me-1"></i>
                                {{ ucfirst($borrowing->status) }}
                            </span>
                        </div>

                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-user text-primary me-3"></i>
                                <span class="text-muted">Peminjam</span>
                            </div>
                            <p class="ms-4 ps-2">{{ $borrowing->user->name }}</p>
                        </div>

                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-flask text-primary me-3"></i>
                                <span class="text-muted">Laboratorium</span>
                            </div>
                            <p class="ms-4 ps-2">{{ settings('lab_name') }}</p>
                        </div>

                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-day text-primary me-3"></i>
                                <span class="text-muted">Tanggal</span>
                            </div>
                            <p class="ms-4 ps-2">{{ \App\Helpers\DateHelper::formatLong($borrowing->borrow_date) }}</p>
                        </div>

                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock text-primary me-3"></i>
                                <span class="text-muted">Waktu</span>
                            </div>
                            <p class="ms-4 ps-2">{{ substr($borrowing->start_time, 0, 5) }} -
                                {{ substr($borrowing->end_time, 0, 5) }}</p>
                        </div>

                        @if ($borrowing->notes)
                            <div class="info-item mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-sticky-note text-primary me-3"></i>
                                    <span class="text-muted">Catatan</span>
                                </div>
                                <div class="ms-4 ps-2">
                                    <div class="border rounded p-3 bg-light">
                                        {{ $borrowing->notes }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="info-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-plus text-primary me-3"></i>
                                <span class="text-muted">Dibuat pada</span>
                            </div>
                            <p class="ms-4 ps-2">
                                {{ $borrowing->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
                        </div>

                        <!-- Tombol aksi -->
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <a href="{{ route('borrowing.lab.print', $borrowing->id) }}" class="btn btn-outline-primary"
                                target="_blank">
                                <i class="fas fa-print me-1"></i> Cetak
                            </a>
                            <a href="{{ route('borrowing.lab.show', $borrowing->id) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Kanan: Timeline Aktivitas -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold text-uppercase"><i class="fas fa-history me-2"></i> Timeline Aktivitas
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="timeline-container p-4">
                            @if (count($histories) > 0)
                                <ul class="timeline modern-timeline">
                                    @foreach ($histories as $history)
                                        <li class="timeline-item">
                                            @php
                                                $color = $statusColors[$history['status']] ?? 'secondary';
                                            @endphp

                                            <div class="timeline-badge bg-{{ $color }}">
                                                <i class="fas fa-circle"></i>
                                            </div>

                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span
                                                        class="badge bg-{{ $color }}">{{ ucfirst($history['status']) }}</span>
                                                    <small class="text-muted">{{ $history['time_ago'] }}</small>
                                                </div>

                                                <p class="mb-1">{{ $history['notes'] }}</p>

                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i> {{ $history['user'] }}
                                                    </small>
                                                    <small class="text-muted">{{ $history['timestamp'] }}</small>
                                                </div>

                                                @if (isset($history['metadata']) && !empty($history['metadata']))
                                                    <div class="collapse" id="metadata-{{ $history['id'] }}">
                                                        <div class="mt-2 p-2 border rounded bg-light">
                                                            <small>
                                                                <strong>IP:</strong>
                                                                {{ $history['metadata']['ip'] ?? 'N/A' }}<br>
                                                                <strong>Browser:</strong>
                                                                {{ $history['metadata']['user_agent'] ?? 'N/A' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-sm btn-link p-0 mt-1" data-bs-toggle="collapse"
                                                        href="#metadata-{{ $history['id'] }}" role="button">
                                                        <small>Lihat metadata</small>
                                                    </a>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <p>Belum ada aktivitas yang tercatat.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Timeline styling untuk desktop */
        .timeline {
            list-style-type: none;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #dee2e6;
            left: 20px;
            margin-left: -1.5px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 60px;
        }

        .timeline-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: white;
            position: absolute;
            left: 0;
            top: 0;
            z-index: 1;
        }

        .timeline-badge i {
            font-size: 14px;
        }

        .timeline-content {
            padding: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Info items */
        .info-item {
            border-bottom: 1px dashed #e0e0e0;
            padding-bottom: 12px;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        /* Mobile styles */
        @media (max-width: 767.98px) {

            /* Detail list untuk mobile */
            .detail-list {
                padding: 0;
            }

            .detail-item {
                display: flex;
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 1px solid #eee;
            }

            .detail-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .detail-icon {
                width: 36px;
                height: 36px;
                background-color: #f0f5ff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 12px;
                flex-shrink: 0;
            }

            .detail-icon i {
                color: #3c7efa;
                font-size: 16px;
            }

            .detail-content {
                flex: 1;
            }

            .detail-label {
                color: #666;
                font-size: 12px;
                margin-bottom: 2px;
            }

            .detail-value {
                font-size: 14px;
            }

            .notes-box {
                background-color: #f8f9fa;
                padding: 8px;
                border-radius: 4px;
                border: 1px solid #e9ecef;
            }

            /* Mobile timeline */
            .mobile-timeline:before {
                left: 16px;
            }

            .mobile-timeline .timeline-item {
                padding-left: 45px;
                margin-bottom: 20px;
            }

            .mobile-timeline .timeline-badge {
                width: 32px;
                height: 32px;
                line-height: 32px;
            }

            .mobile-timeline .timeline-badge i {
                font-size: 12px;
            }

            .mobile-timeline .timeline-content {
                padding: 12px;
                font-size: 0.9rem;
            }

            /* Tab Pills styling */
            .nav-pills .nav-link {
                border-radius: 4px;
                font-size: 14px;
                padding: 8px 0;
            }

            .nav-pills .nav-link.active {
                background-color: #3c7efa;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        // Script untuk menangani tab di tampilan mobile
        document.addEventListener('DOMContentLoaded', function() {
            // Deteksi apakah ada parameter hash di URL untuk mengatur tab aktif
            if (window.location.hash === '#timeline') {
                const timelineTab = document.getElementById('timeline-tab');
                if (timelineTab) {
                    timelineTab.click();
                }
            }

            // Tambahkan hash ke URL ketika tab diganti untuk mempertahankan state
            const tabLinks = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tabLinks.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(e) {
                    const targetId = e.target.getAttribute('id');
                    if (targetId === 'timeline-tab') {
                        history.replaceState(null, null, '#timeline');
                    } else {
                        history.replaceState(null, null, '#details');
                    }
                });
            });
        });
    </script>
@endpush
