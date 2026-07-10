@extends('layouts.app')
@section('title', $package->title)
@section('content')

<style>
/* CLEAN SAAS STYLES FOR SHOW PAGE */
.hero-banner {
    height: 380px; position: relative; overflow: hidden; background: #0f172a; margin-top: -70px;
}
.hero-img {
    width: 100%; height: 100%; object-fit: cover; opacity: 0.65;
}
.hero-overlay {
    position: absolute; inset: 0; background: linear-gradient(to top, rgba(15, 23, 42, 0.9) 0%, transparent 100%);
}
.hero-content {
    position: absolute; bottom: 2rem; left: 0; width: 100%;
}
.hero-title {
    font-size: 2.5rem; font-weight: 700; color: #fff; margin-bottom: 0.25rem; letter-spacing: -0.5px;
}
.hero-meta { color: #cbd5e1; font-size: 1rem; display: flex; align-items: center; gap: 1rem; }

.badge-category {
    background: #f0fdfa; color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; display: inline-block; margin-bottom: 0.5rem;
}

.content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem; padding-bottom: 4rem; }
@media(max-width: 992px) { .content-grid { grid-template-columns: 1fr; } }

.card-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 1.25rem; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem; }
.card-title i { color: var(--primary); }

.list-item { display: flex; gap: 0.75rem; margin-bottom: 0.75rem; font-size: 0.95rem; color: var(--text-muted); }
.list-item i { color: var(--primary); margin-top: 0.25rem; flex-shrink: 0; }

