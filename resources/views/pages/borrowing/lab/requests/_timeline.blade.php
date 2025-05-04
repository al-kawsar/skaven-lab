<div class="borrow-timeline">
    <!-- Pengajuan Peminjaman -->
    <div class="timeline-item">
        <div class="timeline-marker"></div>
        <div class="timeline-content">
            <h3 class="timeline-title">
                <i class="fas fa-paper-plane me-2"></i> Pengajuan
            </h3>
            <p class="timeline-date">{{ $borrow->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
            <p class="timeline-user">
                <i class="fas fa-user me-1"></i> {{ $borrow->user->name ?? 'User tidak ditemukan' }}
            </p>
        </div>
    </div>

    <!-- Proses Admin (Ditolak / Disetujui) -->
    @if (in_array($borrow->status, ['disetujui', 'ditolak', 'selesai', 'dibatalkan']))
        <!-- Ditolak / Disetujui -->
        @if ($borrow->status == 'disetujui' || $borrow->status == 'selesai')
            <div class="timeline-item">
                <div class="timeline-marker timeline-marker-success"></div>
                <div class="timeline-content">
                    <h3 class="timeline-title">
                        <i class="fas fa-check-circle me-2"></i> Disetujui
                    </h3>
                    <p class="timeline-date">
                        {{ $borrow->updated_at->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
                    @if (isset($borrow->admin) && $borrow->admin)
                        <p class="timeline-admin">
                            <i class="fas fa-user-shield me-1"></i> {{ $borrow->admin->name ?? 'Admin' }}
                        </p>
                    @endif
                    @if ($borrow->notes)
                        <div class="timeline-notes">
                            <i class="fas fa-comment-dots me-1"></i> {{ $borrow->notes }}
                        </div>
                    @endif
                </div>
            </div>
        @elseif($borrow->status == 'ditolak')
            <div class="timeline-item">
                <div class="timeline-marker timeline-marker-danger"></div>
                <div class="timeline-content">
                    <h3 class="timeline-title">
                        <i class="fas fa-times-circle me-2"></i> Ditolak
                    </h3>
                    <p class="timeline-date">
                        {{ $borrow->updated_at->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
                    @if (isset($borrow->admin) && $borrow->admin)
                        <p class="timeline-admin">
                            <i class="fas fa-user-shield me-1"></i> {{ $borrow->admin->name ?? 'Admin' }}
                        </p>
                    @endif
                    @if ($borrow->notes)
                        <div class="timeline-notes">
                            <i class="fas fa-comment-dots me-1"></i> <strong>Alasan Penolakan:</strong>
                            {{ $borrow->notes }}
                        </div>
                    @endif
                </div>
            </div>
        @elseif($borrow->status == 'dibatalkan')
            <div class="timeline-item">
                <div class="timeline-marker timeline-marker-warning"></div>
                <div class="timeline-content">
                    <h3 class="timeline-title">
                        <i class="fas fa-ban me-2"></i> Dibatalkan
                    </h3>
                    <p class="timeline-date">
                        {{ $borrow->updated_at->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
                    <p class="timeline-user">
                        <i class="fas fa-user me-1"></i>
                        {{ $borrow->user->name ?? 'User tidak ditemukan' }}
                    </p>
                    @if ($borrow->notes)
                        <div class="timeline-notes">
                            <i class="fas fa-comment-dots me-1"></i> {{ $borrow->notes }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif

    <!-- Peminjaman Aktif (hanya tampil jika status disetujui) -->
    @if ($borrow->status == 'disetujui')
        <div class="timeline-item">
            <div class="timeline-marker timeline-marker-active"></div>
            <div class="timeline-content">
                <h3 class="timeline-title">
                    <i class="fas fa-clock me-2"></i> Peminjaman Aktif
                </h3>
                <p class="timeline-date">
                    {{ \Carbon\Carbon::parse($borrow->borrow_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </p>
                <div class="timeline-time">
                    <span>{{ date('H:i', strtotime($borrow->start_time)) }}</span>
                    <span class="time-divider"></span>
                    <span>{{ date('H:i', strtotime($borrow->end_time)) }}</span>
                </div>

                @php
                    $now = \Carbon\Carbon::now();
                    $startTime = \Carbon\Carbon::parse($borrow->borrow_date . ' ' . $borrow->start_time);
                    $endTime = \Carbon\Carbon::parse($borrow->borrow_date . ' ' . $borrow->end_time);

                    // Handle jika end_time lebih kecil dari start_time (melewati tengah malam)
                    if ($endTime < $startTime) {
                        $endTime->addDay();
                    }

                    $isPending = $now < $startTime;
                    $isOngoing = $now >= $startTime && $now <= $endTime;
                    $isExpired = $now > $endTime;
                @endphp

                <!-- Status waktu (menunggu, berlangsung, atau selesai) -->
                <p class="timeline-status">
                    @if ($isPending)
                        <i class="fas fa-hourglass me-1"></i> Menunggu waktu mulai
                    @elseif($isOngoing)
                        <i class="fas fa-play-circle me-1"></i> Sedang berlangsung
                    @elseif($isExpired)
                        <i class="fas fa-check-circle me-1"></i> Waktu telah berakhir
                    @endif
                </p>
            </div>
        </div>
    @endif

    <!-- Peminjaman Selesai -->
    @if ($borrow->status == 'selesai')
        <div class="timeline-item">
            <div class="timeline-marker timeline-marker-secondary"></div>
            <div class="timeline-content">
                <h3 class="timeline-title">
                    <i class="fas fa-check-double me-2"></i> Selesai
                </h3>
                <p class="timeline-date">
                    {{ \Carbon\Carbon::parse($borrow->borrow_date . ' ' . $borrow->end_time)->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm') }}
                </p>
                <div class="timeline-duration">
                    <span>Durasi:</span>
                    <span>
                        @php
                            $startTime = \Carbon\Carbon::parse($borrow->start_time);
                            $endTime = \Carbon\Carbon::parse($borrow->end_time);

                            // Handle jika end_time lebih kecil dari start_time (melewati tengah malam)
                            if ($endTime < $startTime) {
                                $endTime->addDay();
                            }

                            $duration = $startTime->diff($endTime);
                            $durationText = [];

                            if ($duration->h > 0) {
                                $durationText[] = $duration->h . ' jam';
                            }
                            if ($duration->i > 0) {
                                $durationText[] = $duration->i . ' menit';
                            }

                            echo implode(' ', $durationText);
                        @endphp
                    </span>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="timeline">
    @foreach ($borrow->histories()->orderBy('created_at', 'asc')->get() as $history)
        <div class="timeline-item">
            <div
                class="timeline-point {{ $history->status === 'ditolak'
                    ? 'bg-danger'
                    : ($history->status === 'disetujui'
                        ? 'bg-success'
                        : ($history->status === 'selesai'
                            ? 'bg-secondary'
                            : 'bg-primary')) }}">
                @if ($history->status === 'ditolak')
                    <i class="fas fa-times text-white"></i>
                @elseif ($history->status === 'disetujui')
                    <i class="fas fa-check text-white"></i>
                @elseif ($history->status === 'selesai')
                    <i class="fas fa-check-double text-white"></i>
                @elseif ($history->status === 'dibatalkan')
                    <i class="fas fa-ban text-white"></i>
                @elseif ($history->status === 'menunggu')
                    <i class="fas fa-clock text-white"></i>
                @else
                    <i class="fas fa-circle text-white"></i>
                @endif
            </div>
            <div class="timeline-content">
                <h6 class="mb-1">
                    @if ($history->status === 'menunggu')
                        Menunggu Persetujuan
                    @elseif ($history->status === 'disetujui')
                        Disetujui
                    @elseif ($history->status === 'ditolak')
                        Ditolak
                    @elseif ($history->status === 'selesai')
                        Selesai
                    @elseif ($history->status === 'dibatalkan')
                        Dibatalkan
                    @elseif ($history->status === 'kadaluarsa')
                        Kadaluarsa
                    @else
                        {{ ucfirst($history->status) }}
                    @endif
                </h6>
                <p class="mb-0 text-muted">
                    {{ $history->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm:ss') }}</p>
                @if ($history->notes)
                    <div class="mt-2 p-2 bg-light rounded">
                        {{ $history->notes }}
                    </div>
                @endif
                <small class="text-muted">Oleh: {{ $history->user ? $history->user->name : 'Sistem' }}</small>
            </div>
        </div>
    @endforeach
</div>

@php
    function getStatusColorClass($status)
    {
        switch ($status) {
            case 'disetujui':
                return 'bg-success';
            case 'menunggu':
                return 'bg-warning';
            case 'ditolak':
                return 'bg-danger';
            case 'selesai':
                return 'bg-secondary';
            case 'dibatalkan':
                return 'bg-danger bg-opacity-50';
            case 'digunakan':
                return 'bg-info';
            case 'kadaluarsa':
                return 'bg-dark';
            default:
                return 'bg-secondary';
        }
    }

    function getStatusIcon($status)
    {
        switch ($status) {
            case 'disetujui':
                return 'check-circle';
            case 'menunggu':
                return 'clock';
            case 'ditolak':
                return 'times-circle';
            case 'selesai':
                return 'check-double';
            case 'dibatalkan':
                return 'ban';
            case 'digunakan':
                return 'sync-alt';
            case 'kadaluarsa':
                return 'calendar-times';
            default:
                return 'question-circle';
        }
    }

    function getBadgeClass($status)
    {
        switch ($status) {
            case 'disetujui':
                return 'bg-success';
            case 'menunggu':
                return 'bg-warning';
            case 'ditolak':
                return 'bg-danger';
            case 'selesai':
                return 'bg-secondary';
            case 'dibatalkan':
                return 'bg-danger bg-opacity-50';
            case 'digunakan':
                return 'bg-info';
            case 'kadaluarsa':
                return 'bg-dark';
            default:
                return 'bg-secondary';
        }
    }
@endphp

<!-- CSS untuk Timeline -->
<style>
    .borrow-timeline {
        position: relative;
        padding-left: 30px;
        margin-bottom: 20px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 25px;
        padding-bottom: 5px;
    }

    .timeline-item:before {
        content: "";
        position: absolute;
        left: -30px;
        top: 20px;
        bottom: -25px;
        width: 2px;
        background-color: #dee2e6;
        z-index: 0;
    }

    .timeline-item:last-child:before {
        display: none;
    }

    .timeline-marker {
        position: absolute;
        left: -39px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #6c757d;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
        z-index: 1;
    }

    .timeline-marker-success {
        background-color: #28a745;
    }

    .timeline-marker-danger {
        background-color: #dc3545;
    }

    .timeline-marker-warning {
        background-color: #fd7e14;
    }

    .timeline-marker-active {
        background-color: #007bff;
    }

    .timeline-marker-secondary {
        background-color: #6c757d;
    }

    .timeline-content {
        padding: 15px 20px;
        border-radius: 5px;
        background-color: #f9fafb;
        border-left: 4px solid #dee2e6;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .timeline-title {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #212529;
    }

    .timeline-date {
        margin: 5px 0 0;
        font-size: 14px;
        color: #6c757d;
    }

    .timeline-user,
    .timeline-admin {
        margin: 5px 0 0;
        color: #495057;
        font-size: 14px;
    }

    .timeline-notes {
        margin-top: 10px;
        padding: 10px;
        background-color: #f8f9fa;
        border-left: 3px solid #dee2e6;
        color: #495057;
        font-size: 14px;
        line-height: 1.5;
    }

    .timeline-time {
        display: flex;
        align-items: center;
        margin-top: 8px;
        font-weight: 500;
    }

    .timeline-time span:first-child {
        color: #007bff;
    }

    .timeline-time span:last-child {
        color: #6c757d;
    }

    .time-divider {
        position: relative;
        height: 1px;
        width: 30px;
        background: #dee2e6;
        margin: 0 10px;
    }

    .time-divider:after {
        content: "→";
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        color: #adb5bd;
    }

    .timeline-status {
        margin-top: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #495057;
    }

    .timeline-duration {
        display: flex;
        margin-top: 8px;
        gap: 10px;
        font-size: 14px;
        color: #495057;
    }

    .timeline-duration span:first-child {
        color: #6c757d;
    }

    .timeline-duration span:last-child {
        font-weight: 500;
    }

    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }

    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: 2px;
    }

    .timeline-item {
        position: relative;
        margin-right: 10px;
        margin-bottom: 20px;
    }

    .timeline-item:last-of-type {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        top: 0;
        left: 18px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        color: white;
        z-index: 10;
    }

    .timeline-content {
        position: relative;
        margin-left: 60px;
        background: #f8f9fa;
        border-radius: 4px;
        padding: 15px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    }

    .timeline-title {
        margin-top: 0;
        font-size: 18px;
    }

    .timeline-subtitle {
        margin: 2px 0 10px 0;
        font-size: 14px;
        color: #6c757d;
    }

    .timeline-info {
        padding: 10px;
        background: #fff;
        border-radius: 4px;
        border-left: 3px solid #ddd;
    }

    @media (max-width: 576px) {
        .borrow-timeline {
            padding-left: 25px;
        }

        .timeline-marker {
            left: -32px;
            width: 15px;
            height: 15px;
        }

        .timeline-item:before {
            left: -25px;
        }

        .timeline-content {
            padding: 10px 15px;
        }

        .timeline-title {
            font-size: 15px;
        }

        .timeline-date,
        .timeline-user,
        .timeline-admin,
        .timeline-notes {
            font-size: 13px;
        }
    }
</style>
