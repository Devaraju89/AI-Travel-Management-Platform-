@extends('layouts.app')
@section('title', 'TravelMate — Find Your Next Journey')
@section('content')

<style>
/* HERO SECTION */
.hero {
    position: relative;
    height: 600px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #0f172a;
    margin-top: -70px;
}
.hero-img {
    position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.6;
}
.hero-content {
    position: relative; z-index: 1; text-align: center; padding: 0 1.5rem; width: 100%; max-width: 800px; margin-top: 40px;
}
.hero-content h1 {
    font-size: 3rem; font-weight: 700; color: #fff; margin-bottom: 1rem; line-height: 1.2; letter-spacing: -1px;
}
.hero-content p {
    font-size: 1.1rem; color: #cbd5e1; margin-bottom: 2.5rem;
}

/* SEARCH WIDGET */
.search-widget {
    background: #fff;
    border-radius: 12px;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    max-width: 900px;
    margin: 0 auto;
}
.search-input-group {
    display: flex; flex-direction: column; text-align: left;
    flex: 1; border-right: 1px solid var(--border); padding: 0.5rem 1rem;
}
.search-input-group:last-of-type { border-right: none; }
.search-label { font-size: 0.75rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem;}
.search-input-group input, .search-input-group select { 
    border: none; outline: none; font-size: 0.95rem; color: var(--text-muted); background: transparent; width: 100%; font-family: 'Inter', sans-serif;
}
.search-btn {
    background: var(--primary); color: #fff; height: 50px; width: 50px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    border: none; cursor: pointer; transition: 0.2s; flex-shrink: 0; margin-left: 0.5rem;
}
.search-btn:hover { background: var(--primary-hover); }

/* SECTIONS */
.section-title { font-size: 1.8rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem; letter-spacing: -0.5px;}
.section-subtitle { font-size: 1rem; color: var(--text-muted); margin-bottom: 2.5rem;}

/* DESTINATION GRID */
.dest-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;
}
.d-card {
    background: #fff; border: 1px solid var(--border); border-radius: 12px; overflow: hidden;
    transition: 0.2s; display: block; box-shadow: var(--shadow-sm);
}
.d-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.d-img { width: 100%; height: 200px; object-fit: cover; }
.d-body { padding: 1.25rem; }
.d-name { font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem; }
.d-info { font-size: 0.85rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;}

/* FEATURES */
.features-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;
}
.feature-box {
    padding: 2rem; background: #fff; border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow-sm);
}
.feature-icon {
    width: 50px; height: 50px; background: #f0fdfa; color: var(--primary); border-radius: 10px;
    display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem;
}
.feature-title { font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.75rem; }
.feature-desc { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; }

@media (max-width: 992px) {
    .dest-grid { grid-template-columns: repeat(2, 1fr); }
    .features-grid { grid-template-columns: 1fr; }
    .search-widget { flex-direction: column; padding: 1.5rem; border-radius: 12px; gap: 1rem; }
    .search-input-group { border-right: none; border-bottom: 1px solid var(--border); padding: 0.5rem 0; width: 100%; }
    .search-input-group:last-of-type { border-bottom: none; }
    .search-btn { width: 100%; margin-left: 0; margin-top: 1rem; }
    .hero-content h1 { font-size: 2.2rem; }
}
@media (max-width: 576px) {
    .dest-grid { grid-template-columns: 1fr; }
}
</style>

<div class="hero">
    <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=2000" class="hero-img" alt="Travel">
    <div class="hero-content">
        <h1>Discover Your Next Adventure</h1>
        <p>Book curated packages, explore stunning destinations, and let our AI plan your perfect itinerary.</p>
        
        <form action="{{ route('destinations.index') }}" method="GET" class="search-widget">
            <div class="search-input-group">
                <label class="search-label">Location</label>
                <input type="text" name="search" placeholder="City or country...">
            </div>
            <div class="search-input-group">
                <label class="search-label">Category</label>
                <select name="category">
                    <option value="">Any category</option>
                    <option value="beaches">Beaches</option>
                    <option value="mountains">Mountains</option>
                    <option value="historical">Historical</option>
                    <option value="nature">Nature</option>
                </select>
            </div>
            <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

