@extends('layouts.app-layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ajukan Ulang Peminjaman {{$labData['name']}}</h5>
                    <a href="{{ route('borrowing.lab.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <!-- Informasi Penolakan Sebelumnya -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-1"></i> Informasi Pengajuan Sebelumnya</h6>
                        <p class="mb-0">Pengajuan peminjaman sebelumnya ditolak dengan alasan:</p>
                        <p class="mb-0 mt-2 p-2 bg-light rounded">{{ $previousData['rejection_reason'] ?? 'Tidak ada alasan yang diberikan' }}</p>
                    </div>
                    
                    <form id="labBorrowingForm">
                        <input type="hidden" name="reference_id" value="{{ $previousData['reference_id'] }}">
                        
                        <div class="mb-3">
                            <label for="borrow_date" class="form-label">Tanggal Peminjaman</label>
                            <input type="date" class="form-control" id="borrow_date" name="borrow_date" value="{{ $previousData['borrow_date'] }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_time" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $previousData['start_time'] }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_time" class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" value="{{ $previousData['end_time'] }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="event" class="form-label">Kegiatan</label>
                            <input type="text" class="form-control" id="event" name="event" value="{{ $previousData['event'] }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ $previousData['notes'] }}</textarea>
                            <div class="form-text">Berikan keterangan tambahan jika diperlukan.</div>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">Batal</button>
                            <button type="submit" class="btn btn-primary">Ajukan Ulang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form submission
    $('#labBorrowingForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('borrowing.lab.resubmit.store', $labData['id']) }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                // Disable button and show loading
                $('button[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    confirmButtonText: 'Lihat Daftar Peminjaman'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('borrowing.lab.index') }}";
                    }
                });
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan pada sistem';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: errorMessage
                });
                
                // Re-enable button
                $('button[type="submit"]').prop('disabled', false).text('Ajukan Ulang');
            }
        });
    });
});
</script>
@endpush 