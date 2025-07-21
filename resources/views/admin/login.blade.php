<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Sign In</title>
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

        
    </style>
    </head>
<body>

<video autoplay muted loop id="bg-video">
    <source src="{{ asset('storage/moving-cruise.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
</video>


<div class="signup-page">
    <div class="form">
        <h2>Admin Sign In</h2>

        @if(session('error'))
            <div style="color: red;">{{ session('error') }}</div>
        @elseif(session('success'))
            <div style="color: green;">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="form-group">
                <label>Email Address</label>
                <input type="text" name="email" required value="{{ old('email') }}">
                @error('email') <span style="color: red;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
                @error('password') <span style="color: red;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <button type="submit">SIGN IN</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('assets/js/jquery-1.12.4-jquery.min.js') }}"></script>

</body>
</html>