<div class="section-padding container">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem;">
        <div>
            <h2 class="section-title">Trending Destinations</h2>
            <p class="section-subtitle" style="margin-bottom: 0;">Popular choices for travelers worldwide.</p>
        </div>
        <div>
            <a href="{{ route('destinations.index') }}" class="btn btn-outline">View All</a>
        </div>
    </div>
    
    <div class="dest-grid">
        <a href="{{ route('destinations.index', ['search' => 'Bali']) }}" class="d-card">
            <img src="https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&q=80&w=600" class="d-img" alt="Bali">
            <div class="d-body">
                <div class="d-name">Bali, Indonesia</div>
                <div class="d-info"><i class="fas fa-star" style="color: #fbbf24;"></i> 4.9 &bull; Beaches</div>
            </div>
        </a>
        <a href="{{ route('destinations.index', ['search' => 'Paris']) }}" class="d-card">
            <img src="https://images.unsplash.com/photo-1499856871958-5b9627545d1a?auto=format&fit=crop&q=80&w=600" class="d-img" alt="Paris">
            <div class="d-body">
                <div class="d-name">Paris, France</div>
                <div class="d-info"><i class="fas fa-star" style="color: #fbbf24;"></i> 4.8 &bull; Cultural</div>
            </div>
        </a>
        <a href="{{ route('destinations.index', ['search' => 'Santorini']) }}" class="d-card">
            <img src="https://images.unsplash.com/photo-1512453979436-5a53696511cc?auto=format&fit=crop&q=80&w=600" class="d-img" alt="Santorini">
            <div class="d-body">
                <div class="d-name">Santorini, Greece</div>
                <div class="d-info"><i class="fas fa-star" style="color: #fbbf24;"></i> 4.9 &bull; Luxury</div>
            </div>
        </a>
        <a href="{{ route('destinations.index', ['search' => 'Kyoto']) }}" class="d-card">
            <img src="https://images.unsplash.com/photo-1534008897995-27a23e859048?auto=format&fit=crop&q=80&w=600" class="d-img" alt="Kyoto">
            <div class="d-body">
                <div class="d-name">Kyoto, Japan</div>
                <div class="d-info"><i class="fas fa-star" style="color: #fbbf24;"></i> 4.7 &bull; Historical</div>
            </div>
        </a>
    </div>
</div>

<div class="section-padding container">
    <h2 class="section-title text-center" style="text-align: center;">Why Choose TravelMate?</h2>
    <p class="section-subtitle text-center" style="text-align: center;">We simplify travel planning through intelligent curation.</p>
    
    <div class="features-grid">
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-robot"></i></div>
            <div class="feature-title">AI-Powered Itineraries</div>
            <div class="feature-desc">Our intelligent concierge builds custom, minute-by-minute itineraries tailored exactly to your unique preferences.</div>
        </div>
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-map-marked-alt"></i></div>
            <div class="feature-title">Curated Packages</div>
            <div class="feature-desc">Every trip is hand-picked and verified by our travel experts to ensure an exceptional experience.</div>
        </div>
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-headset"></i></div>
            <div class="feature-title">24/7 Dedicated Support</div>
            <div class="feature-desc">Wherever you are in the world, our dedicated support team is available around the clock to assist you.</div>
        </div>
    </div>
</div>

<div class="section-padding container">
    <div style="background: var(--primary); border-radius: 16px; padding: 4rem 2rem; text-align: center; color: #fff;">
        <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">Ready to explore the world?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">Join thousands of happy travelers who have discovered their perfect destinations with TravelMate.</p>
        <a href="{{ route('register') }}" class="btn" style="background: #fff; color: var(--primary); font-weight: 600; padding: 0.8rem 2rem;">Create Free Account</a>
    </div>
</div>

@endsection
