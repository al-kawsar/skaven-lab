<div class="borrowing-history-timeline">
    @foreach ($histories as $index => $history)
        <div class="history-item">
            <div class="history-marker history-marker-{{ $history['status'] }}"></div>
            <div class="history-content">
                <div class="d-flex justify-content-between">
                    <h4 class="history-title">
                        @switch($history['status'])
                            @case('menunggu')
                                <i class="fas fa-clock text-warning me-1"></i> Pengajuan Peminjaman
                            @break

                            @case('disetujui')
                                <i class="fas fa-check-circle text-success me-1"></i> Disetujui
                            @break

                            @case('ditolak')
                                <i class="fas fa-times-circle text-danger me-1"></i> Ditolak
                            @break

                            @case('digunakan')
                                <i class="fas fa-play-circle text-primary me-1"></i> Mulai Digunakan
                            @break

                            @case('selesai')
                                <i class="fas fa-check-double text-secondary me-1"></i> Selesai
                            @break

                            @case('dibatalkan')
                                <i class="fas fa-ban text-danger me-1"></i> Dibatalkan
                            @break

                            @case('kadaluarsa')
                                <i class="fas fa-hourglass-end text-secondary me-1"></i> Kadaluarsa
                            @break

                            @default
                                <i class="fas fa-info-circle me-1"></i> {{ ucfirst($history['status']) }}
                        @endswitch
                    </h4>
                    <small class="history-date text-muted">{{ $history['time_ago'] }}</small>
                </div>
                <p class="history-timestamp text-muted small">{{ $history['timestamp'] }}</p>

                <div class="history-user small mb-2">
                    <i class="fas fa-user me-1"></i> {{ $history['user'] }}
                </div>

                @if (!empty($history['notes']))
                    <div class="history-notes p-2 bg-light rounded">
                        {{ $history['notes'] }}
                    </div>
                @endif

                @if (!empty($history['metadata']))
                    <div class="history-metadata mt-2">
                        <a class="btn btn-sm btn-light" data-bs-toggle="collapse" href="#metadata-{{ $index }}"
                            role="button">
                            <i class="fas fa-code me-1"></i> Detail Teknis
                        </a>
                        <div class="collapse mt-2" id="metadata-{{ $index }}">
                            <div class="card card-body py-2 px-3">
                                <small class="mb-1"><i class="fas fa-globe me-1"></i> IP:
                                    {{ $history['metadata']['ip'] ?? 'Tidak tersedia' }}</small>
                                <small class="mb-1"><i class="fas fa-desktop me-1"></i> Device:
                                    @php
                                        $ua = $history['metadata']['user_agent'] ?? '';
                                        $device = preg_match('/mobile/i', $ua) ? 'Mobile' : 'Desktop';
                                        echo $device;
                                    @endphp
                                </small>
                                <small><i class="fas fa-clock me-1"></i> Timestamp:
                                    {{ $history['metadata']['timestamp'] ?? now() }}</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<style>
    .borrowing-history-timeline {
        position: relative;
        padding-left: 30px;
        margin-bottom: 20px;
    }

    .history-item {
        position: relative;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .history-item:last-child {
        border-bottom: none;
    }

    .history-item:before {
        content: "";
        position: absolute;
        left: -30px;
        top: 20px;
        bottom: -15px;
        width: 2px;
        background-color: #e9ecef;
        z-index: 0;
    }

    .history-item:last-child:before {
        display: none;
    }

    .history-marker {
        position: absolute;
        left: -39px;
        top: 5px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #0d6efd;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
        z-index: 1;
    }

    .history-marker-menunggu {
        background-color: #ffc107;
    }

    .history-marker-disetujui {
        background-color: #198754;
    }

    .history-marker-ditolak {
        background-color: #dc3545;
    }

    .history-marker-digunakan {
        background-color: #0d6efd;
    }

    .history-marker-selesai {
        background-color: #6c757d;
    }

    .history-marker-dibatalkan {
        background-color: #dc3545;
        opacity: 0.7;
    }

    .history-marker-kadaluarsa {
        background-color: #6c757d;
        opacity: 0.7;
    }

    .history-content {
        padding: 0 5px;
    }

    .history-title {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .history-timestamp,
    .history-user {
        margin: 3px 0;
    }

    .history-notes {
        margin-top: 5px;
        color: #495057;
    }
</style>
