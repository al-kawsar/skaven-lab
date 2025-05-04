@if(isset($data['recurrence']) && $data['recurrence'])
    <div class="row mb-3">
        <div class="col-5 fw-bold text-muted">
            <i class="fas fa-sync-alt me-2"></i>Perulangan
        </div>
        <div class="col-7">
            @if(isset($data['recurrence']['is_child']) && $data['recurrence']['is_child'])
                <span class="badge bg-info text-white">
                    <i class="fas fa-link me-1"></i> Bagian dari Peminjaman Berulang
                </span>
                <div class="small text-muted mt-1">
                    Jadwal ke-{{ $data['recurrence']['instance_number'] }} dari {{ $data['recurrence']['total_instances'] }}
                </div>
                <div class="small">
                    <a href="#" class="view-parent-booking" data-id="{{ $data['recurrence']['parent_id'] }}">
                        Lihat jadwal utama ({{ $data['recurrence']['parent_date'] }})
                    </a>
                </div>
            @else
                <span class="badge bg-primary text-white">
                    {{ $data['recurrence']['label'] }}
                </span>
                <div class="small text-muted mt-1">
                    Total {{ $data['recurrence']['total_instances'] }} jadwal
                    @if($data['recurrence']['ends_at'])
                        sampai {{ $data['recurrence']['ends_at'] }}
                    @endif
                </div>
                @if($data['recurrence']['total_instances'] > 1)
                    <div class="small">
                        <a href="#" class="view-recurring-instances" data-id="{{ $data['id'] }}">
                            Lihat semua jadwal terkait
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endif