.sidebar-card { background: #fff; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); margin-bottom: 1.5rem; }
.sidebar-sticky { position: sticky; top: 90px; }

.highlight-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

/* REVIEWS */
.review-item { padding: 1.25rem 0; border-bottom: 1px solid var(--border); }
.review-item:last-child { border-bottom: none; }
.review-header { display: flex; justify-content: space-between; margin-bottom: 0.75rem; }
.reviewer-info { display: flex; align-items: center; gap: 0.75rem; }
.reviewer-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
.reviewer-name { font-weight: 600; font-size: 0.95rem; color: var(--text-main); }
.review-date { font-size: 0.75rem; color: var(--text-muted); }
</style>

<div class="hero-banner">
    <img src="{{ $package->image ? $package->image_url : ($package->destination?->image_url ?? $package->image_url) }}" alt="{{ $package->title }}" class="hero-img">
    <div class="hero-overlay"></div>
    <div class="hero-content container">
        <span class="badge-category">{{ ucfirst($package->package_type) }}</span>
        <h1 class="hero-title">{{ $package->title }}</h1>
        <div class="hero-meta">
            <span><i class="fas fa-map-marker-alt"></i> {{ $package->destination->name }}, {{ $package->destination->country }}</span>
            <span>&bull;</span>
            <span><i class="fas fa-clock"></i> {{ $package->duration_days }} days</span>
            <span>&bull;</span>
            <span><i class="fas fa-users"></i> Max {{ $package->max_group_size }} people</span>
        </div>
    </div>
</div>

<div class="container content-grid">
    <div>
        <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
            <h2 class="card-title"><i class="fas fa-info-circle"></i> About This Package</h2>
            <p style="color: var(--text-muted); line-height: 1.7;">{{ $package->description }}</p>
            
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1.5rem;">
                @if($package->discount_percent > 0)
                <span style="background: #fef2f2; color: #ef4444; padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">-{{ $package->discount_percent }}% SALE</span>
                @endif
                <span style="background: #f8fafc; border: 1px solid var(--border); color: var(--text-muted); padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">{{ ucfirst($package->difficulty_level) }}</span>
                <span style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669; padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">{{ $package->cancellation_policy }}</span>
            </div>
        </div>

        @if($package->highlights)
        <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
            <h2 class="card-title"><i class="fas fa-star" style="color: #fbbf24;"></i> Highlights</h2>
            <div class="highlight-grid">
                @foreach($package->highlights as $hl)
                <div class="list-item" style="margin-bottom: 0;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ $hl }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            @if($package->inclusions)
            <div class="card" style="padding: 1.5rem;">
                <h3 style="font-weight: 600; font-size: 1.1rem; margin-bottom: 1rem; color: var(--primary);"><i class="fas fa-circle-check"></i> What's Included</h3>
                @foreach($package->inclusions as $inc)
                <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--primary); width: 16px;"></i> {{ $inc }}</div>
                @endforeach
            </div>
            @endif
            @if($package->exclusions)
            <div class="card" style="padding: 1.5rem;">
                <h3 style="font-weight: 600; font-size: 1.1rem; margin-bottom: 1rem; color: #ef4444;"><i class="fas fa-circle-xmark"></i> Not Included</h3>
                @foreach($package->exclusions as $exc)
                <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-xmark" style="color: #ef4444; width: 16px;"></i> {{ $exc }}</div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2 class="card-title" style="margin: 0;"><i class="fas fa-star" style="color: #fbbf24;"></i> Reviews</h2>
            </div>
            
            @forelse($package->reviews as $review)
            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="{{ $review->user->avatar_url }}" class="reviewer-img">
                        <div>
                            <div class="reviewer-name">{{ $review->user->name }}</div>
                            <div class="review-date">{{ $review->created_at->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div>
                        <div style="color: #fbbf24; font-size: 0.85rem;">{{ str_repeat('★', $review->rating) }}</div>
                    </div>
                </div>
                <p style="font-size: 0.95rem; color: var(--text-muted);">{{ $review->body }}</p>
            </div>
            @empty
            <p style="color: var(--text-muted); padding: 1rem 0;">No reviews yet.</p>
            @endforelse
        </div>
    </div>

    <div class="sidebar-sticky">
        <div class="sidebar-card">
            <div style="margin-bottom: 1.5rem;">
                @if($package->original_price > $package->price_per_person)
                <div style="font-size: 0.9rem; color: var(--text-muted); text-decoration: line-through;">Was ₹{{ number_format($package->original_price) }}</div>
                @endif
                <div style="font-size: 2.2rem; font-weight: 700; color: var(--text-main); line-height: 1;">₹{{ number_format($package->discounted_price) }}<span style="font-size: 1rem; font-weight: 400; color: var(--text-muted);">/person</span></div>
                @if($package->discount_percent > 0)
                <div style="color: #ef4444; font-size: 0.85rem; font-weight: 600; margin-top: 0.25rem;">You save ₹{{ number_format($package->original_price - $package->discounted_price) }}</div>
                @endif
            </div>

            <div style="background: #f8fafc; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; border: 1px solid var(--border);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem;">
                    <span style="color: var(--text-muted);">Duration</span>
                    <span style="font-weight: 600;">{{ $package->duration_days }} days</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem;">
                    <span style="color: var(--text-muted);">Group Size</span>
                    <span style="font-weight: 600;">Max {{ $package->max_group_size }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                    <span style="color: var(--text-muted);">Cancellation</span>
                    <span style="font-weight: 600; color: #059669;">{{ $package->cancellation_policy }}</span>
                </div>
            </div>

            @auth
            <a href="{{ route('bookings.create').'?package_id='.$package->id }}" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.8rem; font-size: 1rem;">Book Now</a>
            <button onclick="toggleWishlistPkg('{{ $package->id }}', this)" class="btn btn-outline" style="width: 100%; justify-content: center; margin-top: 0.75rem;">
                <i class="fas fa-heart" style="{{ $isWishlisted ? 'color:#ef4444' : '' }}"></i> {{ $isWishlisted ? 'Remove Wishlist' : 'Add to Wishlist' }}
            </button>
            @else
            <a href="{{ route('register') }}" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.8rem; font-size: 1rem;">Sign Up to Book</a>
            @endauth
        </div>

        @if($relatedPackages->count())
        <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; margin-top: 2rem;">Similar Packages</h3>
        @foreach($relatedPackages as $rp)
        <a href="{{ route('packages.show', $rp) }}" class="sidebar-card" style="display: block; padding: 1rem; transition: 0.2s;">
            <div style="font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem;">{{ $rp->title }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="far fa-clock"></i> {{ $rp->duration_days }} Days</div>
            <div style="font-weight: 700; color: var(--primary);">₹{{ number_format($rp->price_per_person) }}</div>
        </a>
        @endforeach
        @endif
    </div>
</div>

<script>
async function toggleWishlistPkg(id, btn) {
    const res = await fetch('{{ route("wishlist.toggle") }}', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({ type: 'package', id: id })
    });
    const data = await res.json();
    btn.querySelector('i').style.color = data.wishlisted ? '#ef4444' : '';
    btn.childNodes[2].textContent = data.wishlisted ? ' Remove Wishlist' : ' Add to Wishlist';
}
</script>
@endsection
