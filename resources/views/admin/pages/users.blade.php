@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">Registered Users</h3>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table id="example1" class="table table-hover table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($passengers as $index => $passenger)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $passenger->name }}</td>
                                <td>{{ $passenger->email }}</td>
                                <td>{{ $passenger->phone }}</td>
                             
                                <td>
                                    @if(file_exists(public_path('storage/' . $passenger->loc)))
                                    <img src="{{ asset('storage/' . $passenger->loc) }}" width="80" height="80" class="img img-rounded" />
                                            @else
                                    <img src="{{ asset('storage/passenger_profiles/default.png') }}" width="80" height="80" class="img img-rounded" />
                                    @endif                               
                                 </td>
                                <td>
                                    @if ($passenger->status == 0)
                                        <a href="{{ route('admin.passengers.toggleStatus', [$passenger->id, 1]) }}" 
                                           onclick="return confirm('Allow this user to login?')"
                                           class="btn btn-success">Enable Account</a>
                                    @else
                                        <a href="{{ route('admin.passengers.toggleStatus', [$passenger->id, 0]) }}"
                                           onclick="return confirm('Deny this user login access?')"
                                           class="btn btn-danger">Disable Account</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No Records Yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

