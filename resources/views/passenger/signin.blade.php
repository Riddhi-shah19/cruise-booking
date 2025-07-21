<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Sign In</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        #bg-video {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            object-fit: cover;
        }       

        .bg-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4); /* Adjust opacity for darker/lighter */
        z-index: -1;
    }

        .custom-navbar {
            background-color: #137c89;
            padding: 12px 10px;
        }

        .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 30px; 
            align-items: center;
        }

        .nav-links li {
            display: inline-block;
        }

        .nav-links li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .alert-danger {
        color: red;
        }
    </style>
    </head>
<body>

<video autoplay muted loop id="bg-video">
    <source src="{{ asset('storage/moving-cruise.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
</video>

@php
    $currentRoute = Route::currentRouteName();
@endphp

<div class="bg-overlay">
<nav class="custom-navbar">
    <div class="container-fluid">
        <ul class="nav-links">
            <li class="{{ $currentRoute == 'register' ? 'active' : '' }}">
                <a href="{{ route('signup.register') }}">Sign Up</a>
            </li>
            <li class="{{ $currentRoute == 'admin.login' ? 'active' : '' }}">
                <a href="{{ route('login') }}">Sign In</a>
            </li>
            <li>
                <a href="{{ url('/') }}">Go Back</a>
            </li>
        </ul>
    </div>
</nav>

<div class="signup-page">
    <div class="form" style="background:rgba(255,255,255, 0.8)">
        <h2>Passenger Sign In</h2>

        <br>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                {{ implode('', $errors->all(':message')) }}
            </div>
        @endif

        <form class="login-form" method="POST" action="{{ route('signin') }}" id="signup-form" autocomplete="off">
            @csrf

            <div class="col-md-12">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" required name="email" value="{{ old('email') }}">
                </div>
            </div>

                <div class="col-md-12">
                    <div class="form-group" style="position: relative;">
                        <label>Password</label>
                        <input type="password" required name="password" id="password">
                        <span class="toggle-password" onclick="togglePassword()" style="position: absolute; right: 10px; top: 38px; cursor: pointer;">
            <i id="eye-icon" class="fas fa-eye"></i>
        </span>
                    </div>
                </div>

            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" id="btn-signup">
                        SIGN IN
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eye-icon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>