@extends('layouts.admin')

@section('title', 'Administrator Dashboard')

@section('content')
<div class="content">
    <h5 class="mt-4 mb-2">Hi, {{ $fullname }}</h5>

    <div class="row">
        <x-admin.info-box icon="fa-users" title="Passengers" value="{{ $passengerCount }}" color="danger" />
        <x-admin.info-box icon="fa-train" title="Cruise" value="{{ $trainCount }}" color="info" />
        <x-admin.info-box icon="far fa-calendar-alt" title="Schedules" value="{{ $scheduleCount }}" color="secondary" />
        <x-admin.info-box icon="fa-rupee-sign" title="Payments" value="Rs {{ $totalPayments ?? 0 }}" color="success" />
    </div>

    <div class="row">
        <x-admin.info-box icon="fa-route" title="Routes" value="{{ $routeCount }}" color="primary" />
        <x-admin.info-box icon="fa-comment-dots" title="Feedbacks Received" value="{{ $feedbackCount }}" color="warning" />
    </div>
</div>
@endsection
