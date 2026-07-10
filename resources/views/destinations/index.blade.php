@extends('layouts.app')
@section('title','Destinations')
@section('content')

<style>
/* DESTINATIONS HEADER */
.page-header {
    background-color: #f1f5f9;
    padding: 4rem 0 3rem;
    border-bottom: 1px solid var(--border);
    text-align: center;
}
.page-header h1 {
    font-size: 2.2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main); letter-spacing: -0.5px;
}
.page-header p { font-size: 1.1rem; color: var(--text-muted); max-width: 600px; margin: 0 auto; }

/* FILTER PANEL */
.filter-panel {
    background: #fff; border: 1px solid var(--border); border-radius: 12px;
    padding: 1.5rem; margin: -2rem auto 3rem; box-shadow: var(--shadow-md);
    display: flex; gap: 1rem; flex-wrap: wrap; position: relative; z-index: 10; max-width: 1000px;
}
.filter-group { flex: 1; min-width: 160px; }
.filter-label { font-size: 0.75rem; font-weight: 600; color: var(--text-main); text-transform: uppercase; margin-bottom: 0.4rem; display: block; }
.filter-input { 
    width: 100%; border: 1px solid var(--border); background: #f8fafc; color: var(--text-main);
    padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.9rem; outline: none; transition: 0.2s; font-family: 'Inter', sans-serif;
}
.filter-input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);}

/* DESTINATION CARD */
.d-card {
    background: #fff; border-radius: 12px; overflow: hidden;
    box-shadow: var(--shadow-sm); border: 1px solid var(--border);
    transition: 0.2s; display: flex; flex-direction: column; height: 100%;
}
.d-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
.d-img-container { height: 220px; position: relative; overflow: hidden; }
.d-img { width: 100%; height: 100%; object-fit: cover; }
.d-badge {
    position: absolute; top: 1rem; left: 1rem; background: #fff; color: var(--text-main);
    padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; box-shadow: var(--shadow-sm);
}
.d-wishlist {
    position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.9); color: var(--text-muted);
    width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    border: none; cursor: pointer; transition: 0.2s; box-shadow: var(--shadow-sm);
}
.d-wishlist:hover { color: #ef4444; }

.d-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
.d-title { font-size: 1.2rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--text-main); }
.d-location { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.4rem; }
.d-desc { color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 1.5rem; flex: 1; }

.d-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 1rem; margin-top: auto; }
.d-price { font-weight: 700; font-size: 1.1rem; color: var(--text-main); }
.d-price span { font-size: 0.8rem; font-weight: 400; color: var(--text-muted); }

.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }
@media(max-width: 992px) { .grid-3 { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 768px) { .grid-3 { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <div class="container">
        <h1>Explore Destinations</h1>
        <p>Discover beautiful locations curated for your next adventure.</p>
    </div>
</div>

<div class="container">
    <form method="GET" action="{{ route('destinations.index') }}" class="filter-panel">
        <div class="filter-group">
            <label class="filter-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="filter-input" placeholder="City or region...">
        </div>
        <div class="filter-group">
            <label class="filter-label">Category</label>
            <select name="category" class="filter-input">
                <option value="">All Categories</option>
                <option value="beaches">🏖️ Beaches</option>
                <option value="mountains">⛰️ Mountains</option>
                @foreach($categories as $cat)
                    @if(!in_array(strtolower($cat), ['beaches','mountains']))
                        <option value="{{ $cat }}" {{ request('category')==$cat?'selected':'' }}>{{ ucfirst($cat) }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Country</label>
            <select name="country" class="filter-input">
                <option value="">Global</option>
                @foreach($countries as $c)
                    <option value="{{ $c }}" {{ request('country')==$c?'selected':'' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group" style="flex: 0.5;">
            <label class="filter-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary" style="width: 100%; height: 42px; justify-content: center; border-radius: 8px;"><i class="fas fa-search"></i></button>
        </div>
    </form>

    <div style="padding-bottom: 4rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">All Destinations</h2>
        <div class="grid-3">
            @forelse($destinations as $dest)
            <div class="d-card">
                <div class="d-img-container">
                    <img src="{{ $dest->image_url ?? 'https://images.unsplash.com/photo-1512453979436-5a53696511cc?auto=format&fit=crop&q=80&w=800' }}" class="d-img" alt="{{ $dest->name }}">
                    <div class="d-badge">{{ $dest->category ?? 'Destination' }}</div>
                    @auth
                    <button class="d-wishlist" data-type="destination" data-id="{{ $dest->id }}" onclick="toggleWishlist(this)" style="color:{{ auth()->user()->wishlists()->where('destination_id', $dest->id)->exists() ? '#ef4444' : 'inherit' }}">
                        <i class="fas fa-heart"></i>
                    </button>
                    @endauth
                </div>
                <div class="d-body">
                    <a href="{{ route('destinations.show', $dest) }}" style="text-decoration:none; color:inherit; display:block; flex:1;">
                        <div class="d-title">{{ $dest->name }}</div>
                        <div class="d-location"><i class="fas fa-map-marker-alt"></i> {{ $dest->country }}</div>
                        <div class="d-desc">{{ Str::limit($dest->description, 100) }}</div>
                    </a>
                    <div class="d-footer">
                        <div class="d-price">₹{{ number_format($dest->base_price_economy ?? 15000) }} <span>/ day</span></div>
                        <a href="{{ route('itineraries.create') }}?destination={{ urlencode($dest->name) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Plan Trip</a>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 12px; border: 1px dashed var(--border);">
                <i class="fas fa-map" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.25rem; font-weight: 600;">No Destinations Found</h3>
                <p style="color: var(--text-muted);">Try adjusting your search filters.</p>
                <a href="{{ route('destinations.index') }}" class="btn btn-outline" style="margin-top: 1rem;">Clear Filters</a>
            </div>
            @endforelse
        </div>
        
        <div style="margin-top: 3rem; display: flex; justify-content: center;">
            {{ $destinations->links() }}
        </div>
    </div>
</div>

<script>
async function toggleWishlist(btn) {
    const res = await fetch('{{ route("wishlist.toggle") }}', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({ type: btn.dataset.type, id: btn.dataset.id })
    });
    const data = await res.json();
    btn.style.color = data.wishlisted ? '#ef4444' : 'inherit';
}
</script>
@endsection
