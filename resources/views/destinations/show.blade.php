@extends('layouts.app')
@section('title', $destination->name)
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

.stats-box {
    background: rgba(255,255,255,0.1); backdrop-filter: blur(8px); padding: 0.75rem 1.25rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); text-align: center;
}

.content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem; padding-bottom: 4rem; }
@media(max-width: 992px) { .content-grid { grid-template-columns: 1fr; } }

.card-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 1.25rem; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem; }
.card-title i { color: var(--primary); }

.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1.5rem; }
.info-box { background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border); }
.info-label { font-size: 0.75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem; }
.info-val { font-weight: 600; color: var(--text-main); }

.list-item { display: flex; gap: 0.75rem; margin-bottom: 0.75rem; font-size: 0.95rem; color: var(--text-muted); }
.list-item i { color: var(--primary); margin-top: 0.25rem; flex-shrink: 0; }

.sidebar-card { background: #fff; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); margin-bottom: 1.5rem; }
.sidebar-sticky { position: sticky; top: 90px; }

/* REVIEWS */
.review-item { padding: 1.25rem 0; border-bottom: 1px solid var(--border); }
.review-item:last-child { border-bottom: none; }
.review-header { display: flex; justify-content: space-between; margin-bottom: 0.75rem; }
.reviewer-info { display: flex; align-items: center; gap: 0.75rem; }
.reviewer-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
.reviewer-name { font-weight: 600; font-size: 0.95rem; color: var(--text-main); }
.review-date { font-size: 0.75rem; color: var(--text-muted); }

.form-control { width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-size: 0.95rem; font-family: 'Inter', sans-serif; margin-bottom: 1rem;}
.form-control:focus { border-color: var(--primary); outline: none; }
</style>

<div class="hero-banner">
    <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" class="hero-img">
    <div class="hero-overlay"></div>
    <div class="hero-content container">
        <div style="display:flex; justify-content:space-between; align-items:flex-end;">
            <div>
                <span class="badge-category">{{ ucfirst($destination->category) }}</span>
                <h1 class="hero-title">{{ $destination->name }}</h1>
                <div class="hero-meta"><i class="fas fa-map-marker-alt"></i> {{ $destination->city }}, {{ $destination->country }}</div>
            </div>
            <div style="display:flex; align-items:flex-end; gap: 1rem;">
                <div class="stats-box">
                    <div style="font-size: 1.25rem; font-weight: 700; color: #fff;">{{ number_format($destination->avg_rating ?? 0, 1) }} <i class="fas fa-star" style="color:#fbbf24; font-size: 1rem;"></i></div>
                    <div style="font-size: 0.75rem; color: rgba(255,255,255,0.7);">{{ number_format($destination->review_count ?? 0) }} Reviews</div>
                </div>
                @auth
                <button onclick="toggleWishlistDest('{{ $destination->id }}', this)" class="btn btn-outline" style="background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.3);" id="wish-btn">
                    <i class="fas fa-heart" style="{{ $isWishlisted ? 'color:#ef4444' : '' }}"></i> 
                </button>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="container content-grid">
    <div>
        <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
            <h2 class="card-title"><i class="fas fa-info-circle"></i> About</h2>
            <p style="color: var(--text-muted); line-height: 1.7;">{{ $destination->description }}</p>
            
            <div style="margin-top: 1.5rem; border-radius: 8px; overflow: hidden; border: 1px solid var(--border); height: 300px;">
                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode($destination->name . ' ' . $destination->country) }}&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>

            <div class="info-grid">
                @if($destination->climate)
                <div class="info-box">
                    <div class="info-label">Climate</div>
                    <div class="info-val">{{ ucfirst($destination->climate) }}</div>
                </div>
                @endif
                @if($destination->best_season)
                <div class="info-box">
                    <div class="info-label">Best Season</div>
                    <div class="info-val">{{ $destination->best_season }}</div>
                </div>
                @endif
            </div>
        </div>

        @if($destination->safety_tips)
        <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
            <h2 class="card-title"><i class="fas fa-shield-alt"></i> Safety Tips</h2>
            @foreach($destination->safety_tips as $tip)
            <div class="list-item">
                <i class="fas fa-check-circle"></i>
                <span>{{ $tip }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2 class="card-title" style="margin: 0;"><i class="fas fa-star" style="color: #fbbf24;"></i> Reviews</h2>
                @auth<button onclick="document.getElementById('review-form').scrollIntoView({behavior:'smooth'})" class="btn btn-primary">Write Review</button>@endauth
            </div>
            
            @forelse($reviews as $review)
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
                @if($review->title)<div style="font-weight: 600; margin-bottom: 0.25rem;">{{ $review->title }}</div>@endif
                <p style="font-size: 0.95rem; color: var(--text-muted);">{{ $review->body }}</p>
            </div>
            @empty
            <p style="color: var(--text-muted); padding: 1rem 0;">No reviews yet.</p>
            @endforelse
            <div style="margin-top: 1rem;">{{ $reviews->links() }}</div>
        </div>

        @auth
        <div class="card" style="padding: 1.5rem;" id="review-form">
            <h2 class="card-title">Write a Review</h2>
            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" name="destination_id" value="{{ $destination->id }}">
                <input type="hidden" name="reviewable_type" value="destination">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem;">Rating (1-5)</label>
                    <input type="number" name="rating" class="form-control" min="1" max="5" required>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem;">Title</label>
                    <input type="text" name="title" class="form-control">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem;">Review</label>
                    <textarea name="body" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
        @endauth
    </div>

    <div class="sidebar-sticky">
        <div class="sidebar-card" style="background: #f0fdfa; border-color: #ccfbf1;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">Ready to visit {{ $destination->name }}?</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">Let our AI concierge build a custom itinerary just for you.</p>
            <a href="{{ route('itineraries.create') }}?destination={{ urlencode($destination->name) }}" class="btn btn-primary" style="width: 100%;">Plan Trip</a>
        </div>

        @if($destination->packages->count() > 0)
        <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">Available Packages</h3>
        @foreach($destination->packages->take(3) as $pkg)
        <a href="{{ route('packages.show', $pkg) }}" class="sidebar-card" style="display: block; padding: 1rem; transition: 0.2s;">
            <div style="font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem;">{{ $pkg->name }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="far fa-clock"></i> {{ $pkg->duration_days }} Days</div>
            <div style="font-weight: 700; color: var(--primary);">₹{{ number_format($pkg->price_per_person) }}</div>
        </a>
        @endforeach
        @endif
    </div>
</div>

<script>
async function toggleWishlistDest(id, btn) {
    const res = await fetch('{{ route("wishlist.toggle") }}', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({ type: 'destination', id: id })
    });
    const data = await res.json();
    btn.querySelector('i').style.color = data.wishlisted ? '#ef4444' : '#fff';
}
</script>
@endsection
