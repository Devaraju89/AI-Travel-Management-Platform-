@extends('layouts.app')
@section('title','Traveler Dashboard')
@section('content')

<style>
.dashboard-header {
    background: #1e293b; /* Slate 800 */
    padding: 3rem 0;
    color: #fff;
}
.profile-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 2rem;
    box-shadow: var(--shadow-sm);
    margin-top: -3rem;
    position: relative;
    z-index: 10;
}
.profile-avatar {
    width: 90px; height: 90px; border-radius: 50%; object-fit: cover;
    border: 3px solid #fff; box-shadow: var(--shadow-md);
}
.profile-info h1 {
    font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; color: var(--text-main);
}
.profile-badge {
    background: #f0fdfa; color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;
}
.stat-card {
    background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem;
    display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm); transition: 0.2s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.stat-icon {
    width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
}
.stat-val { font-size: 1.5rem; font-weight: 700; color: var(--text-main); line-height: 1.2;}
.stat-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }

.trip-card {
    display: flex; gap: 1.5rem; background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem;
    box-shadow: var(--shadow-sm); transition: 0.2s;
}
.trip-card:hover { box-shadow: var(--shadow-md); border-color: #cbd5e1; }
.trip-img { width: 120px; height: 120px; border-radius: 8px; object-fit: cover; }
.trip-details { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
.trip-title { font-size: 1.1rem; font-weight: 600; color: var(--text-main); }
.trip-date { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;}
</style>

<div class="dashboard-header">
    <div class="container" style="padding-bottom: 2rem;">
        <div style="font-size: 0.85rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Next Trip In</div>
        <div style="font-size: 2.5rem; font-weight: 700; line-height: 1;">14 Days</div>
        <div style="font-size: 1.1rem; margin-top: 0.5rem; color: #cbd5e1;">Santorini Summer Escape</div>
    </div>
</div>

<div class="container" style="padding-bottom: 4rem;">
    <div class="profile-card">
        <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="profile-avatar" alt="Avatar">
        <div class="profile-info">
            <div style="color: var(--text-muted); font-size: 0.85rem;">Welcome back,</div>
            <h1>{{ $user->name }}</h1>
            <div class="profile-badge"><i class="fas fa-crown"></i> Gold Member</div>
        </div>
        <div style="margin-left: auto; display: flex; gap: 1rem;">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline" style="color: #ef4444; border-color: #fca5a5;"><i class="fas fa-shield-alt"></i> Admin</a>
            @endif
            <a href="{{ route('itineraries.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Plan New Trip</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-top: 2rem;">
        <div class="stat-card">
            <div>
                <div class="stat-val">{{ $stats['loyalty_points'] ?? '2,450' }}</div>
                <div class="stat-label">Travel Points</div>
            </div>
            <div class="stat-icon" style="background: #fffbeb; color: #d97706;"><i class="fas fa-star"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-val">{{ $stats['packages_bought'] ?? '4' }}</div>
                <div class="stat-label">Past Trips</div>
            </div>
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;"><i class="fas fa-plane"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-val">{{ $stats['premium_bought'] ?? '1' }}</div>
                <div class="stat-label">Active Bookings</div>
            </div>
            <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;"><i class="fas fa-ticket-alt"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-val">12</div>
                <div class="stat-label">Wishlist</div>
            </div>
            <div class="stat-icon" style="background: #fdf2f8; color: #db2777;"><i class="fas fa-heart"></i></div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 3rem;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main);">My Bookings</h2>
                <a href="{{ route('bookings.index') }}" style="color: var(--primary); font-weight: 500; font-size: 0.9rem;">View All &rarr;</a>
            </div>

            @forelse($recentBookings ?? [] as $booking)
            <div class="trip-card">
                <img src="{{ $booking->package?->destination?->image_url ?? 'https://images.unsplash.com/photo-1499856871958-5b9627545d1a?auto=format&fit=crop&q=80&w=400' }}" class="trip-img" alt="Trip">
                <div class="trip-details">
                    <div>
                        <div style="display: flex; justify-content: space-between;">
                            <div class="trip-title">{{ $booking->package?->destination?->name ?? 'Paris, France' }}</div>
                            <span style="background: #ecfdf5; color: #059669; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Confirmed</span>
                        </div>
                        <div class="trip-date"><i class="far fa-calendar-alt"></i> {{ $booking->check_in?->format('M d, Y') ?? 'Aug 14, 2026' }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">Ref: <strong>{{ $booking->booking_reference ?? '#BKG-9923' }}</strong></div>
                    </div>
                    <div style="text-align: right;">
                        <a href="{{ route('bookings.show', $booking->id ?? 1) }}" class="btn btn-outline">View Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="trip-card">
                <img src="https://images.unsplash.com/photo-1512453979436-5a53696511cc?auto=format&fit=crop&q=80&w=400" class="trip-img" alt="Trip">
                <div class="trip-details">
                    <div>
                        <div style="display: flex; justify-content: space-between;">
                            <div class="trip-title">Santorini Summer Escape</div>
                            <span style="background: #ecfdf5; color: #059669; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Confirmed</span>
                        </div>
                        <div class="trip-date"><i class="far fa-calendar-alt"></i> Aug 14, 2026 - Aug 21, 2026</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">Ref: <strong>#BKG-9923</strong></div>
                    </div>
                    <div style="text-align: right;">
                        <a href="#" class="btn btn-outline">View Details</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem;">Recommended For You</h2>
            <div class="card">
                <img src="https://images.unsplash.com/photo-1534008897995-27a23e859048?auto=format&fit=crop&q=80&w=600" style="width: 100%; height: 180px; object-fit: cover;" alt="Kyoto">
                <div style="padding: 1.5rem;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: var(--primary); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.5px;">98% Match</div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">Kyoto Heritage Tour</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem; line-height: 1.5;">Based on your recent searches, explore the serene temples of Kyoto.</p>
                    <a href="{{ route('packages.index') }}" class="btn btn-outline" style="width: 100%; justify-content: center;">View Package</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
