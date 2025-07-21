@extends('layouts.app')

@section('content')

<section class="main-heading" id="home">
    <div class="overlay">
        <div class="container">
            <div class="row">
                <div class="main-heading-content col-md-12 col-sm-12 text-center">
                    <h1 class="main-heading-title"><span class="main-element themecolor" data-elements=" Online Ticket, Online Ticket, Online Ticket"></span></h1>
                    <h1 class="main-heading-title"><span class="main-element themecolor" data-elements=" Reservation System, Reservation System, Reservation System"></span></h1>
                    <p class="main-heading-text">WELCOME TO,<br />E - TICKETING FOR RAILWAYS</p>
                    <div class="btn-bar">
                        <a href="{{ route('signin') }}" class="btn btn-custom theme_background_color">Make Reservations Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="main-heading" id="home">
    <div class="overlay">
        <div class="container">
            <div class="row">
                <div class="main-heading-content col-md-12 col-sm-12 text-center">
                    <h1 class="main-heading-title">NextStop Cruise Booking Service</h1>
                    <p class="main-heading-text">The NextStop is 112 years old... etc.</p>
                    <div class="btn-bar">
                        <a href="{{ url('system') }}" class="btn btn-custom theme_background_color">Get Started</a>
                        <a href="{{ url('system/admin') }}" class="btn btn-custom-outline">Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="aboutus white-background black" id="two">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center black">
                <h3 class="title">ABOUT <span class="themecolor">US</span></h3>
                <p class="a-slog">Developed By {{ $developer_name ?? 'Developer' }}</p>
            </div>
        </div>

        <div class="gap"></div>

        <div class="row about-box">
            <div class="col-sm-4 text-center">
                <div class="margin-bottom">
                    <i class="fa fa-newspaper-o"></i>
                    <h4>Get Cruise Tickets from the comfort of your home</h4>
                    <p class="black">Book cruise tickets from anywhere using the robust ticketing platform...</p>
                </div>
            </div>

            <div class="col-sm-4 about-line text-center">
                <div class="margin-bottom">
                    <i class="fa fa-diamond"></i>
                    <h4>Cruise & Ticketing related information at your fingertips</h4>
                    <p class="black">Checkout available seats, route information, fare information on real time...</p>
                </div>
            </div>

            <div class="col-sm-4 text-center">
                <div class="margin-bottom">
                    <i class="fa fa-dollar"></i>
                    <h4>Pay Securely</h4>
                    <p class="black">Online payment. (NO REFUND!)</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
