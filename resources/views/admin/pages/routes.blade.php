@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>All Routes</h3>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <button class="btn btn-info btn-sm mb-2" data-toggle="modal" data-target="#addRoute">Add New Route 🚍</button>

    <table id="example1" class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>From</th>
                <th>To</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($routes as $index => $route)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $route->start }}</td>
                    <td>{{ $route->stop }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editRoute{{ $route->id }}">Edit</button>
                        <form action="{{ route('routes.destroy', $route->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editRoute{{ $route->id }}">
                    <div class="modal-dialog">
                        <form action="{{ route('routes.update', $route->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Edit Route: {{ $route->start }} to {{ $route->stop }}</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <input name="start" value="{{ $route->start }}" class="form-control mb-2" required>
                                    <input name="stop" value="{{ $route->stop }}" class="form-control" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <tr><td colspan="4">No routes available.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addRoute">
    <div class="modal-dialog">
        <form action="{{ route('routes.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Route</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input name="start" placeholder="From" class="form-control mb-2" required>
                    <input name="stop" placeholder="To" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Add Route</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
