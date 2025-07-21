@extends('layouts.passenger')

@section('title', config('app.name') . ' - Feedbacks')

@section('content')

<style>
     .card {
        background-color: rgba(255, 255, 255, 0.4);
     }
</style>

<div style="background-image: url('/storage/feedback.jpg'); background-size: cover; background-repeat: no-repeat; padding: 50px 0; min-height: 100vh;">
    <div class="container py-3">
        <div class="card shadow">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">List of All Feedbacks</h4>
                <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#addFeedbackModal">
                    Send New Feedback
                </button>
            </div>

            <div class="card-body table-responsive">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> We always want to hear from you! Replied to within 24 hours.
                </div>

                <table class="table table-bordered table-hover bg-white" id="example1">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Your Comment</th>
                            <th>Response</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedbacks as $index => $fb)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $fb->message }}</td>
                                <td>{{ $fb->response ?? '-- No Response Yet --' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="addFeedbackModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title">Send New Feedback</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('feedbacks.store') }}">
                    @csrf
                    <div class="form-group text-left">
                        <label for="message">Type Message:</label>
                        <textarea name="message" id="message" required minlength="10" class="form-control" rows="6"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
