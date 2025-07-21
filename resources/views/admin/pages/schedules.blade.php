@extends('layouts.admin') 
@section('content')

<div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">All Dynamic Schedules</h3>
                            <div class='float-right'>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add">
                                    Add New One-Time Schedule &#128645;
                                </button> - - -
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add2">
                                    Add Range Schedule &#128645;
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" style="align-items: stretch;" class="table table-hover w-100 table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cruise</th>
                                        <th>Route</th>
                                        <th>General Fee</th>
                                        <th>Luxury Fee</th>
                                        <th>Total Bookings</th>
                                        <th>Date/Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($schedules as $index => $schedule)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ getTrainName($schedule->cruise_id) }}</td>
                                            <td>{{ getRoutePath($schedule->route_id) }}</td>
                                            <td>Rs. {{ $schedule->general_fee }}</td>
                                            <td>Rs. {{ $schedule->luxury_fee }}</td>
                                            <td>
                                                @php $array = getTotalBookByType($schedule->id); @endphp
                                                {{ $array['first'] - $array['first_booked'] }} Seat(s) Available for First Class
                                                <hr/>
                                                {{ $array['second'] - $array['second_booked'] }} Seat(s) Available for Second Class
                                            </td>
                                            <td>{{ $schedule->date }} / {{ formatTime($schedule->time) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit{{ $schedule->id }}">
                                                    Edit
                                                </button> -
                                                <form action="{{ route('schedule.destroy', $schedule->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure about this?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="edit{{ $schedule->id }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Editing Schedule &#128642;</h4>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('schedule.update', $schedule->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')

                                                            <p>Cruise:
                                                                <select class="form-control" name="cruise_id" required>
                                                                    <option value="">Select Cruise</option>
                                                                    @foreach ($cruise as $cruises)
                                                                        <option value="{{ $cruises->id }}" {{ $schedule->cruise_id == $cruises->id ? 'selected' : '' }}>
                                                                            {{ $cruises->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </p>

                                                            <p>Route:
                                                                <select class="form-control" name="route_id" required>
                                                                    <option value="">Select Route</option>
                                                                    @foreach ($routes as $route)
                                                                        <option value="{{ $route->id }}" {{ $schedule->route_id == $route->id ? 'selected' : '' }}>
                                                                            {{ getRoutePath($route->id) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </p>

                                                            <p>First Class Charge:
                                                                <input class="form-control" type="number" name="general_fee" value="{{ $schedule->general_fee }}" required>
                                                            </p>
                                                            <p>Second Class Charge:
                                                                <input class="form-control" type="number" name="luxury_fee" value="{{ $schedule->luxury_fee }}" required>
                                                            </p>
                                                            <p>Date:
                                                                <input type="date" id="date" class="form-control" name="date" value="<?php echo (date('Y-m-d', strtotime($schedule->date))) ?> required>
                                                            </p>
                                                            <p>Time:
                                                                <input type="time" class="form-control" name="time" value="{{ $schedule->time }}" required>
                                                            </p>
                                                            <p class="float-right">
                                                                <input type="submit" class="btn btn-success" value="Edit Schedule">
                                                            </p>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="8">No Records Yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="add">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" align="center">
            <div class="modal-header">
                <h4 class="modal-title">Add New Schedule &#128649;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('schedule.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            Cruise :
                            <select class="form-control" name="cruise_id" required id="">
                                <option value="">Select Cruise</option>
                                @foreach($cruise as $cruises)
                                    <option value="{{ $cruises->id }}">{{ $cruises->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            Route :
                            <select class="form-control" name="route_id" required id="">
                                <option value="">Select Route</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}">{{ getRoutePath($route->id) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            First Class Charge :
                            <input class="form-control" type="number" name="general_fee" required id="">
                        </div>
                        <div class="col-sm-6">
                            Second Class Charge :
                            <input class="form-control" type="number" name="luxury_fee" required id="">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Date :
                            <input class="form-control" onchange="check(this.value)" type="date" name="date" required id="date">
                        </div>
                        <div class="col-sm-6">
                            Time :
                            <input class="form-control" type="time" name="time" required id="">
                        </div>
                    </div>
                    <hr>
                    <input type="submit" name="submit" class="btn btn-success" value="Add Schedule">
                </form>

                <script>
                    function check(val) {
                        val = new Date(val);
                        var age = (Date.now() - val) / 31557600000;
                        var formDate = document.getElementById('date');
                        if (age > 0) {
                            alert("Past/Current Date not allowed");
                            formDate.value = "";
                            return false;
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add2">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" align="center">
            <div class="modal-header">
                <h4 class="modal-title">Add Range Schedule &#128649;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('schedule.range.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            Cruise :
                            <select class="form-control" name="cruise_id" required id="">
                                <option value="">Select Cruise</option>
                                @foreach($cruise as $cruises)
                                    <option value="{{ $cruises->id }}">{{ $cruises->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            Route :
                            <select class="form-control" name="route_id" required id="">
                                <option value="">Select Route</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}">{{ getRoutePath($route->id) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            First Class Charge :
                            <input class="form-control" type="number" name="general_fee" required>
                        </div>
                        <div class="col-sm-6">
                            Second Class Charge :
                            <input class="form-control" type="number" name="luxury_fee" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            From Date :
                            <input class="form-control" onchange="check(this.value)" type="date" name="from_date" required>
                        </div>
                        <div class="col-sm-6">
                            End Date :
                            <input class="form-control" onchange="check(this.value)" type="date" name="to_date" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Every :
                            <select class="form-control" name="every">
                                <option value="Day">Day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            Time :
                            <input class="form-control" type="time" name="time" required id="">
                        </div>
                    </div>
                    <hr>
                    <input type="submit" name="submit2" class="btn btn-success" value="Add Schedule">
                </form>

                <script>
                function check(val) {
                    val = new Date(val);
                    var age = (Date.now() - val) / 31557600000;
                    if (age > 0) {
                        alert("You are using a past/current date!");
                        return false;
                    }
                }
                </script>
            </div>
        </div>
    </div>
</div>  


@endsection
