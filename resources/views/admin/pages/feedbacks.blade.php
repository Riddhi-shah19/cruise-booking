@extends('layouts.admin') 

@section('content')
<div class="content">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">All Feedbacks</h3>
                        </div>

                        <div class="card-body">
                            <table id="example1" class="table table-hover w-100 table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Passenger</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th style="width: 30%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($feedbacks as $index => $feedback)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $feedback->user->name ?? 'Unknown' }}</td>
                                            <td>{{ $feedback->message }}</td>
                                            <td>{{ $feedback->response ?? 'Pending' }}</td>
                                            <td>
                                                @if (is_null($feedback->response))
                                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                                        data-target="#edit{{ $feedback->id }}">
                                                        Reply
                                                    </button>
                                                @else
                                                    Reply Given
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Reply Modal -->
                                        <div class="modal fade" id="edit{{ $feedback->id }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Replying to {{ $feedback->user->name ?? 'User' }}'s Message</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('feedback.reply', $feedback->id) }}" method="POST">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label>Reply</label>
                                                                <textarea name="reply" class="form-control" required minlength="3"></textarea>
                                                            </div>
                                                            <button type="submit" class="btn btn-info">Send Reply</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No Feedbacks Yet</td>
                                        </tr>
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
@endsection
