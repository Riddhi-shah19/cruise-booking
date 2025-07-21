<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title ?? 'Home' }}</title>

    <!-- Fonts and Plugins -->
    <!-- Font Awesome 4.3.0 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">

<!-- Favicon (you must upload your own icon somewhere or skip CDN for this) -->
<link rel="shortcut icon" href="{{ asset('storage/cruise_logo.jpg') }}" type="image/x-icon">

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<!-- Owl Carousel -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

<!-- Magnific Popup -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">

<!-- Bootstrap (you are using both theme and core) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap-theme.min.css">

<!-- Custom style.css, responsive.css, color/themecolor.css -->
<!-- These are your project-specific files, they don't have a public CDN unless you upload them. -->
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{asset('assets/css/responsive.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color/themecolor.css') }}">

    @stack('styles')
</head>

<body>
    <div class="preloader">
        <div class="loader theme_background_color">
            <span></span>
        </div>
    </div>

    <div class="wrapper">
        @include('passenger.partials.navbar')

        @yield('content')

        @include('passenger.partials.footer')
    </div>

   <!-- Modernizr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

<!-- jQuery 1.11.3 -->
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- jQuery Easing -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

<!-- Vegas (Background Slideshow) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vegas/2.4.4/vegas.min.js"></script>

<!-- Typed.js (Typing Animation) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/1.1.7/typed.min.js"></script>

<!-- fappear.js (Appear animations) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-appear/0.4.1/jquery.appear.min.js"></script>

<!-- jquery.countTo.js (Counter Animation) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-countto/1.2.0/jquery.countTo.min.js"></script>

<!-- Owl Carousel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- Magnific Popup -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<!-- Smooth Scroll -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.4.10/SmoothScroll.min.js"></script>

<script src="{{ asset('assets/js/common.js') }}"></script>

    @stack('scripts')
</body>

</html>
