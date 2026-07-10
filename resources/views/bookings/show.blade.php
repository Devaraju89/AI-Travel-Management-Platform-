@extends('layouts.app')
@section('title','Booking #'.$booking->booking_reference)
@section('content')

<style>
.page-header { background-color: #f1f5f9; padding: 3rem 0; border-bottom: 1px solid var(--border); }
.page-header h1 { font-size: 2rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem; letter-spacing: -0.5px; }

.booking-card { background: #fff; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); padding: 2rem; margin-bottom: 1.5rem; }
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
.info-label { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 600; text-transform: uppercase; }
.info-val { font-weight: 600; font-size: 1rem; color: var(--text-main); }

.badge-status { padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600; }
.status-confirmed { background: #ecfdf5; color: #059669; }
.status-cancelled { background: #fef2f2; color: #ef4444; }
.status-pending { background: #fffbeb; color: #d97706; }

.guide-box { background: #f0fdfa; border: 1px solid #ccfbf1; padding: 2rem; border-radius: 12px; margin-bottom: 1.5rem; }
</style>

<div class="page-header">
    <div class="container" style="max-width: 900px;">
        <a href="{{ route('bookings.index') }}" style="color: var(--text-muted); font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem; display: inline-block;">&larr; Back to Bookings</a>
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
            <h1>Booking Details</h1>
            <span class="badge-status {{ $booking->booking_status=='confirmed'?'status-confirmed':($booking->booking_status=='cancelled'?'status-cancelled':'status-pending') }}">{{ ucfirst($booking->booking_status) }}</span>
        </div>
    </div>
</div>

<div class="container" style="max-width: 900px; padding-top: 2rem; padding-bottom: 4rem;">
    <div class="booking-card">
        <div style="margin-bottom: 2rem;">
            <div class="info-label">Reference Number</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: var(--primary);">{{ $booking->booking_reference }}</div>
        </div>

        <div class="info-grid">
            @foreach([
                ['Package', $booking->package?->title ?? 'Custom Itinerary'],
                ['Destination', $booking->package?->destination?->name ?? 'N/A'],
                ['Travel Date', $booking->check_in?->format('M d, Y') ?? '—'],
                ['Travelers', $booking->adults.' Adults'.($booking->children ? ', '.$booking->children.' Children' : '')],
                ['Total Amount', '₹'.number_format($booking->total_amount)],
                ['Payment Status', ucfirst($booking->payment_status)],
                ['Transaction ID', $booking->transaction_id ?? 'Pending'],
                ['Booked On', $booking->created_at->format('M d, Y H:i')]
            ] as [$l, $v])
            <div>
                <div class="info-label">{{ $l }}</div>
                <div class="info-val">{{ $v }}</div>
            </div>
            @endforeach
        </div>

        @if($booking->qr_code)
        <div style="margin-top: 2.5rem; display: flex; gap: 1.5rem; align-items: center; background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border);">
            <img src="{{ $booking->qr_code }}" alt="QR Code" style="width: 120px; height: 120px; border-radius: 8px; border: 4px solid #fff; box-shadow: var(--shadow-sm);">
            <div>
                <div style="font-weight: 700; color: var(--primary); margin-bottom: 0.5rem; font-size: 1.1rem;"><i class="fas fa-qrcode"></i> Digital Travel Pass</div>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">Scan this code at your destination for instant verification and hotel check-in.</p>
            </div>
        </div>
        @endif
    </div>

    @if($booking->guide_id)
    <div class="guide-box">
        <div style="display: flex; gap: 1.5rem; align-items: center; margin-bottom: 1.5rem;">
            <img src="{{ $booking->guide->avatar_url }}" alt="Guide" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: var(--shadow-sm);">
            <div>
                <div style="font-size: 0.8rem; font-weight: 600; color: #0d9488; text-transform: uppercase; margin-bottom: 0.25rem;">Local Manager Assigned</div>
                <div style="font-size: 1.4rem; font-weight: 700; color: var(--text-main);">{{ $booking->guide->name }}</div>
                <div style="color: var(--text-muted); font-size: 0.95rem;">Expert in {{ $booking->package?->destination?->name ?? 'the local area' }}</div>
            </div>
        </div>
        <a href="{{ route('chatbot.index') }}" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.75rem;"><i class="fas fa-comments"></i> Message {{ explode(' ', $booking->guide->name)[0] }}</a>
    </div>
    @endif

    @if($booking->event_log)
    <div class="booking-card">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;"><i class="fas fa-history" style="color: var(--primary);"></i> Booking History</h3>
        @foreach(array_reverse($booking->event_log) as $event)
        <div style="display: flex; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
            <i class="fas fa-circle" style="color: var(--primary); font-size: 0.5rem; margin-top: 0.45rem;"></i>
            <div>
                <div style="font-weight: 600; font-size: 0.95rem; color: var(--text-main);">{{ str_replace('_',' ',ucfirst($event['event'])) }}</div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($event['timestamp'])->format('M d, Y h:i A') }}</div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($booking->booking_status==='confirmed' && !$booking->isCancelled())
    <div class="booking-card" style="border-color: #fca5a5;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #ef4444; margin-bottom: 1rem;"><i class="fas fa-ban"></i> Cancel Booking</h3>
        <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
            @csrf
            <div style="margin-bottom: 1rem;">
                <textarea name="reason" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-family: 'Inter', sans-serif;" rows="2" placeholder="Reason for cancellation..." required></textarea>
            </div>
            <button type="submit" class="btn" style="background: #ef4444; color: #fff;" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel Booking</button>
        </form>
    </div>
    @endif
</div>
@endsection
