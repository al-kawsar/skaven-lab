@extends('layouts.auth-layout')

@section('title', 'Login')
@section('auth-content')
    <div class="loginbox">
        <div class="login-left">
            <img class="img-fluid" src="/assets/img/login.png" alt="Logo">
        </div>
        <div class="login-right">
            <div class="login-right-wrap">
                <h1>Welcome to Lab Management</h1>

                <p class="account-subtitle">Belum punya akun? <a href="{{ route('auth.register') }}">Registrasi</a></p>
                <h2>Sign in</h2>

                <form action="{{ route('auth.doLogin') }}" method="POST">
                    @method('POST')
                    @csrf
                    <div class="form-group">


                        <label>Username <span class="login-danger">*</span></label>
                        <input required class="form-control" name="username" type="text">
                        <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                    </div>
                    <div class="form-group">
                        <label>Password <span class="login-danger">*</span></label>
                        <input required class="form-control pass-input" name="password" type="password">
                        <span class="profile-views feather-eye-off toggle-password"></span>
                    </div>
                    <div class="forgotpass">
                        <div class="remember-me">
                            <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Remember me
                                <input type="checkbox" name="remember" value="true">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" type="submit">Login</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
