@extends('layouts.admin') 

@section('content')
<div class="content">
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">All Cruise</h3>
                            <div class="float-right">
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addTrainModal">
                                    Add New Cruise &#128645;
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="example1" class="table table-hover w-100 table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cruise Name</th>
                                        <th>General Rooms</th>
                                        <th>Luxury Rooms</th>
                                        <th style="width: 30%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cruise as $index => $cruises)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $cruises->name }}</td>
                                            <td>{{ $cruises->general_rooms }}</td>
                                            <td>{{ $cruises->luxury_rooms }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editTrainModal{{ $cruises->id }}">
                                                    Edit
                                                </button> -

                                                <form action="{{ route('cruise.destroy', $cruises->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure about this?')" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editTrainModal{{ $cruises->id }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <form action="{{ route('cruise.update', $cruises->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit {{ $cruises->name }}</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Cruise Name:
                                                                <input type="text" class="form-control" name="name" value="{{ $cruises->name }}" required minlength="3">
                                                            </p>
                                                            <p>General Capacity:
                                                                <input type="number" min="0" class="form-control" name="general_rooms" value="{{ $cruises->general_rooms }}" required>
                                                            </p>
                                                            <p>Luxury Capacity:
                                                                <input type="number" min="0" class="form-control" name="luxury_rooms" value="{{ $cruises->luxury_rooms }}" required>
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" class="btn btn-info" value="Edit Cruise">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr><td colspan="5">No Cruise Found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addTrainModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" align="center">
            <form action="{{ route('cruise.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Add New Cruise &#128646;</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Cruise Name</th>
                            <td><input type="text" class="form-control" name="name" required minlength="3"></td>
                        </tr>
                        <tr>
                            <th>First Class Capacity</th>
                            <td><input type="number" min="0" class="form-control" name="general_rooms" required></td>
                        </tr>
                        <tr>
                            <th>Second Class Capacity</th>
                            <td><input type="number" min="0" class="form-control" name="luxury_rooms" required></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="submit" class="btn btn-info" value="Add Cruise">
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
