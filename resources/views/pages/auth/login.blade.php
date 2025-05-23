@extends('layouts.auth-layout')

@section('title', 'Login')
@section('auth-content')
    <div class="loginbox">
        <div class="login-left py-4">
            <img class="img-fluid" src="/assets/img/smkn7.png" alt="Logo" style="max-width: 25%; height: auto;">
        </div>
        <div class="login-right">
            <div class="login-right-wrap">
                <h1 class="mb-4">Selamat Datang</h1>

                {{-- <p class="account-subtitle">Belum punya akun? <a href="{{ route('auth.register') }}">Registrasi</a></p> --}}
                <h2>Login</h2>

                @if ($errors->any())
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            @foreach ($errors->all() as $error)
                                toastr.error('{{ $error }}');
                            @endforeach
                        });
                    </script>
                @endif
                <form id="login-form" class="pristine" method="POST" action="{{ route('auth.login.submit') }}" novalidate>
                    @csrf
                    <div class="form-group">
                        <label style="z-index: 20;">Username <span class="login-danger ">*</span></label>
                        <div class="position-relative">
                            <span class="position-absolute"
                                style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 10;">
                                <i class="fas fa-user-circle"></i>
                            </span>
                            <input required class="form-control" autofocus value="{{ old('username') }}" id="username"
                                name="username" type="text" autocomplete="username" minlength="3" maxlength="255"
                                style="font-size: .8rem; padding-left: 35px;">
                        </div>
                        <div class="invalid-feedback username-error"></div>
                    </div>
                    <div class="form-group">
                        <label style="z-index: 20;">Password <span class="login-danger">*</span></label>
                        <div class="position-relative">
                            <span class="position-absolute"
                                style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 10;">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input required class="form-control pass-input" id="password" name="password" type="password"
                                autocomplete="current-password" minlength="8" maxlength="255"
                                style="font-size: .8rem; padding-left: 35px;">
                            <div class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);">
                                <span class="feather-eye-off toggle-password" style="cursor: pointer; z-index: 20;"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback password-error"></div>
                    </div>
                    <div class="forgotpass">
                        <div class="remember-me">
                            <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Remember me
                                <input type="checkbox" name="remember" id="remember" value="true">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" id="login-button" type="submit">Login</button>
                    </div>
                    <noscript>
                        <p class="text-center mt-3 text-danger">Please enable JavaScript for the best experience.</p>
                    </noscript>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- <script src="/assets/js/security-helper.js"></script> --}}
    {{-- <script src="/assets/js/form-validation.js"></script> --}}
    {{-- <script src="/assets/js/login.js"></script>     --}}
@endpush
