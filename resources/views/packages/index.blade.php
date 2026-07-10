@extends('layouts.app')
@section('title','Travel Packages')
@section('content')

<style>
/* PACKAGES HEADER */
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

/* PACKAGE CARD */
.pkg-card {
    background: #fff; border-radius: 12px; overflow: hidden;
    box-shadow: var(--shadow-sm); border: 1px solid var(--border);
    transition: 0.2s; display: flex; flex-direction: column; height: 100%;
}
.pkg-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
.pkg-img-container { height: 220px; position: relative; overflow: hidden; }
.pkg-img { width: 100%; height: 100%; object-fit: cover; }

.pkg-offer {
    position: absolute; top: 1rem; left: 1rem; background: #fffbeb; color: #d97706;
    padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; box-shadow: var(--shadow-sm); border: 1px solid #fde68a;
}
.pkg-discount {
    position: absolute; top: 1rem; right: 1rem; background: #ef4444; color: #fff;
    padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; box-shadow: var(--shadow-sm);
}
.pkg-type {
    position: absolute; bottom: 1rem; right: 1rem; background: rgba(0,0,0,0.7); color: #fff;
    padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 500;
}

.pkg-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
.pkg-title { font-size: 1.15rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main); line-height: 1.3;}
.pkg-desc { color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 1rem; flex: 1; }

.pkg-highlights { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.5rem; }
.pkg-highlight { font-size: 0.85rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem; }
.pkg-highlight i { color: var(--primary); width: 16px; text-align: center; }

.pkg-footer { display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid var(--border); padding-top: 1rem; margin-top: auto; }
.pkg-price-old { text-decoration: line-through; color: #ef4444; font-size: 0.8rem; display: block; margin-bottom: 0.1rem; }
.pkg-price { font-weight: 700; font-size: 1.25rem; color: var(--text-main); line-height: 1; }
.pkg-price span { font-size: 0.8rem; font-weight: 400; color: var(--text-muted); }

.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }
@media(max-width: 992px) { .grid-3 { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 768px) { .grid-3 { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <div class="container">
        <h1>Travel Packages</h1>
        <p>Expertly crafted itineraries for unforgettable experiences.</p>
    </div>
</div>

<div class="container">
    <form method="GET" action="{{ route('packages.index') }}" class="filter-panel">
        <div class="filter-group">
            <label class="filter-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="filter-input" placeholder="Find a package...">
        </div>
        <div class="filter-group">
            <label class="filter-label">Experience Type</label>
            <select name="type" class="filter-input">
                <option value="">All Types</option>
                <option value="luxury">💎 Luxury</option>
                <option value="adventure">🧗 Adventure</option>
                <option value="romantic">❤️ Romantic</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Max Budget (₹)</label>
            <input type="number" name="budget_max" value="{{ request('budget_max') }}" class="filter-input" placeholder="No Limit">
        </div>
        <div class="filter-group" style="flex: 0.5;">
            <label class="filter-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary" style="width: 100%; height: 42px; justify-content: center; border-radius: 8px;"><i class="fas fa-search"></i></button>
        </div>
    </form>

    <div style="padding-bottom: 4rem;">
        <div class="grid-3">
            @forelse($packages as $pkg)
            <div class="pkg-card">
                <div class="pkg-img-container">
                    <img src="{{ $pkg->destination?->image_url ?? 'https://images.unsplash.com/photo-1499856871958-5b9627545d1a?auto=format&fit=crop&q=80&w=800' }}" class="pkg-img" alt="{{ $pkg->name }}">
                    
                    @if($pkg->special_offer)
                    <div class="pkg-offer"><i class="fas fa-star"></i> {{ $pkg->special_offer }}</div>
                    @endif
                    
                    @if($pkg->discount_percentage > 0)
                    <div class="pkg-discount">-{{ $pkg->discount_percentage }}%</div>
                    @endif
                    
                    <div class="pkg-type">{{ $pkg->type ?? 'Standard' }}</div>
                </div>
                
                <div class="pkg-body">
                    <div class="pkg-title">{{ $pkg->name }}</div>
                    <div class="pkg-desc">{{ Str::limit($pkg->description, 90) }}</div>
                    
                    <div class="pkg-highlights">
                        <div class="pkg-highlight"><i class="far fa-clock"></i> {{ $pkg->duration_days }} Days / {{ $pkg->duration_nights }} Nights</div>
                        <div class="pkg-highlight"><i class="fas fa-bed"></i> {{ ucfirst($pkg->hotel_tier ?? 'standard') }} Accommodations</div>
                        <div class="pkg-highlight"><i class="fas fa-users"></i> Up to {{ $pkg->max_persons ?? 2 }} Travelers</div>
                    </div>
                    
                    <div class="pkg-footer">
                        <div>
                            @if($pkg->discount_percentage > 0)
                                @php 
                                    $oldPrice = $pkg->price_per_person / (1 - ($pkg->discount_percentage/100));
                                @endphp
                                <span class="pkg-price-old">₹{{ number_format($oldPrice) }}</span>
                            @endif
                            <div class="pkg-price">₹{{ number_format($pkg->price_per_person) }} <span>/ person</span></div>
                        </div>
                        <a href="{{ route('packages.show', $pkg) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">View</a>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 12px; border: 1px dashed var(--border);">
                <i class="fas fa-box-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.25rem; font-weight: 600;">No Packages Found</h3>
                <p style="color: var(--text-muted);">We couldn't find any itineraries matching your criteria.</p>
                <a href="{{ route('packages.index') }}" class="btn btn-outline" style="margin-top: 1rem;">Clear Filters</a>
            </div>
            @endforelse
        </div>
        
        <div style="margin-top: 3rem; display: flex; justify-content: center;">
            {{ $packages->links() }}
        </div>
    </div>
</div>
@endsection
