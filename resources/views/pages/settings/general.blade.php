@extends('layouts.app-layout')

@section('title', 'Pengaturan Umum')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Pengaturan Umum</h3>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('settings.general.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-4 d-flex flex-column align-items-center">
                                            <div class="logo-preview mb-3">
                                                <img src="{{ asset($settings->logo ?? 'assets/img/logo.png') }}"
                                                    alt="Logo" class="img-fluid"
                                                    style="max-height: 120px; max-width: 100%;">
                                            </div>
                                            <label class="btn btn-primary btn-sm">
                                                <i class="fas fa-upload me-1"></i> Ganti Logo
                                                <input type="file" name="logo" class="d-none" id="logo-upload">
                                            </label>
                                            <small class="form-text text-muted mt-2">Format: JPG, PNG, WEBP (Maks.
                                                2MB)</small>
                                        </div>

                                        <div class="mb-4 d-flex flex-column align-items-center">
                                            <div class="favicon-preview mb-3">
                                                <img src="{{ asset($settings->favicon ?? 'assets/img/favicon.png') }}"
                                                    alt="Favicon" class="img-fluid"
                                                    style="max-height: 64px; max-width: 64px; border: 1px solid #ddd; padding: 5px;">
                                            </div>
                                            <label class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-upload me-1"></i> Ganti Favicon
                                                <input type="file" name="favicon" class="d-none" id="favicon-upload">
                                            </label>
                                            <small class="form-text text-muted mt-2">Format: PNG, ICO (Maks. 1MB)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-9 col-lg-8 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Informasi Sistem</h4>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nama Institusi <span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('institution_name') is-invalid @enderror"
                                                    name="institution_name"
                                                    value="{{ old('institution_name', $settings->institution_name ?? '') }}"
                                                    required>
                                                @error('institution_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Kode Institusi</label>
                                                <input type="text"
                                                    class="form-control @error('institution_code') is-invalid @enderror"
                                                    name="institution_code"
                                                    value="{{ old('institution_code', $settings->institution_code ?? '') }}">
                                                @error('institution_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Email Kontak <span
                                                        class="text-danger">*</span></label>
                                                <input type="email"
                                                    class="form-control @error('contact_email') is-invalid @enderror"
                                                    name="contact_email"
                                                    value="{{ old('contact_email', $settings->contact_email ?? '') }}"
                                                    required>
                                                @error('contact_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nomor Telepon</label>
                                                <input type="text"
                                                    class="form-control @error('contact_phone') is-invalid @enderror"
                                                    name="contact_phone"
                                                    value="{{ old('contact_phone', $settings->contact_phone ?? '') }}">
                                                @error('contact_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label class="form-label">Alamat</label>
                                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address', $settings->address ?? '') }}</textarea>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <h4 class="card-title mb-4 mt-4">Konfigurasi Sistem</h4>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Zona Waktu</label>
                                                <select class="form-select @error('timezone') is-invalid @enderror"
                                                    name="timezone">
                                                    <option value="Asia/Jakarta"
                                                        {{ old('timezone', $settings->timezone ?? '') == 'Asia/Jakarta' ? 'selected' : '' }}>
                                                        Asia/Jakarta (WIB)</option>
                                                    <option value="Asia/Makassar"
                                                        {{ old('timezone', $settings->timezone ?? '') == 'Asia/Makassar' ? 'selected' : '' }}>
                                                        Asia/Makassar (WITA)</option>
                                                    <option value="Asia/Jayapura"
                                                        {{ old('timezone', $settings->timezone ?? '') == 'Asia/Jayapura' ? 'selected' : '' }}>
                                                        Asia/Jayapura (WIT)</option>
                                                </select>
                                                @error('timezone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Format Tanggal</label>
                                                <select class="form-select @error('date_format') is-invalid @enderror"
                                                    name="date_format">
                                                    <option value="d/m/Y"
                                                        {{ old('date_format', $settings->date_format ?? '') == 'd/m/Y' ? 'selected' : '' }}>
                                                        DD/MM/YYYY (31/12/2023)</option>
                                                    <option value="Y-m-d"
                                                        {{ old('date_format', $settings->date_format ?? '') == 'Y-m-d' ? 'selected' : '' }}>
                                                        YYYY-MM-DD (2023-12-31)</option>
                                                    <option value="d-m-Y"
                                                        {{ old('date_format', $settings->date_format ?? '') == 'd-m-Y' ? 'selected' : '' }}>
                                                        DD-MM-YYYY (31-12-2023)</option>
                                                </select>
                                                @error('date_format')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Teks Footer</label>
                                                <input type="text"
                                                    class="form-control @error('footer_text') is-invalid @enderror"
                                                    name="footer_text"
                                                    value="{{ old('footer_text', $settings->footer_text ?? '© ' . date('Y') . ' Lab Management System') }}">
                                                @error('footer_text')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Preview untuk upload logo
            $('#logo-upload').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('.logo-preview img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Preview untuk upload favicon
            $('#favicon-upload').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('.favicon-preview img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
