@extends('layouts.passenger')

@section('title', config('app.name') . ' - My Bookings')

@section('content')
<div style="background-image: url('/images/bbb.jpg'); background-size: cover; background-repeat: no-repeat; padding: 50px 0; min-height: 100vh;">
    <div class="container py-3">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Purchased Tickets</h4>
            </div>

            <div class="card-body table-responsive">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-hover bg-white" id="example1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ticket Number</th>
                            <th>Trip Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $index => $booking)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $booking->code }}</td>
                                <td>{{ $booking->payment->date ?? 'N/A' }}</td>
                                <td>
                                    @if(isScheduleActive($booking->schedule_id))
                                        <span class="text-success font-weight-bold">Active</span>
                                    @else
                                        <span class="text-danger font-weight-bold">Expired</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#view{{ $booking->id }}">View</button>
                                </td>
                            </tr>

                            {{-- Modal --}}
                            <div class="modal fade" id="view{{ $booking->id }}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Details for {{ getRouteFromSchedule($booking->schedule_id) }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <div class="modal-body">
                                            <p><strong>Room Number:</strong> {{ $booking->room->room_number }}</p>
                                            <p><strong>Cruise Name:</strong> {{ getTrainName($booking->cruise_id) }}</p>
                                            <p><strong>Payment Date:</strong> {{ $booking->payment->date }}</p>
                                            <p><strong>Amount Paid:</strong> ₹{{ $booking->payment->amount }}</p>
                                            <p><strong>Payment Ref:</strong> {{ $booking->payment->ref }}</p>

                                            @if(isScheduleActive($booking->schedule_id))
                                                <a href="{{ route('passenger.print', $booking->id) }}" class="btn btn-success">Print Ticket</a>
                                            @else
                                                <button disabled class="btn btn-danger">Ticket Has Expired</button>
                                            @endif

                                            @if(isScheduleActive($booking->schedule_id))
                                                <div x-data="{ open: false }">
                                                    <button @click="open = true" class="btn btn-dark float-right mt-3">Modify</button>

                                                    <div x-show="open" @click.away="open = false" class="mt-3">
                                                        <form method="POST" action="{{ route('passenger.tickets.modify') }}">
                                                            @csrf
                                                            <input type="hidden" name="pk" value="{{ $booking->id }}">
                                                            <div class="form-group">
                                                                <label>Modify Schedule:</label>
                                                                <select name="s" class="form-control" required>
                                                                    <option value="">Choose One Or Skip</option>
                                                                    @foreach($upcomingSchedules as $schedule)
                                                                        @php
                                                                            if ($schedule->date == today()->format('Y-m-d') && now()->format('H:i') >= $schedule->time) continue;
                                                                        @endphp
                                                                        <option value="{{ $schedule->id }}">
                                                                            {{ getRoutePath($schedule->route_id) }} - {{ $schedule->date }} / {{ formatTime($schedule->time) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <button class="btn btn-primary" type="submit">Submit</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- End Modal --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
