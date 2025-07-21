<nav class="nim-menu navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ $title[0] ?? '' }}<span class="themecolor">{{ $title[1] ?? '' }}</span>{{ substr($title, 2) }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#home" class="page-scroll"><h3>Home</h3></a></li>
                <li><a href="#two" class="page-scroll"><h3>About</h3></a></li>
                <li><a href="{{ route('signin') }}" class="page-scroll"><h3>Passenger Portal</h3></a></li>
                <li><a href="{{ route('admin.login') }}" class="page-scroll"><h3>Admin</h3></a></li>
            </ul>
        </div>
    </div>
</nav>
