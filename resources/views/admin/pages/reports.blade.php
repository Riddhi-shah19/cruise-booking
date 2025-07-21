@extends('layouts.admin') 
@section('content')

<div class="content">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">All Schedules</h3>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">

                                <table style="width: 100%;" id="example1" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Cruise</th>
                                            <th>Route</th>
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
                                                <td>{{ $schedule->date }} / {{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.report.view', $schedule->id) }}">
                                                        <button type="button" class="btn btn-success">
                                                            View
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No Records Yet</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
