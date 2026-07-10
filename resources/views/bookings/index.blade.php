@extends('layouts.app')
@section('title','My Bookings')
@section('content')

<style>
.page-header {
    background-color: #f1f5f9;
    padding: 3rem 0;
    border-bottom: 1px solid var(--border);
}
.page-header h1 {
    font-size: 2rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem; letter-spacing: -0.5px;
}
.page-header p { font-size: 1rem; color: var(--text-muted); }

.booking-card {
    background: #fff; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm);
    padding: 1.5rem; margin-bottom: 1.25rem; display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; transition: 0.2s;
}
.booking-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }

.b-ref { font-weight: 700; font-size: 1.1rem; color: var(--text-main); margin-right: 0.5rem; }
.b-status { padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
.status-confirmed { background: #ecfdf5; color: #059669; }
.status-cancelled { background: #fef2f2; color: #ef4444; }
.status-pending { background: #fffbeb; color: #d97706; }
.b-type { background: #f0fdfa; color: var(--primary); padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; margin-left: 0.5rem;}

.b-title { font-weight: 600; font-size: 1.15rem; color: var(--text-main); margin-top: 0.5rem; margin-bottom: 0.25rem; }
.b-meta { font-size: 0.9rem; color: var(--text-muted); }
.b-price { font-size: 1.5rem; font-weight: 700; color: var(--text-main); line-height: 1; }
.b-pay-status { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px;}

.section-title { font-size: 1.4rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem; margin-top: 2.5rem; }
</style>

<div class="page-header">
    <div class="container">
        <h1>My Bookings</h1>
        <p>{{ $bookings->total() }} total bookings</p>
    </div>
</div>

<div class="container" style="padding-bottom: 4rem;">
    @php
        $premiumPlans = $bookings->filter(fn($b) => $b->booking_type === 'itinerary');
        $tripBookings = $bookings->filter(fn($b) => $b->booking_type !== 'itinerary');
    @endphp

    {{-- Premium Plans Section --}}
    @if($premiumPlans->count() > 0)
    <h2 class="section-title"><i class="fas fa-crown" style="color: #fbbf24;"></i> AI Premium Itinerary Plans</h2>
    @foreach($premiumPlans as $booking)
    <div class="booking-card" style="border-left: 4px solid #fbbf24;">
        <div style="flex: 1;">
            <div style="display: flex; align-items: center; flex-wrap: wrap;">
                <span class="b-ref">{{ $booking->booking_reference }}</span>
                <span class="b-status {{ $booking->booking_status=='confirmed'?'status-confirmed':($booking->booking_status=='cancelled'?'status-cancelled':'status-pending') }}">{{ ucfirst($booking->booking_status) }}</span>
                <span class="b-type">Premium Plan</span>
            </div>
            <div class="b-title">{{ $booking->itinerary?->title ?? 'Custom Itinerary Booking' }}</div>
            <div class="b-meta"><i class="far fa-calendar-alt"></i> {{ $booking->created_at?->format('M d, Y') }}</div>
        </div>
        <div style="text-align: right; flex-shrink: 0;">
            <div class="b-price">₹{{ number_format($booking->total_amount) }}</div>
            <div class="b-pay-status">{{ ucfirst($booking->payment_status) }}</div>
            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                @if($booking->itinerary_id)
                <a href="{{ route('itineraries.show', $booking->itinerary_id) }}" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.85rem;"><i class="fas fa-map"></i> View Plan</a>
                @endif
                <a href="{{ route('bookings.show',$booking) }}" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">Details</a>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    {{-- Packages & Destinations Section --}}
    <h2 class="section-title"><i class="fas fa-suitcase-rolling" style="color: var(--primary);"></i> Packages & Stays</h2>
    @forelse($tripBookings as $booking)
    <div class="booking-card">
        @if($booking->package?->destination)
        <img src="{{ $booking->package->destination->image_url }}" style="width: 120px; height: 90px; object-fit: cover; border-radius: 8px; flex-shrink: 0;">
        @endif
        <div style="flex: 1;">
            <div style="display: flex; align-items: center; flex-wrap: wrap;">
                <span class="b-ref">{{ $booking->booking_reference }}</span>
                <span class="b-status {{ $booking->booking_status=='confirmed'?'status-confirmed':($booking->booking_status=='cancelled'?'status-cancelled':'status-pending') }}">{{ ucfirst($booking->booking_status) }}</span>
                <span class="b-type">{{ ucfirst($booking->booking_type) }}</span>
            </div>
            <div class="b-title">{{ $booking->package?->title ?? $booking->hotel?->name ?? 'Custom Booking' }}</div>
            <div class="b-meta">
                <span><i class="fas fa-map-marker-alt"></i> {{ $booking->package?->destination?->name ?? $booking->hotel?->destination?->name ?? 'N/A' }}</span>
                <span style="margin: 0 0.5rem;">&bull;</span>
                <span><i class="far fa-calendar-alt"></i> {{ $booking->check_in?->format('M d, Y') }}</span>
                <span style="margin: 0 0.5rem;">&bull;</span>
                <span><i class="fas fa-users"></i> {{ $booking->adults }} Adults{{ $booking->children ? ', '.$booking->children.' Kids' : '' }}</span>
            </div>
        </div>
        <div style="text-align: right; flex-shrink: 0;">
            <div class="b-price">₹{{ number_format($booking->total_amount) }}</div>
            <div class="b-pay-status">{{ ucfirst($booking->payment_status) }}</div>
            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                <a href="{{ route('itineraries.create') }}{{ $booking->package?->destination_id ? '?destination='.$booking->package->destination_id : '' }}" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.85rem;"><i class="fas fa-wand-magic-sparkles"></i> AI Planner</a>
                <a href="{{ route('bookings.show',$booking) }}" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">Details</a>
            </div>
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 4rem; background: #fff; border: 1px dashed var(--border); border-radius: 12px;">
        <i class="fas fa-ticket-alt" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem; display: block;"></i>
        <h3 style="font-size: 1.2rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem;">No Bookings Yet</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">You haven't booked any packages or stays yet.</p>
        <a href="{{ route('packages.index') }}" class="btn btn-primary">Browse Packages</a>
    </div>
    @endforelse

    <div style="margin-top: 2rem;">{{ $bookings->links() }}</div>
</div>
@endsection
