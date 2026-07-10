<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TravelMate') — Professional Travel Management</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            /* ── CLEAN SAAS THEME ── */
            --bg-body: #f8fafc;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --primary: #0d9488;
            --primary-hover: #0f766e;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        }
        
        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }
        
        body {
            font-family:'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a { color:inherit; text-decoration:none; }

        /* CONTAINERS */
        .container { width: 100%; max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; }
        .section-padding { padding: 4rem 0; }

        /* NAVBAR */
        .navbar {
            position: fixed; top: 0; width: 100%; z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border);
            height: 70px;
            display: flex;
            align-items: center;
        }
        .nav-inner { display:flex; align-items:center; justify-content:space-between; width: 100%; }
        .nav-logo {
            font-size: 1.25rem; font-weight: 700; color: var(--primary);
            display: flex; align-items: center; gap: 0.5rem; letter-spacing: -0.5px;
        }
        
        .nav-links { display:flex; align-items:center; gap: 2rem; list-style:none; }
        .nav-links a {
            font-size: 0.95rem; font-weight: 500; color: var(--text-muted); transition: 0.2s;
        }
        .nav-links a:hover, .nav-links a.active { color: var(--primary); }
        
        .nav-actions { display:flex; align-items:center; gap: 1rem; }
        
        .btn {
            display:inline-flex; align-items:center; justify-content:center; gap: 0.5rem; padding: 0.6rem 1.25rem;
            border-radius: 8px; font-weight: 500; font-size: 0.95rem; cursor:pointer;
            transition: 0.2s ease; border: none;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-outline { background: #fff; border: 1px solid var(--border); color: var(--text-main); }
        .btn-outline:hover { background: var(--bg-body); }
        
        /* DROPDOWN */
        .nav-dropdown { position:relative; }
        .dropdown-menu { 
            position:absolute; top:calc(100% + 10px); right:0; background: #fff;
            border:1px solid var(--border); border-radius: 8px; min-width: 220px; padding: 0.5rem 0;
            opacity:0; visibility:hidden; transform:translateY(-10px); transition:0.2s; box-shadow: var(--shadow-lg); 
        }
        .dropdown-menu.show { opacity:1; visibility:visible; transform:translateY(0); }
        .dropdown-menu a, .dropdown-menu button { 
            display:flex; align-items:center; gap:0.75rem; padding: 0.75rem 1.25rem;
            font-size: 0.9rem; color: var(--text-main); transition: 0.2s; font-weight: 400; width: 100%; border: none; background: none; cursor: pointer; text-align: left;
        }
        .dropdown-menu a:hover, .dropdown-menu button:hover { background: var(--bg-body); color: var(--primary); }
        .dropdown-menu hr { border:none; border-top:1px solid var(--border); margin: 0.5rem 0; }
        .avatar-sm { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; cursor: pointer; border: 1px solid var(--border); }

        /* NOTIFICATION BADGE */
        .notif-badge .badge { 
            position:absolute; top:-2px; right:-2px; background: #ef4444;
            color:#fff; font-size:0.7rem; width: 18px; height: 18px; border-radius: 50%;
            display:flex; align-items:center; justify-content:center; font-weight:600; border: 2px solid #fff;
        }

        /* MAIN */
        main { min-height: calc(100vh - 70px); padding-top: 70px; }

        /* FOOTER */
        footer { background: #fff; padding: 4rem 0 2rem; border-top: 1px solid var(--border); margin-top: 4rem; }
        .footer-grid { display:grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 4rem; margin-bottom: 3rem; }
        .footer-brand p { color:var(--text-muted); font-size: 0.9rem; margin: 1rem 0 1.5rem; line-height: 1.6; }
        .footer-social { display:flex; gap: 1rem; }
        .social-btn { color: var(--text-muted); font-size: 1.1rem; transition: 0.2s; }
        .social-btn:hover { color: var(--primary); }
        .footer-col h4 { font-size: 1rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--text-main); }
        .footer-col ul { list-style:none; display:flex; flex-direction:column; gap: 0.75rem; }
        .footer-col ul a { color: var(--text-muted); font-size: 0.9rem; transition: 0.2s; }
        .footer-col ul a:hover { color: var(--primary); }
        .footer-bottom { 
            border-top:1px solid var(--border); padding-top: 2rem;
            display:flex; align-items:center; justify-content:space-between;
            color: var(--text-muted); font-size: 0.85rem;
        }

        /* ALERTS */
        .alert { padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.95rem; font-weight: 500; box-shadow: var(--shadow-md); display: flex; align-items: center; gap: 0.75rem;}
        .alert-success { background: #ecfdf5; border: 1px solid #10b981; color: #047857; }
        .alert-error   { background: #fef2f2; border: 1px solid #ef4444; color: #b91c1c; }

        /* CARDS & PANELS */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow-sm); overflow: hidden; }
    </style>
    @stack('styles')
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">
            <i class="fas fa-paper-plane"></i> TravelMate
        </a>
        <ul class="nav-links">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Discover</a></li>
            <li><a href="{{ route('destinations.index') }}" class="{{ request()->routeIs('destinations*') ? 'active' : '' }}">Destinations</a></li>
            <li><a href="{{ route('packages.index') }}" class="{{ request()->routeIs('packages*') ? 'active' : '' }}">Packages</a></li>
            <li><a href="{{ route('planner.index') }}" class="{{ request()->routeIs('planner*') ? 'active' : '' }}">Concierge</a></li>
        </ul>
        <div class="nav-actions">
            @auth
                <div class="nav-dropdown notif-badge">
                    <a href="javascript:void(0);" onclick="toggleNavDropdown('notif-menu', event)" style="cursor:pointer; color: var(--text-muted);">
                        <i class="fas fa-bell" style="font-size:1.1rem;"></i>
                        @php 
                            $unreadCount = \App\Models\TravelNotification::where('user_id',auth()->id())->where('is_read',false)->count(); 
                        @endphp
                        @if($unreadCount)<span class="badge">{{ $unreadCount }}</span>@endif
                    </a>
                    <div id="notif-menu" class="dropdown-menu" style="width: 300px;">
                        <div style="padding: 1rem; border-bottom: 1px solid var(--border);">
                            <h4 style="font-size: 0.95rem; font-weight: 600; margin: 0;">Notifications</h4>
                        </div>
                        <div style="padding: 1.5rem; text-align: center; font-size: 0.85rem;">
                            <a href="{{ route('notifications') }}" style="color: var(--primary); font-weight: 500; justify-content: center;">View All Notifications &rarr;</a>
                        </div>
                    </div>
                </div>
                <div class="nav-dropdown">
                    <img src="{{ auth()->user()->avatar_url }}" alt="avatar" class="avatar-sm" onclick="toggleNavDropdown('profile-menu', event)">
                    <div id="profile-menu" class="dropdown-menu">
                        <a href="{{ route('dashboard') }}"><i class="fas fa-user"></i> Dashboard</a>
                        <a href="{{ route('bookings.index') }}"><i class="fas fa-ticket-alt"></i> My Bookings</a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" style="color: #ef4444;"><i class="fas fa-shield-alt"></i> Admin Panel</a>
                        @endif
                        <hr>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> Log Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Log In</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
            @endauth
        </div>
    </div>
</nav>

<main>
    @if(session('success'))
        <div style="position:fixed; top:90px; right: 2rem; z-index:9000; max-width:400px">
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div style="position:fixed; top:90px; right: 2rem; z-index:9000; max-width:400px">
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        </div>
    @endif
    
    @yield('content')
</main>

{{-- FOOTER --}}
<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="nav-logo">TravelMate</div>
                <p>Your intelligent travel companion for discovering and planning perfect trips.</p>
                <div class="footer-social">
                    <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Company</h4>
                <ul>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Explore</h4>
                <ul>
                    <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                    <li><a href="{{ route('packages.index') }}">Packages</a></li>
                    <li><a href="{{ route('planner.index') }}">AI Planner</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} TravelMate Inc. All rights reserved.</span>
        </div>
    </div>
</footer>

<script>
function toggleNavDropdown(id, event) {
    event.preventDefault(); event.stopPropagation();
    const menu = document.getElementById(id);
    const wasOpen = menu.classList.contains('show');
    document.querySelectorAll('.dropdown-menu.show').forEach(el => el.classList.remove('show'));
    if (!wasOpen) menu.classList.add('show');
}
window.addEventListener('click', function(e) {
    if (!e.target.closest('.nav-dropdown')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(el => el.classList.remove('show'));
    }
});
setTimeout(() => { document.querySelectorAll('.alert').forEach(a => a.style.display = 'none'); }, 5000);
</script>
@stack('scripts')
    <!-- Chatbot Widget -->
    <div id="chat-widget-container" style="display: none; position: fixed; bottom: 85px; right: 2rem; width: 380px; height: 600px; z-index: 1000; box-shadow: 0 15px 35px rgba(0,0,0,0.2); border-radius: 16px; overflow: hidden; border: 1px solid var(--border); transition: 0.3s; transform-origin: bottom right;">
        <iframe src="{{ route('chatbot.index') }}?widget=true" style="width: 100%; height: 100%; border: none; background: #fff;"></iframe>
    </div>

    <button onclick="toggleChatWidget()" class="floating-chat-btn" id="floating-chat-btn">
        <div class="chat-icon"><i class="fas fa-robot"></i></div>
        <div class="chat-text">AI Assistant</div>
    </button>

    <script>
        function toggleChatWidget() {
            const widget = document.getElementById('chat-widget-container');
            if (widget.style.display === 'none') {
                widget.style.display = 'block';
            } else {
                widget.style.display = 'none';
            }
        }
    </script>

    <style>
        .floating-chat-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary);
            color: #fff;
            border-radius: 50px;
            padding: 0.75rem 1.25rem 0.75rem 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 10px 25px rgba(13, 148, 136, 0.4);
            text-decoration: none;
            z-index: 1001;
            transition: 0.3s;
            border: 2px solid rgba(255,255,255,0.2);
            cursor: pointer;
        }
        .floating-chat-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(13, 148, 136, 0.6);
            background: var(--primary-hover);
        }
        .floating-chat-btn .chat-icon {
            background: #fff;
            color: var(--primary);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .floating-chat-btn .chat-text {
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }
        @media (max-width: 768px) {
            #chat-widget-container {
                width: 90vw;
                height: 75vh;
                right: 5vw;
                bottom: 80px;
            }
            .floating-chat-btn {
                bottom: 1.5rem;
                right: 1.5rem;
                padding: 0.75rem;
            }
            .floating-chat-btn .chat-text {
                display: none;
            }
        }
    </style>
</body>
</html>
