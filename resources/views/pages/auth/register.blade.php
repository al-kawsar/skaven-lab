@extends('layouts.auth-layout')
@section('auth-content')
    <div class="loginbox">
        <div class="login-left">
            <img class="img-fluid" src="/assets/img/login.png" alt="Logo">
        </div>
        <div class="login-right">
            <div class="login-right-wrap">
                <h1>Sign Up</h1>
                <p class="account-subtitle">Enter details to create your account</p>

                <form action="login.html">
                    <div class="form-group">
                        <label>Name <span class="login-danger">*</span></label>
                        <input class="form-control" type="text">
                        <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="login-danger">*</span></label>
                        <input class="form-control" type="text">
                        <span class="profile-views"><i class="fas fa-envelope"></i></span>
                    </div>
                    <div class="form-group">
                        <label>Password <span class="login-danger">*</span></label>
                        <input class="form-control pass-input" type="password">
                        <span class="profile-views feather-eye-off toggle-password"></span>
                    </div>
                    <div class="form-group">
                        <label>Confirm password <span class="login-danger">*</span></label>
                        <input class="form-control pass-confirm" type="text">
                        <span class="profile-views feather-eye reg-toggle-password"></span>
                    </div>
                    <div class=" dont-have">Sudah Punya akun? <a href="{{ route('auth.login') }}">Login</a></div>
                    <div class="form-group mb-0">
                        <button class="btn btn-primary btn-block" type="submit">Register</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
