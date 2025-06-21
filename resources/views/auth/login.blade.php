{{-- resources/views/auth/login.blade.php --}}
@extends('auth.layout-auth')

@section('title', 'Login')

@section('content')
<style>
    .auth-wrapper {
        position: relative;
        overflow: hidden;
        background: linear-gradient(120deg, #EDE7F6 0%, #673AB7 100%);
        height: 100vh;
        max-height: 100vh;
    }

    .floating-circle {
        position: absolute;
        font-size: 4rem;
        opacity: 0.4;
        z-index: 0;
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
    }

    .circle-1 { top: 5%; left: 5%; animation: float 4s ease-in-out infinite; color: #673AB7; }
    .circle-2 { top: 15%; left: 35%; animation: float 3.5s ease-in-out infinite 0.2s; color: #9575CD; }
    .circle-3 { top: 35%; left: 15%; animation: float 4.5s ease-in-out infinite 0.4s; color: #7E57C2; }
    .circle-4 { top: 55%; left: 35%; animation: float 3.8s ease-in-out infinite 0.6s; color: #B39DDB; }
    .circle-5 { top: 75%; left: 5%; animation: float 4.2s ease-in-out infinite 0.8s; color: #512DA8; }
    .circle-6 { top: 25%; left: 55%; animation: float 3.6s ease-in-out infinite 1s; color: #5E35B1; }
    .circle-7 { top: 45%; left: 65%; animation: float 4.1s ease-in-out infinite 1.2s; color: #4527A0; }
    .circle-8 { top: 65%; left: 55%; animation: float 3.9s ease-in-out infinite 1.4s; color: #311B92; }
    .circle-9 { top: 85%; left: 75%; animation: float 4.3s ease-in-out infinite 1.6s; color: #D1C4E9; }
    .circle-10 { top: 10%; left: 85%; animation: float 4s ease-in-out infinite 1.8s; color: #9575CD; }
    .circle-11 { top: 30%; left: 80%; animation: float 3.7s ease-in-out infinite 2s; color: #B39DDB; }
    .circle-12 { top: 50%; left: 90%; animation: float 4.4s ease-in-out infinite 2.2s; color: #7E57C2; }
    .circle-13 { top: 70%; left: 85%; animation: float 3.8s ease-in-out infinite 2.4s; color: #673AB7; }
    .circle-14 { top: 90%; left: 90%; animation: float 4.2s ease-in-out infinite 2.6s; color: #512DA8; }
    .circle-15 { top: 20%; left: 25%; animation: float 3.9s ease-in-out infinite 2.8s; color: #311B92; }

    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
            opacity: 0.4;
        }
        50% {
            transform: translateY(-30px) rotate(10deg);
            opacity: 0.8;
        }
    }

    .auth-inner {
        position: relative;
        z-index: 1;
        background-color: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
        margin: 20px;
        padding: 20px;
        height: calc(100vh - 40px);
        overflow-y: hidden;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    body {
        overflow: hidden;
    }

    .btn-primary {
        background-color: #673AB7 !important;
        border-color: #673AB7 !important;
    }

    .btn-primary:hover {
        background-color: #512DA8 !important;
        border-color: #512DA8 !important;
    }

    .text-primary {
        color: #673AB7 !important;
    }

    a {
        color: #673AB7;
    }

    a:hover {
        color: #512DA8;
    }

      .img-side {
        background-image: url('/Assets/Frontend//img/cover_login.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 80%;
        margin-top: 50px;
        margin-bottom: 0;
    }
</style>

<div class="auth-wrapper">
    <div class="floating-circle circle-1">⚪</div>
    <div class="floating-circle circle-2">⚪</div>
    <div class="floating-circle circle-3">⚪</div>
    <div class="floating-circle circle-4">⚪</div>
    <div class="floating-circle circle-5">⚪</div>
    <div class="floating-circle circle-6">⚪</div>
    <div class="floating-circle circle-7">⚪</div>
    <div class="floating-circle circle-8">⚪</div>
    <div class="floating-circle circle-9">⚪</div>
    <div class="floating-circle circle-10">⚪</div>
    <div class="floating-circle circle-11">⚪</div>
    <div class="floating-circle circle-12">⚪</div>
    <div class="floating-circle circle-13">⚪</div>
    <div class="floating-circle circle-14">⚪</div>
    <div class="floating-circle circle-15">⚪</div>
    <div class="auth-inner">
        <!-- Login Section -->
        <div class="col-lg-6 d-flex align-items-center auth-bg">
            <div class="login-card mx-auto w-100 p-3">
                <a class="brand-logo d-flex align-items-center" href="/">
                    <img src="{{ asset('Assets/Frontend/img/foto_logo.png') }}" alt="Logo" width="50" height="50">
                    <h2 class="brand-text text-primary ml-1 mb-0">RA Al Barokah</h2>       
                </a>
                @if($message = Session::get('error'))
                    <div class="alert alert-danger">
                        <div class="alert-body">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                    </div>
                @elseif($message = Session::get('success'))
                    <div class="alert alert-success">
                        <div class="alert-body">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                    </div>
                @endif

                <h2 class="card-title font-weight-bold mb-1">Welcome to RA Al Barokah! 👋</h2>
                <p class="card-text mb-2">Silakan masuk ke akun Anda</p>

                <form class="auth-login-form mt-2" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="text" id="login-email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Masukan Email" autofocus>
                        @error('email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="login-password">Password</label>
                            <a href="{{ route('password.request') }}">
                                <small>Forgot Password?</small>
                            </a>
                        </div>                        
                        <div class="input-group input-group-merge form-password-toggle">
                            <input type="password" id="login-password" class="form-control form-control-merge @error('password') is-invalid @enderror" name="password" placeholder="············">
                            <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group form-check">
                        <input class="form-check-input" id="remember-me" type="checkbox">
                        <label class="form-check-label" for="remember-me">Ingat Saya</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Masuk</button>
                </form>

                <p class="text-center mt-2">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                </p>
            </div>
        </div>
        <!-- /Login Section -->

        <!-- Animated Background Section -->
        <div class="col-lg-6 img-side d-none d-lg-flex">
        </div>
        <!-- /Animated Background Section -->
    </div>
</div>
@endsection