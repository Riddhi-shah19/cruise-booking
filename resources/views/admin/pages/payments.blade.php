@extends('layouts.admin') <!-- Or your specific layout -->

@section('content')
<div class="content">
    <div class="row">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-success">
                    <div class="card-header border-0">
                        <h3 class="card-title">All Payments</h3>
                    </div>
                    <div class="card-body">
                        <table id='example1' class="table table-striped table-bordered table-hover table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Route</th>
                                    <th>Date</th>
                                    <th>General Class</th>
                                    <th>Luxury Class</th>
                                    <th>Capacity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->route }}</td>
                                        <td>{{ $payment->date }} - {{ $payment->formatted_time }}</td>
                                        <td>Rs {{ $payment->sum_first }}</td>
                                        <td>Rs {{ $payment->sum_second }}</td>
                                        <td>
                                            {{ $payment->first_class_avail }} Seat(s) Available for General Class
                                            <hr/>
                                            {{ $payment->second_class_avail }} Seat(s) Available for Luxury Class
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- /.col -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /.row -->
</div> <!-- /.content -->
@endsection
