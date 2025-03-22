@extends('layouts.app-layout')

@section('title', 'Pengaturan Keamanan')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Pengaturan Keamanan Sistem</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title"><i class="fas fa-lock me-2 text-primary"></i>Konfigurasi Password
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('settings.security.password') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Minimal Panjang Password</label>
                                            <div class="input-group">
                                                <input type="number"
                                                    class="form-control @error('min_password_length') is-invalid @enderror"
                                                    name="min_password_length"
                                                    value="{{ old('min_password_length', $settings->min_password_length ?? 8) }}"
                                                    min="6" max="16">
                                                <span class="input-group-text">karakter</span>
                                                @error('min_password_length')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">Direkomendasikan minimal 8 karakter untuk
                                                keamanan yang baik.</small>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="require_special_char"
                                                    id="require_special_char"
                                                    {{ old('require_special_char', $settings->require_special_char ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="require_special_char">
                                                    Wajib mengandung karakter khusus
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">Contoh karakter khusus:
                                                !@#$%^&*()-_=+</small>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="require_number"
                                                    id="require_number"
                                                    {{ old('require_number', $settings->require_number ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="require_number">
                                                    Wajib mengandung angka
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="require_uppercase"
                                                    id="require_uppercase"
                                                    {{ old('require_uppercase', $settings->require_uppercase ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="require_uppercase">
                                                    Wajib mengandung huruf besar
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Masa Berlaku Password</label>
                                            <div class="input-group">
                                                <input type="number"
                                                    class="form-control @error('password_expiry_days') is-invalid @enderror"
                                                    name="password_expiry_days"
                                                    value="{{ old('password_expiry_days', $settings->password_expiry_days ?? 90) }}"
                                                    min="0" max="365">
                                                <span class="input-group-text">hari</span>
                                                @error('password_expiry_days')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">Masukkan 0 untuk menonaktifkan fitur masa
                                                berlaku password.</small>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i> Simpan Konfigurasi Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title"><i class="fas fa-shield-alt me-2 text-primary"></i>Konfigurasi
                                        Login</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('settings.security.login') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="enable_2fa"
                                                    id="enable_2fa"
                                                    {{ old('enable_2fa', $settings->enable_2fa ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable_2fa">
                                                    Aktifkan 2FA (Two-Factor Authentication)
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">Mengaktifkan verifikasi dua langkah untuk
                                                semua pengguna sistem.</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Batas Percobaan Login</label>
                                            <div class="input-group">
                                                <input type="number"
                                                    class="form-control @error('max_login_attempts') is-invalid @enderror"
                                                    name="max_login_attempts"
                                                    value="{{ old('max_login_attempts', $settings->max_login_attempts ?? 5) }}"
                                                    min="3" max="10">
                                                <span class="input-group-text">kali</span>
                                                @error('max_login_attempts')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">Jumlah maksimal percobaan login sebelum akun
                                                dikunci sementara.</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Durasi Penguncian Akun</label>
                                            <div class="input-group">
                                                <input type="number"
                                                    class="form-control @error('lockout_duration') is-invalid @enderror"
                                                    name="lockout_duration"
                                                    value="{{ old('lockout_duration', $settings->lockout_duration ?? 30) }}"
                                                    min="5" max="1440">
                                                <span class="input-group-text">menit</span>
                                                @error('lockout_duration')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">Durasi penguncian akun setelah melebihi
                                                batas percobaan login.</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Durasi Sesi Login</label>
                                            <div class="input-group">
                                                <input type="number"
                                                    class="form-control @error('session_lifetime') is-invalid @enderror"
                                                    name="session_lifetime"
                                                    value="{{ old('session_lifetime', $settings->session_lifetime ?? 120) }}"
                                                    min="5" max="1440">
                                                <span class="input-group-text">menit</span>
                                                @error('session_lifetime')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">Berapa lama sesi login aktif sebelum harus
                                                login ulang.</small>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="force_https"
                                                    id="force_https"
                                                    {{ old('force_https', $settings->force_https ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="force_https">
                                                    Paksa penggunaan HTTPS
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">Mengalihkan semua traffic ke HTTPS untuk
                                                keamanan koneksi.</small>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i> Simpan Konfigurasi Login
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card border shadow-sm mt-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title"><i class="fas fa-history me-2 text-primary"></i>Log Keamanan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid">
                                        <a href="{{ route('settings.security.logs') }}"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-list-alt me-1"></i> Lihat Log Keamanan
                                        </a>
                                    </div>
                                    <small class="d-block text-muted mt-2">Berisi catatan aktivitas login, perubahan
                                        password, dan upaya akses tidak sah.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
