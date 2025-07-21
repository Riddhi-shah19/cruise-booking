<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name') . " - Passenger Area")</title>

    <!-- Bootstrap 4 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background-color: #343a40;
        }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand {
            color: #ffffff;
        }
        .navbar-custom .nav-link:hover {
            color: #adb5bd;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
        .content-wrapper {
            min-height: 80vh;
            margin-top: 20px;
        }
        footer {
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('passenger.dashboard') }}">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#passengerNavbar" aria-controls="passengerNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="passengerNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="{{ route('passenger.dashboard') }}" class="nav-link {{ request()->is('passenger/dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('passenger.booking') }}" class="nav-link {{ request()->is('passenger/booking') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i> New Booking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ticket.paid') }}" class="nav-link {{ request()->is('passenger/bookings') ? 'active' : '' }}">
                            <i class="fas fa-book-open"></i> View Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('passenger.feedback') }}" class="nav-link {{ request()->is('passenger/feedback') ? 'active' : '' }}">
                            <i class="fas fa-comments"></i> Feedback
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    
                    <li class="nav-item d-flex align-items-center me-2">
                    <img src="{{ asset(Auth::guard('passenger')->user()->loc ? 'storage/' . Auth::guard('passenger')->user()->loc : 'storage/passenger_profiles/default.png') }}" alt="Profile" class="profile-img">
                    <span class="ms-2 text-white">{{ Auth::guard('passenger')->user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('passenger.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="display: inline; margin: 0; padding: 0;">
                                <i class="fas fa-power-off"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <!-- <div class="container content-wrapper"> -->
        @yield('content')
    <!-- </div> -->

    <!-- Footer -->
    <footer class="text-center text-muted">
        <div class="container py-4">
            <strong>{{ now()->year }} &copy; {{ config('app.name') }}. All rights reserved.</strong>
        </div>
    </footer>

    <!-- Bootstrap 4 JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
   

    <script>
    $(function() {
        $("#example1").DataTable();
    });
    </script>
    @stack('scripts')
</body>
</html>
