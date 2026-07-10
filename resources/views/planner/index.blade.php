@extends('layouts.app')
@section('title','AI Travel Planner Portal')
@section('content')

{{-- Leaflet Maps Assets --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
/* CLEAN SAAS STYLES FOR PLANNER */
.planner-page { background: var(--bg-body); padding: 3rem 1.5rem 6rem; min-height: 100vh; }
.inner { max-width: 1200px; margin: 0 auto; }
.glass { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; box-shadow: var(--shadow-sm); }
.form-row { display: grid; grid-template-columns: 1.2fr 1.2fr 1fr 1fr 1fr; gap: 1rem; align-items: end; }
@media(max-width:900px) { .form-row { grid-template-columns: 1fr 1fr; } }
.f-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.4rem; display: block; }
.f-input { width: 100%; background: #f8fafc; border: 1px solid var(--border); color: var(--text-main); padding: 0.8rem; border-radius: 8px; font-size: 0.95rem; outline: none; transition: 0.2s; font-family: 'Inter', sans-serif;}
.f-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(13,148,136,0.1); background: #fff;}
.calc-btn { padding: 1rem 2.5rem; border: none; border-radius: 8px; background: var(--primary); color: #fff; font-weight: 600; font-size: 1rem; cursor: pointer; transition: 0.2s; white-space: nowrap; }
.calc-btn:hover { background: var(--primary-hover); }

.results { display: none; margin-top: 3rem; animation: fadeUp 0.5s ease; }
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

.transport-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 2rem; }
@media(max-width:900px) { .transport-grid { grid-template-columns: 1fr; } }
.transport-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; text-align: center; transition: 0.3s; cursor: pointer; box-shadow: var(--shadow-sm); }
.transport-card:hover, .transport-card.best { border-color: var(--primary); box-shadow: var(--shadow-md); }
.transport-card.best::before { content: '✨ Best Value'; display: block; background: #f0fdfa; color: var(--primary); font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 6px; margin: 0 auto 0.75rem auto; text-transform: uppercase; width: max-content; }

.total-banner { background: #f0fdfa; border: 1px solid #ccfbf1; border-radius: 12px; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }

.premium-lock-container { background: #fff; border: 1px dashed var(--border); border-radius: 16px; padding: 3rem; text-align: center; margin-top: 2.5rem; position: relative; box-shadow: var(--shadow-sm); }
.unlock-btn { padding: 1rem 2.5rem; border: none; border-radius: 8px; background: #0f172a; color: #fff; font-weight: 600; font-size: 1.05rem; cursor: pointer; transition: 0.3s; }
.unlock-btn:hover { background: #1e293b; }

.gated-blur-element { filter: blur(4px); pointer-events: none; opacity: 0.6; user-select: none; }
.lock-overlay-badge { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(255,255,255,0.7); backdrop-filter: blur(2px); z-index: 10; border-radius: 12px; padding: 1rem; text-align: center; }

.partner-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; margin-top: 2rem; }
.partner-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; text-align: center; transition: 0.3s; position: relative; }
.partner-card:hover { border-color: var(--primary); box-shadow: var(--shadow-sm); }

.timeline-panel { position: relative; padding-left: 2rem; border-left: 2px solid #e2e8f0; text-align: left; margin-top: 1.5rem; }
.timeline-card { position: relative; margin-bottom: 2rem; }
.timeline-card::before { content: ''; position: absolute; left: -2.35rem; top: 0.25rem; width: 14px; height: 14px; border-radius: 50%; background: #fff; border: 3px solid var(--primary); }

.feature-check { display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: var(--text-main); margin: 0.5rem 0; }
.feature-check.locked { color: var(--text-muted); text-decoration: line-through; }

.spinner { display: none; width: 24px; height: 24px; border: 3px solid #e2e8f0; border-top-color: var(--primary); border-radius: 50%; animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

.success-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.9); z-index: 9999; align-items: center; justify-content: center; }
.success-box { background: #fff; border-radius: 16px; padding: 3rem; text-align: center; max-width: 400px; animation: popIn 0.4s ease; box-shadow: var(--shadow-lg); }
@keyframes popIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<div class="planner-page">
    <div class="inner">
        {{-- Header Section --}}
        <div style="text-align: center; margin-bottom: 3.5rem;">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: #f0fdfa; border: 1px solid #ccfbf1; padding: 0.4rem 1.2rem; border-radius: 50px; margin-bottom: 1rem;">
                <span style="color: var(--primary); font-size: 0.8rem; font-weight: 700;"><i class="fas fa-sparkles"></i> AI INTELLIGENCE PLANNER</span>
            </div>
            <h1 style="font-size: clamp(2rem, 5vw, 3rem); font-weight: 800; color: var(--text-main); margin-bottom: 0.75rem; letter-spacing: -0.5px;">Plan Your Perfect Trip</h1>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Generate dynamic itineraries with advanced travel estimates, weather forecasts, and premium booking tools.</p>
        </div>

        {{-- Interactive Form Panel --}}
        <div class="glass" style="margin-bottom: 2.5rem;">
            <h2 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem;"><i class="fas fa-sliders-h" style="color: var(--primary);"></i> Travel Parameters</h2>
            <div class="form-row">
                <div style="position: relative;">
                    <label class="f-label">From</label>
                    <input type="text" id="p_from" class="f-input" placeholder="e.g. Delhi, Mumbai" value="Mumbai, India" autocomplete="off">
                    <div id="from-autocomplete" style="display:none;position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid var(--border);border-radius:8px;box-shadow:var(--shadow-md);z-index:9999;max-height:220px;overflow-y:auto;margin-top:.25rem"></div>
                </div>
                <div style="position: relative;">
                    <label class="f-label">To</label>
                    <input type="text" id="p_to" class="f-input" placeholder="e.g. Goa, Paris" value="Goa, India" autocomplete="off">
                    <div id="to-autocomplete" style="display:none;position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid var(--border);border-radius:8px;box-shadow:var(--shadow-md);z-index:9999;max-height:220px;overflow-y:auto;margin-top:.25rem"></div>
                </div>
                <div>
                    <label class="f-label">Travelers</label>
                    <select id="p_travelers" class="f-input">
                        @foreach(range(1,10) as $i)<option value="{{ $i }}" {{ $i==2?'selected':'' }}>{{ $i }} Person{{ $i>1?'s':'' }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="f-label">Days</label>
                    <select id="p_days" class="f-input">
                        @foreach([1,2,3,4,5,7,10] as $d)<option value="{{ $d }}" {{ $d==3?'selected':'' }}>{{ $d }} Days</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="f-label">Budget Tier</label>
                    <select id="p_budget" class="f-input">
                        <option value="budget">💰 Budget</option>
                        <option value="standard" selected>⭐ Standard</option>
                        <option value="luxury">💎 Luxury</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 1.5rem; display: flex; gap: 1.5rem; align-items: center;">
                <button class="calc-btn" onclick="calculate()" id="calc-btn">
                    <i class="fas fa-robot"></i> Generate AI Plan
                </button>
                <div class="spinner" id="spinner"></div>
            </div>
        </div>

        {{-- Interactive Results Module --}}
        <div class="results" id="results">

            {{-- 1. Transit Comparison --}}
            <div style="margin-bottom: 2.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem;"><i class="fas fa-route" style="color: var(--primary);"></i> Transit Cost Estimator</h3>
                <div class="transport-grid" id="transport-cards"></div>
            </div>

            {{-- 2. Weather Alert & Attractions --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
                {{-- Weather Alert Card --}}
                <div class="glass" style="padding: 1.5rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem;"><i class="fas fa-cloud-sun" style="color: #0ea5e9;"></i> Meteorology Forecast</h3>
                    <div style="background: #f8fafc; border: 1px solid var(--border); padding: 1.25rem; border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-size: 1.8rem; font-weight: 800; color: var(--text-main);" id="weather_temp">28°C</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">Scattered Clouds · Safe Travel Rating</div>
                        </div>
                        <span style="background: #ecfdf5; color: #059669; font-size: 0.75rem; padding: 0.3rem 0.6rem; border-radius: 6px; font-weight: 600;">OPTIONAL</span>
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 1rem; line-height: 1.6;"><i class="fas fa-info-circle" style="color: #0ea5e9;"></i> <b>AI Tip</b>: Carry comfortable clothing. Standard UV index recorded. Perfect conditions for outdoor activities.</p>
                </div>

                {{-- Nearby Attractions Card --}}
                <div class="glass" style="padding: 1.5rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem;"><i class="fas fa-location-dot" style="color: #f43f5e;"></i> Top Attractions</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="background: #f8fafc; border: 1px solid var(--border); padding: 0.8rem 1rem; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-main); font-weight: 600; font-size: 0.9rem;">🏰 Historic Coastal Fort</span>
                            <span style="color: var(--text-muted); font-size: 0.8rem;">1.2 km away</span>
                        </div>
                        <div style="background: #f8fafc; border: 1px solid var(--border); padding: 0.8rem 1rem; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-main); font-weight: 600; font-size: 0.9rem;">🛍️ Lively Street Bazaar</span>
                            <span style="color: var(--text-muted); font-size: 0.8rem;">2.5 km away</span>
                        </div>
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 1rem;"><i class="fas fa-heart" style="color: #f43f5e;"></i> Recommended by 1,200+ travelers.</p>
                </div>
            </div>

            {{-- 3. Budget & Expense Chartjs Section --}}
            <div style="margin-bottom: 2.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem;"><i class="fas fa-chart-pie" style="color: #8b5cf6;"></i> Predictive Cost Analysis</h3>
                <div class="glass" style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2.5rem; padding: 2rem;">
                    <div>
                        <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1.25rem; color: var(--text-main);">Budget Trend Projection</h4>
                        <div style="height: 220px; position: relative;"><canvas id="plannerLineChart"></canvas></div>
                    </div>
                    <div>
                        <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1.25rem; color: var(--text-main);">Cost Breakdown</h4>
                        <div style="height: 220px; position: relative;"><canvas id="plannerDoughnutChart"></canvas></div>
                    </div>
                </div>
            </div>

            {{-- 4. Day-Wise Basic Itinerary --}}
            <div class="glass" style="margin-bottom: 2.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;"><i class="far fa-calendar-alt" style="color: var(--primary);"></i> Day-Wise Itinerary</h3>
                    <span style="background: #f0fdfa; color: var(--primary); font-size: 0.75rem; padding: 0.3rem 0.8rem; border-radius: 50px; font-weight: 600;">FREE PREVIEW</span>
                </div>

                <div class="timeline-panel">
                    {{-- Day 1 --}}
                    <div class="timeline-card">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">Day 1: Arrival & Check-in</div>
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-top: 0.25rem; margin-bottom: 0.5rem;" id="it_day1_title">Oceanfront Check-in & Scenic Beach Shacks</h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;" id="it_day1_desc">Drive from the terminal to your curated beach cottage accommodations. Unpack and take a refreshing beach sunset stroll, ordering fresh local appetizers and refreshing fruit mocktails from highly rated coastal shacks.</p>
                    </div>
                    {{-- Day 2 --}}
                    <div class="timeline-card">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">Day 2: Historical Landmarks</div>
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-top: 0.25rem; margin-bottom: 0.5rem;" id="it_day2_title">Old Portuguese Fort Walking Tour</h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;" id="it_day2_desc">Explore ancient architecture in the historical district. Take amazing landscape photos of the deep sea cliffs from the high-walls. Stroll through the local spice gardens and enjoy traditional clay-pot wood-fired buffet lunches.</p>
                    </div>

                    {{-- Day 3 (Locked) --}}
                    <div class="timeline-card" style="position: relative;">
                        <div class="gated-blur-element">
                            <div style="font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">Day 3: Secret Waterfalls & Caves</div>
                            <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-top: 0.25rem; margin-bottom: 0.5rem;">Tropical Jungle Trekking</h4>
                            <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">Stroll inside a gorgeous wildlife sanctuary, following hidden routes to deep cascading mountain springs. Swim in standard pool currents under safe lifeguards. Finish with evening shopping at dynamic, illuminated ocean bazaar lanes.</p>
                        </div>
                        <div class="lock-overlay-badge">
                            <div style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--text-main);"><i class="fas fa-lock"></i></div>
                            <div style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Day 3 Locked</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); max-width: 300px; margin-top: 0.25rem;">Unlock Premium to access full day-by-day travel timelines.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Estimate Banner --}}
            <div class="total-banner" id="total-banner"></div>

            {{-- 6. PREMIUM RAZORPAY GATE CONTAINER --}}
            <div class="premium-lock-container" id="premium-section">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">💎</div>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;">Unlock Complete Premium AI Itinerary</h3>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; max-width: 550px; margin-left: auto; margin-right: auto; font-size: 0.95rem; line-height: 1.6;">
                    Unlock the full day-by-day travel plan, exact hotel locations, global telematics maps, booking redirect APIs, and generate a printable QR Travel Pass!
                </p>
                <button class="unlock-btn" onclick="unlockPremium()">
                    Unlock Complete Ecosystem for ₹199
                </button>
                <div style="margin-top: 1.25rem; font-size: 0.8rem; color: var(--text-muted);">
                    <i class="fas fa-shield-alt" style="color: var(--primary);"></i> Secure transactions · Instant access
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Success Overlay --}}
<div class="success-overlay" id="success-overlay">
    <div class="success-box">
        <div style="font-size: 3rem; margin-bottom: 1rem;">✨</div>
        <h2 style="color: var(--text-main); font-weight: 800; margin-bottom: 0.5rem;">Premium Unlocked!</h2>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Activating premium parameters and geocoding detailed itineraries...</p>
        <div class="spinner" style="display: block; margin: 0 auto; border-top-color: var(--primary);"></div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
let lastResult = null;
let trendChartInstance = null;
let doughnutChartInstance = null;

async function calculate() {
    const from = document.getElementById('p_from').value.trim();
    const to = document.getElementById('p_to').value.trim();
    if(!from || !to){ alert('Please provide coordinates for From & To inputs.'); return; }

    const btn = document.getElementById('calc-btn');
    const sp = document.getElementById('spinner');
    btn.disabled = true; 
    sp.style.display = 'block';

    try {
        const r = await fetch('{{ route("planner.calculate") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                from: from,
                to: to,
                travelers: document.getElementById('p_travelers').value,
                days: document.getElementById('p_days').value,
                budget: document.getElementById('p_budget').value
            })
        });
        const data = await r.json();
        lastResult = data;
        renderResults(data);
    } catch(e) {
        alert('Simulation error: ' + e.message);
    }
    btn.disabled = false; 
    sp.style.display = 'none';
}

function renderResults(d) {
    const f = n => ('₹' + Math.round(n).toLocaleString('en-IN'));
    const cheapest = Math.min(d.totals.with_flight, d.totals.with_train, d.totals.with_bus);

    let tc = '';
    [['flight','✈️','fa-plane-up'], ['train','🚆','fa-train-subway'], ['bus','🚌','fa-bus-simple']].forEach(([k, em, icon]) => {
        const t = d.transport[k];
        const isBest = d.totals['with_' + k] === cheapest;
        tc += `<div class="transport-card ${isBest ? 'best' : ''}">
            <div style="font-size:1.8rem;margin-bottom:.5rem">${em}</div>
            <div style="color:var(--text-main);font-weight:700;font-size:.95rem;margin-bottom:.2rem">${t.label}</div>
            <div style="color:var(--text-muted);font-size:.8rem;margin-bottom:.5rem"><i class="far fa-clock"></i> ${t.duration}</div>
            <div style="font-size:1.35rem;font-weight:800;color:var(--text-main);">${f(t.cost)}</div>
            <div style="font-size:.75rem;color:var(--text-muted)">for ${d.travelers} traveler(s)</div>
            <div style="margin-top:1rem;font-size:.85rem;font-weight:600;color:var(--text-muted)">Total Trip: <span style="color:var(--primary)">${f(d.totals['with_'+k])}</span></div>
        </div>`;
    });
    document.getElementById('transport-cards').innerHTML = tc;

    document.getElementById('total-banner').innerHTML = `
        <div>
            <div style="font-size:.85rem;color:var(--text-muted);margin-bottom:.2rem">${d.from} &rarr; ${d.to} · ${d.days} Days · ${d.travelers} Traveler(s)</div>
            <div style="font-size:.9rem;color:var(--text-main);font-weight:600;">Suggested Transit: <strong>with ${d.totals.with_bus <= d.totals.with_train ? 'Bus' : 'Train'}</strong></div>
        </div>
        <div style="text-align:right">
            <div style="font-size:.85rem;color:var(--text-muted)">Calculated Basic Budget</div>
            <div style="font-size:2rem;font-weight:800;color:var(--primary);line-height:1">${f(cheapest)}</div>
            <div style="font-size:.8rem;color:var(--text-muted)">${f(Math.round(cheapest/d.travelers))} /person</div>
        </div>`;

    document.getElementById('it_day1_title').textContent = `Check-in in ${d.to} & Local Shacks`;
    document.getElementById('it_day1_desc').textContent = `Arrive dynamically from ${d.from}. Complete validation and enjoy high-speed check-in at a beautiful local hotel collection. Walk around coastal viewpoints and enjoy fresh dining packages.`;
    document.getElementById('it_day2_title').textContent = `${d.to} Historic Architectural Walk`;
    document.getElementById('it_day2_desc').textContent = `Explore scenic landmarks and markets. Treat yourself to highly rated dynamic local specialties. Experience a completely customized travel route optimized for registered users.`;

    initializeCharts(d);

    document.getElementById('results').style.display = 'block';
    document.getElementById('results').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function initializeCharts(d) {
    const trendCtx = document.getElementById('plannerLineChart').getContext('2d');
    const doughnutCtx = document.getElementById('plannerDoughnutChart').getContext('2d');

    if (trendChartInstance) trendChartInstance.destroy();
    if (doughnutChartInstance) doughnutChartInstance.destroy();

    trendChartInstance = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Day 1', 'Day 2', 'Day 3'],
            datasets: [{
                label: 'Projected Spend Limit',
                data: [d.totals.cheapest * 0.3, d.totals.cheapest * 0.6, d.totals.cheapest],
                borderColor: '#0d9488',
                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                fill: true,
                borderWidth: 2,
                tension: 0.4
            }, {
                label: 'Simulated Budget Burn',
                data: [d.totals.cheapest * 0.25, d.totals.cheapest * 0.52, d.totals.cheapest * 0.95],
                borderColor: '#f59e0b',
                borderWidth: 2,
                tension: 0.4,
                pointBackgroundColor: '#f59e0b'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#64748b' } } },
            scales: {
                x: { ticks: { color: '#64748b' }, grid: { color: '#f1f5f9' } },
                y: { ticks: { color: '#64748b' }, grid: { color: '#f1f5f9' } }
            }
        }
    });

    doughnutChartInstance = new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Transit', 'Hotels', 'Food & Local'],
            datasets: [{
                data: [d.transport.flight.cost, d.daily.hotel.total, d.daily.food.total + d.daily.local.total],
                backgroundColor: ['#0d9488', '#f59e0b', '#3b82f6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { color: '#64748b' } } }
        }
    });
}

async function unlockPremium() {
    if(!lastResult) { alert('Please calculate your trip parameters first.'); return; }

    try {
        const r = await fetch('{{ route("planner.premium.order") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                from: lastResult.from,
                to: lastResult.to,
                travelers: lastResult.travelers,
                days: lastResult.days,
                budget: lastResult.tier
            })
        });
        
        if (!r.ok) {
            const errText = await r.text();
            throw new Error(errText);
        }
        
        const data = await r.json();
        if(data.error) { alert('❌ ' + data.error); return; }

        const rzp = new Razorpay({
            key: data.key_id,
            amount: data.amount,
            currency: data.currency,
            name: 'TravelMate Premium',
            description: data.description,
            order_id: data.order_id,
            prefill: { name: data.name, email: data.email, contact: '9999999999' },
            theme: { color: '#0d9488' },
            handler: async function(response) {
                document.getElementById('success-overlay').style.display = 'flex';
                try {
                    const verify = await fetch('{{ route("planner.premium.verify") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(response)
                    });
                    
                    if (!verify.ok) throw new Error('Verification failed.');
                    const res = await verify.json();
                    
                    if(res.success) {
                        window.location.href = res.redirect_url;
                    } else {
                        document.getElementById('success-overlay').style.display = 'none';
                        alert('Verification error: ' + res.message);
                    }
                } catch(errVal) {
                    document.getElementById('success-overlay').style.display = 'none';
                    alert('❌ Verification Error');
                }
            }
        });
        rzp.open();
    } catch(e) {
        alert('❌ Payment Order Error: Please make sure you are logged in.');
    }
}

