<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Sign Up</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
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
        /* position: fixed; */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4); /* Adjust opacity for darker/lighter */
        z-index: -1;
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

<div class="bg-overlay">
<div class="signup-page">
    <div class="form">
        <h2>Create Account</h2>
        <br>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                {{ implode('', $errors->all(':message')) }}
            </div>
        @endif

        <form method="POST" action="{{ route('signup.register') }}" enctype="multipart/form-data" id="signup-form" autocomplete="off">
            @csrf

            <div class="col-md-12">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required minlength="3" value="{{ old('name') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="phone" required minlength="10" pattern="[0-9]{11}" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required value="{{ old('email') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Picture</label>
                    <input type="file" name="file" >
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" required value="{{ old('address') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required id="password">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required id="cpassword">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" id="btn-signup">
                        CREATE ACCOUNT
                    </button>
                </div>
            </div>

            <p class="message">
                <a href="{{ route('login') }}">Already have an account?</a><br>
            </p>
        </form>
    </div>
</div>
</div>
