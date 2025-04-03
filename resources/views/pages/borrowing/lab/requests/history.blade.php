@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Aktivitas Peminjaman</h5>
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <!-- Info peminjaman -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Peminjam</th>
                                    <td>{{ $borrowing->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Lab</th>
                                    <td>{{ $borrowing->lab->name }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ \App\Helpers\DateHelper::formatLong($borrowing->borrow_date) }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu</th>
                                    <td>{{ $borrowing->start_time }} - {{ $borrowing->end_time }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Kegiatan</th>
                                    <td>{{ $borrowing->event }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{!! $borrowing->status !!}</td>
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <td>{{ $borrowing->notes ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat Pada</th>
                                    <td>{{ $borrowing->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Timeline history -->
                    <h5>Timeline Aktivitas</h5>
                    <div class="history-container mt-3">
                        @include('pages.borrowing.lab.requests._history', [
                            'histories' => $histories,
                            'borrowing' => $borrowing
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection