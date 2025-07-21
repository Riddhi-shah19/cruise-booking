<aside class="main-sidebar sidebar-dark-success elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">{{ now()->format('D d, M y') }}</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('storage/cruise_logo.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Admin</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="nav-icon fas fa-tachometer-alt"></i> <p>Home</p></a></li>
                <li class="nav-item"><a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="nav-icon fas fa-users"></i> <p>Users</p></a></li>
                <li class="nav-item"><a href="{{ route('admin.schedules') }}" class="nav-link {{ request()->routeIs('admin.schedules') ? 'active' : '' }}"><i class="nav-icon fas fa-calendar-day"></i> <p>Schedules</p></a></li>
                <li class="nav-item"><a href="{{ route('admin.routes') }}" class="nav-link {{ request()->routeIs('admin.routes') ? 'active' : '' }}"><i class="nav-icon fas fa-route"></i> <p>Routes</p></a></li>
                <li class="nav-item"><a href="{{ route('admin.cruise') }}" class="nav-link {{ request()->routeIs('admin.trains') ? 'active' : '' }}"><i class="nav-icon fas fa-train"></i> <p>Cruise</p></a></li>
                <li class="nav-item"><a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}"><i class="nav-icon fas fa-file-pdf"></i> <p>Reports</p></a></li>
                <li class="nav-item"><a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }}"><i class="nav-icon fas fa-dollar-sign"></i> <p>Payments</p></a></li>
                <li class="nav-item"><a href="{{ route('admin.feedbacks') }}" class="nav-link {{ request()->routeIs('admin.feedbacks') ? 'active' : '' }}"><i class="nav-icon fas fa-mail-bulk"></i> <p>Feedbacks</p></a></li>
                <li class="nav-item"><a href="{{ route('admin.logout') }}" class="nav-link"><i class="nav-icon fas fa-power-off"></i> <p>Logout</p></a></li>
            </ul>
        </nav>
    </div>
</aside>