// Autocomplete Logic
function bindAutocomplete(inputId, boxId) {
    const input = document.getElementById(inputId);
    const box = document.getElementById(boxId);
    let timeout = null;

    input.addEventListener('focus', function() { this.parentElement.style.zIndex = '9999'; });
    input.addEventListener('blur', function() { setTimeout(() => { this.parentElement.style.zIndex = ''; }, 200); });

    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value.trim();
        if (query.length < 2) { box.style.display = 'none'; return; }

        box.innerHTML = '<div style="padding:0.75rem 1rem;color:var(--text-muted);font-size:0.85rem">Searching...</div>';
        box.style.display = 'block';

        timeout = setTimeout(async () => {
            try {
                let apiMatches = [];
                try {
                    const response = await fetch(`/api/city-search?q=${encodeURIComponent(query)}`);
                    const data = await response.json();
                    apiMatches = data.map(place => {
                        const city = place.address.city || place.address.town || place.address.village || place.name;
                        const state = place.address.state || place.address.country || 'India';
                        return { city, state };
                    });
                } catch (apiErr) { console.error(apiErr); }

                const unique = [];
                apiMatches.forEach(item => {
                    if (!unique.some(u => u.city.toLowerCase() === item.city.toLowerCase())) {
                        unique.push(item);
                    }
                });

                if (unique.length === 0) {
                    box.innerHTML = '<div style="padding:0.75rem 1rem;color:var(--text-muted);font-size:0.85rem">No cities found.</div>';
                    return;
                }

                box.innerHTML = '';
                unique.forEach(place => {
                    const item = document.createElement('div');
                    item.style.cssText = 'padding:0.75rem 1rem;cursor:pointer;border-bottom:1px solid var(--border);font-size:0.9rem;display:flex;align-items:center;gap:0.5rem;';
                    item.innerHTML = `<i class="fas fa-location-dot" style="color:var(--text-muted)"></i> <div><div style="font-weight:600;color:var(--text-main)">${place.city}</div><div style="font-size:0.75rem;color:var(--text-muted)">${place.state}</div></div>`;
                    item.onmouseenter = () => item.style.background = '#f8fafc';
                    item.onmouseleave = () => item.style.background = 'transparent';
                    item.onclick = () => {
                        input.value = `${place.city}, ${place.state}`;
                        box.style.display = 'none';
                    };
                    box.appendChild(item);
                });
            } catch (error) {
                box.innerHTML = '<div style="padding:0.75rem 1rem;color:#ef4444;font-size:0.85rem">Error loading cities.</div>';
            }
        }, 500);
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !box.contains(e.target)) {
            box.style.display = 'none';
        }
    });
}

bindAutocomplete('p_from', 'from-autocomplete');
bindAutocomplete('p_to', 'to-autocomplete');
</script>
@endsection
