@extends('layouts.passenger')

@section('title', config('app.name') . ' - Book Tickets')

@section('content')

<style>
    .booking-section {
        background: url('/storage/cruise/booking-background.jpg') no-repeat center center;
        background-size: cover;
        min-height: 100vh;
        padding: 50px 0;
    }
    .card {
        background-color: rgba(255, 255, 255, 0.4);
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .table thead th {
        background-color: #4CAF50;
        color: white;
    }

    /* table.dataTable,
    table.dataTable tbody tr {
        background-color: rgba(255, 255, 255, 0.4) !important;
    } */

</style>

<div class="booking-section d-flex align-items-center">
    <div class="container">
        <div class="card shadow-lg p-4">
            <div class="card-header bg-success text-white rounded">
                <h3 class="card-title text-center"><b>Book Cruise Tickets</b></h3>
            </div>
            <div class="card-body">
                @if(count($schedules) == 0)
                    <div class="alert alert-danger text-center">
                        Sorry, there are no schedules available right now! Please check back later.
                    </div>
                @else
                <div class="table-responsive">
                    <table id="example1" class="table table-hover table-bordered table-striped w-100">
                        <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Route</th>
                                <th>Status</th>
                                <th>Date/Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $index => $schedule)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ getRoutePath($schedule->route_id)?? 'N/A' }}</td>
                                    <td>
                                        @php
                                        $array = getTotalBookByType($schedule->id);
                                        $availableFirst= $array['first'] - $array['first_booked'] ;
                                        $availableSecond=$array['second'] - $array['second_booked']
                                        @endphp
                                        <div><strong>{{$availableFirst}}</strong> Seat(s) Available - <em>General Class</em></div>
                                        <hr class="my-1">
                                        <div><strong>{{ $availableSecond }}</strong> Seat(s) Available - <em>Luxury Class</em></div>
                                    </td>
                                    <td>{{ $schedule->date }} / {{ formatTime($schedule->time) }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#bookModal{{ $schedule->id }}">
                                            Book Now
                                        </button>
                                    </td>
                                </tr>

                                <!-- Booking Modal -->
                                <div class="modal fade" id="bookModal{{ $schedule->id }}" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel{{ $schedule->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="bookingModalLabel{{ $schedule->id }}">
                                                    Book for {{ getRoutePath($schedule->route_id) ?? 'Route' }} 🚢
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('passenger.dashboard') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $schedule->id }}">

                                                    <div class="form-group">
                                                        <label for="number">Number of Tickets:</label>
                                                        <input type="number" name="number" min="1" max="{{ max($availableFirst, $availableSecond) }}" value="1" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="class">Class:</label>
                                                        <select name="class" class="form-control classSelect" required>
                                                            <option value="">-- Select Class --</option>
                                                            <option value="general">General Class (Rs {{ $schedule->general_fee }})</option>
                                                            <option value="luxury">Luxury Class (Rs {{ $schedule->luxury_fee }})</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="room_number">Select Room Number:</label>
                                                        <select name="room_number" class="form-control roomSelect" required>
                                                            <option value="">-- Select Room --</option>
                                                        </select>
                                                    </div>

                                                    <input type="hidden" name="cruise_id" class="cruise_id" value="{{ $schedule->cruise_id }}">

                                                    <div class="text-right mt-3">
                                                        <button type="submit" class="btn btn-success">Proceed</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->

                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.classSelect').forEach(function (dropdown) {
        dropdown.addEventListener('change', function () {
            const selectedClass = this.value;
            const modalContainer = this.closest('.modal'); // Find the closest modal wrapper
            const cruiseId = modalContainer.querySelector('.cruise_id').value;
            const roomSelect = modalContainer.querySelector('.roomSelect');

            // Clear old options
            roomSelect.innerHTML = '<option value="">-- Select Room --</option>';

            if (!selectedClass) return;

            fetch(`/get-available-rooms?cruise_id=${cruiseId}&type=${selectedClass}`)
                .then(response => response.json())
                .then(data => {
                    if (data.rooms && data.rooms.length > 0) {
                        data.rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room;
                            option.text = room;
                            roomSelect.appendChild(option);
                        });
                    } else {
                        alert('No available rooms for selected class.');
                    }
                })
                .catch(err => {
                    console.error('Error fetching rooms:', err);
                });
        });
    });
});
</script>


@endsection
