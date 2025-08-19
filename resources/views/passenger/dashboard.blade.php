@extends('layouts.passenger')

@section('title', 'Passenger Dashboard')

@section('content')

<style>
    .payment-section {
        background: url('/storage/payment-background.jpg') no-repeat center center;
        background-size: cover;
        min-height: 100vh;
        padding: 50px 50px;
    }
</style>

<!-- <div class="container-fluid"> -->
@if (!isset($class))
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-lg rounded-3" style="background-color: rgba(155, 178, 223, 0.8);">
            <div class="card-body">
                <h1 class="mb-4">Welcome, {{ Auth::guard('passenger')->user()->name }}!</h1>

                <div class="mb-3 d-flex justify-content-start align-items-center">
                    <label class="me-2">Select Month:</label>
                    <select id="month-select" class="form-select w-auto me-3">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endfor
                    </select>

                    <label class="me-2">Select Year:</label>
                    <select id="year-select" class="form-select w-auto">
                        @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                    </select>
                </div>
                <div id='calendar'></div>
                <p>Here’s how you can proceed:</p>
                <ul class="list-unstyled mt-3">
                    <li>👉 Click on <strong>"New Booking"</strong> to view available schedules.</li>
                    <li>👉 Complete your booking and proceed to payment.</li>
                    <li>👉 Upon successful payment, you'll receive a <strong>Ticket ID</strong>.</li>
                    <li>👉 Bring your Ticket ID to the station for verification.</li>
                    <li>👉 View all your past bookings in <strong>"View Bookings"</strong>.</li>
                </ul>
            </div>

        </div>
    </div>
</div>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<!-- Tooltip.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2"></script>
<script src="https://cdn.jsdelivr.net/npm/tooltip.js@1.3.3/dist/umd/tooltip.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($schedules),
            eventDidMount: function(info) {
                $(info.el).tooltip({
                    title: info.event.title + " - " + info.event.extendedProps.time,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });

        calendar.render();

        document.getElementById('month-select').addEventListener('change', updateCalendarDate);
        document.getElementById('year-select').addEventListener('change', updateCalendarDate);

        function updateCalendarDate() {
            let month = document.getElementById('month-select').value;
            let year = document.getElementById('year-select').value;

            if (month && year) {
                // Pad month to 2 digits
                let dateStr = `${year}-${String(month).padStart(2, '0')}-01`;
                calendar.gotoDate(dateStr);
            }
        }

    });
</script>
@else
<div class="payment-section">
    <div class="card shadow-sm border-info" style="--bs-card-bg:rgba(255, 255, 255, 0.2);">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Booking Preview</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info" style="background-color:rgba(207, 244, 252,0.8);">
                <h5><i class="fas fa-info-circle"></i> {{ ucfirst($class) }} Class:</h5>
                You are about to book <strong>{{ $number }} ticket{{ $number > 1 ? 's' : '' }}</strong> for <strong>{{ $route }}</strong><br><br>
                Fee per ticket: Rs {{ $fee }} <br>
                Total Fee: Rs {{ $total }} <br>
                V.A.T: Rs {{ $vat }} <br>
                <hr>
                <strong>Total Payable: Rs {{ $grandTotal }}</strong>
            </div>
            <form action="{{ route('payment.initiate') }}" method="POST" onsubmit="return confirm('You will be directed to make your payment.\nPayment finalizes your booking!')">
                @csrf
                <button type="submit" class="btn btn-primary">Pay Now</button>
            </form>

        </div>
    </div>
</div>
@endif
<!-- </div> -->


<!-- Chatbot UI -->
<div id="chatbot-icon" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; cursor: pointer;">
    <img src="https://img.icons8.com/ios-filled/50/000000/chat.png" alt="Chatbot" width="50">
</div>

<div id="chatbox" style="display: none; position: fixed; bottom: 100px; right: 30px; width: 300px; height: 400px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.3); z-index: 9999; overflow: hidden;">
    <div style="background: #007BFF; color: white; padding: 10px; text-align: center;">
        <strong>Ask me about train schedules</strong>
    </div>
    <div id="chat-messages" style="height: 300px; overflow-y: auto; padding: 10px; font-size: 14px;"></div>
    <div style="display: flex; border-top: 1px solid #ccc;">
        <input type="text" id="chat-input" placeholder="Type your question..." style="flex: 1; border: none; padding: 10px;">
        <button id="send-btn" style="background: #007BFF; color: white; border: none; padding: 10px;">Send</button>
    </div>
</div>

<script>
    const chatbotIcon = document.getElementById('chatbot-icon');
    const chatbox = document.getElementById('chatbox');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    const sendBtn = document.getElementById('send-btn');

    chatbotIcon.addEventListener('click', () => {
        chatbox.style.display = chatbox.style.display === 'none' ? 'block' : 'none';
    });

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });

    function appendMessage(content, isUser = true) {
        const msg = document.createElement('div');
        msg.style.margin = '5px 0';
        msg.style.textAlign = isUser ? 'right' : 'left';
        msg.innerHTML = `<span style="background: ${isUser ? '#DCF8C6' : '#EEE'}; padding: 6px 10px; border-radius: 12px; display: inline-block; max-width: 80%;">${content}</span>`;
        chatMessages.appendChild(msg);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function sendMessage() {
        const text = chatInput.value.trim();
        if (!text) return;

        appendMessage(text, true);
        chatInput.value = '';

        fetch('/api/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            appendMessage(data.reply || 'Sorry, I did not understand that.', false);
        })
        .catch(() => {
            appendMessage('An error occurred while connecting to the chatbot.', false);
        });
    }
</script>

@endsection