@extends('layouts.app-layout')

@section('title', 'Log Aktivitas')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Log Aktivitas Sistem</h3>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('settings.security') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Pengaturan
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('settings.security.logs') }}" method="GET" class="row">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Dari Tanggal</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Sampai Tanggal</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Tipe Aktivitas</label>
                                    <select name="activity_type" class="form-select">
                                        <option value="">Semua Aktivitas</option>
                                        <option value="login" {{ request('activity_type') == 'login' ? 'selected' : '' }}>
                                            Login</option>
                                        <option value="logout" {{ request('activity_type') == 'logout' ? 'selected' : '' }}>
                                            Logout</option>
                                        <option value="failed_login"
                                            {{ request('activity_type') == 'failed_login' ? 'selected' : '' }}>Gagal Login
                                        </option>
                                        <option value="password_change"
                                            {{ request('activity_type') == 'password_change' ? 'selected' : '' }}>Ganti
                                            Password</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Pengguna</label>
                                    <input type="text" name="user" placeholder="Email atau Nama" class="form-control"
                                        value="{{ request('user') }}">
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-primary me-2">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                        <a href="{{ route('settings.security.logs') }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-sync-alt me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal & Waktu</th>
                                    <th>Aktivitas</th>
                                    <th>Pengguna</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Log data akan ditampilkan di sini -->
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-info-circle me-1 text-muted"></i> Belum ada data log aktivitas
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <!-- Pagination akan ditampilkan di sini jika ada data -->
                    </div>

                    <div class="mt-3 text-center text-muted small">
                        <p>Log keamanan disimpan selama 90 hari.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
