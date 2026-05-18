<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#22c55e">
    <title>{{ $title ?? 'FitnessPro' }} | FitnessPro</title>
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand': 'var(--brand)',
                        'brand-dark': 'var(--brand-dark)',
                        'surface': 'var(--surface)',
                        'surface-2': 'var(--surface-2)',
                        'surface-3': 'var(--surface-3)',
                        'text-main': 'var(--text-main)',
                        'text-dim': 'var(--text-dim)',
                        'border-col': 'var(--border)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style type="text/tailwindcss">
        :root {
            color-scheme: dark;
            --brand: #22c55e;
            --brand-dark: #16a34a;
            --surface: #0a0f1a;
            --surface-2: #111827;
            --surface-3: #1f2937;
            --text-main: #f9fafb;
            --text-dim: #9ca3af;
            --border: rgba(255,255,255,0.1);
            --card-radius: 2rem;
            --spacing-unit: 1.5rem;
            --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }

        .light-mode {
            color-scheme: light;
            --brand: #22c55e;
            --brand-dark: #15803d;
            --surface: #f1f5f9;
            --surface-2: #ffffff;
            --surface-3: #f8fafc;
            --text-main: #1e293b;
            --text-dim: #64748b;
            --border: rgba(0,0,0,0.08);
            --shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
        }

        .compact-mode {
            --card-radius: 1rem;
            --spacing-unit: 1rem;
        }

        body { 
            background-color: var(--surface); 
            color: var(--text-main); 
            font-family: 'Inter', sans-serif; 
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
            @apply antialiased;
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6 { color: var(--text-main); }
        p { color: var(--text-dim); }

        .sidebar-link { 
            @apply flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 text-sm font-medium relative overflow-hidden;
            color: var(--text-dim);
        }
        .sidebar-link:hover {
            color: var(--text-main);
            background-color: var(--surface-3);
        }
        .sidebar-link.active { 
            color: var(--brand);
            background-color: var(--brand);
            @apply text-white font-bold;
            border-left: 4px solid var(--brand-dark);
        }
        
        .card { 
            background-color: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--card-radius);
            padding: var(--spacing-unit);
            box-shadow: var(--shadow);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card:hover {
            @apply -translate-y-1;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.15);
            border-color: var(--brand);
        }
        
        .stat-card { @apply card; }
        
        .btn-primary { 
            background-color: var(--brand);
            @apply text-white font-black uppercase text-[10px] tracking-widest px-6 py-3 rounded-xl hover:opacity-95 shadow-lg active:scale-95 transition-all duration-300; 
            box-shadow: 0 10px 20px -5px var(--brand);
        }
        
        .input-field { 
            background-color: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text-main);
            @apply w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand transition-all duration-300 placeholder-gray-500 text-sm; 
        }
        
        .glow-text { 
            background: linear-gradient(135deg, var(--brand), var(--brand-dark)); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }

        .text-main-area { color: var(--text-main); }
        
        /* Global Overrides for Light Mode (Refined) */
        .light-mode .bg-adaptive { background-color: var(--surface-2) !important; background-image: none !important; }
        .light-mode .border-adaptive { border-color: var(--border) !important; }

        /* Universal Adaptive Text Fix */
        .light-mode .text-main-area,
        .light-mode .bg-adaptive .text-white,
        .light-mode .bg-adaptive p,
        .light-mode .bg-adaptive span,
        .light-mode .bg-adaptive h1,
        .light-mode .bg-adaptive h2,
        .light-mode .bg-adaptive h3,
        .light-mode .bg-adaptive h4 { 
            color: var(--text-main) !important; 
        }

        /* Typography Pros-Adaptive */
        .prose-adaptive { color: var(--text-dim); }
        .prose-adaptive h1, .prose-adaptive h2, .prose-adaptive h3, .prose-adaptive h4 { color: var(--text-main); font-weight: 900; }
        .prose-adaptive strong { color: var(--text-main); font-weight: 800; }
        .prose-adaptive blockquote { border-left-color: var(--brand); background-color: var(--surface-3); }
        .prose-adaptive a { color: var(--brand); }

        .light-mode .bg-adaptive .text-gray-500,
        .light-mode .bg-adaptive .text-text-dim {
            color: var(--text-dim) !important;
        }

        /* Prevent transparency issues in light mode */
        .light-mode .bg-white\/5,
        .light-mode .bg-white\/3 {
            background-color: rgba(0,0,0,0.03) !important;
        }
        
        /* Ensure dark cards stay readable */
        .light-mode .bg-gray-800:not(.bg-adaptive) .text-white,
        .light-mode .bg-gray-900:not(.bg-adaptive) .text-white,
        .light-mode .bg-brand:not(.bg-adaptive) .text-white { 
            color: #ffffff !important; 
        }

        /* Header & Sidebar Specifics */

        /* Header & Sidebar Specifics */
        aside { background-color: var(--surface-2); border-color: var(--border); }
        header { background-color: var(--surface-2); border-color: var(--border); }

        /* Sidebar Link Adjustments for Light Mode */
        .light-mode .sidebar-link-inactive:hover {
            background-color: var(--surface-3) !important;
            color: var(--text-main) !important;
        }
        .light-mode .sidebar-link-inactive {
            color: var(--text-dim) !important;
        }
        
        /* Mobile Touch Optimizations */
        @media (max-width: 768px) {
            .card { border-radius: 1.5rem; padding: 1.25rem; }
            .btn-primary { @apply py-4 w-full text-xs; }
            .sidebar-link { @apply py-4; }
        }

        ::-webkit-scrollbar { width: 4px; } 
        ::-webkit-scrollbar-track { background: var(--surface); } 
        ::-webkit-scrollbar-thumb { background: var(--text-dim); border-radius: 2px; opacity: 0.5; }
        
        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp 0.4s ease forwards; }
    </style>
</head>
<body class="antialiased">
    <!-- Mobile Menu Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black/60 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-surface-2 lg:bg-surface-2 backdrop-blur-xl lg:backdrop-blur-none flex flex-col border-r border-border-col transform -translate-x-full lg:translate-x-0 transition-all duration-500 cubic-bezier(0.4, 0, 0.2, 1) shadow-2xl lg:shadow-none">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-border-col">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-xl font-extrabold text-text-main tracking-tight">Fitness<span class="text-brand">Pro</span></span>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-6 space-y-2.5 overflow-y-auto no-scrollbar">
                <p class="px-4 text-[10px] font-black text-text-dim uppercase tracking-[0.2em] mb-4 opacity-80">Main Area</p>

                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('workouts.index') }}" class="sidebar-link {{ request()->routeIs('workouts.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    My Workouts
                </a>

                <a href="{{ route('diets.index') }}" class="sidebar-link {{ request()->routeIs('diets.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Diet Plan
                </a>

                <a href="{{ route('progress.index') }}" class="sidebar-link {{ request()->routeIs('progress.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    Progress
                </a>

                <a href="{{ route('trainers.index') }}" class="sidebar-link {{ request()->routeIs('trainers.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Book Trainer
                </a>

                <p class="px-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mt-8 mb-4 opacity-80">Library</p>

                <a href="{{ route('blog.index') }}" class="sidebar-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Blog
                </a>

                <!-- PWA Install Button (NEW) -->
                <div class="px-3 pt-6">
                    <button id="installBtn" class="hidden w-full bg-brand/10 border border-brand/50 text-brand py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-brand hover:text-white transition-all shadow-lg shadow-brand/10">
                        📱 Install App
                    </button>
                </div>
            </nav>

            <!-- User Profile & Logout -->
            <div class="p-4 border-t border-border-col mt-auto bg-adaptive">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-2 border border-gray-700/50 shadow-inner mb-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-bold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-black text-main-area truncate leading-tight">{{ auth()->user()?->name ?? 'User' }}</p>
                        <p class="text-[10px] text-gray-500 truncate font-bold uppercase tracking-widest mt-0.5">{{ auth()->user()?->email ?? '' }}</p>
                    </div>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-red-400 hover:text-red-500 hover:bg-red-500/5 border border-red-500/20 transition-all duration-300 text-xs font-black uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- TOP NAVBAR -->
            <header class="h-20 lg:h-16 bg-surface/80 backdrop-blur-md border-b border-border-col flex items-center justify-between px-4 lg:px-6 shrink-0 sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:text-white transition-all active:scale-90">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="lg:hidden w-8 h-8 rounded-lg bg-brand flex items-center justify-center">
                         <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>

                <div class="hidden lg:block">
                    <h1 class="text-base font-black text-main-area uppercase tracking-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">@yield('page-subtitle', 'Welcome back!')</p>
                </div>

                <div class="flex items-center gap-4 ml-auto lg:ml-0" x-data="{ open: false }">
                    {{-- 🔍 Search Bar Removed --}}

                    {{-- 🛎️ Notification Bell --}}
                    <div class="relative">
                        <button @click="open = !open" class="w-10 h-10 rounded-xl bg-surface-2 border border-border-col flex items-center justify-center text-gray-400 hover:text-brand hover:border-brand/50 transition-all relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @php $notifCount = auth()->user()->unreadNotifications->count(); @endphp
                            @if($notifCount > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 bg-brand text-[8px] font-black text-white rounded-full flex items-center justify-center border-2 border-surface shadow-lg">{{ $notifCount }}</span>
                            @endif
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             class="absolute right-0 mt-3 w-80 bg-gray-900 border border-white/10 rounded-[1.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-[100] overflow-hidden bg-adaptive border-adaptive">
                            <div class="p-5 border-b border-white/5 flex items-center justify-between border-adaptive">
                                <h3 class="text-xs font-black text-main-area uppercase tracking-widest">Alerts Center</h3>
                                <button onclick="showToast('Live test active! 🚀', 'success')" class="text-[8px] font-black text-brand uppercase tracking-widest hover:underline">Send Test Toast</button>
                            </div>
                            <div class="max-h-[300px] overflow-y-auto no-scrollbar">
                                @forelse(auth()->user()->notifications->take(5) as $notification)
                                    <div class="p-4 border-b border-white/5 hover:bg-white/5 transition-colors border-adaptive">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-brand/10 flex items-center justify-center text-brand shrink-0">
                                                @if(($notification->data['type'] ?? '') === 'achievement') 🏆 @else 🔔 @endif
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-medium text-main-area leading-tight">{{ $notification->data['message'] }}</p>
                                                <p class="text-[9px] text-gray-500 mt-1 font-bold uppercase tracking-widest">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center">
                                        <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">No notifications yet</p>
                                    </div>
                                @endforelse
                            </div>
                             <a href="#" class="block p-4 text-center text-[10px] font-black text-gray-400 hover:text-brand uppercase tracking-widest bg-white/5 bg-adaptive border-t border-adaptive">View All Activity</a>
                        </div>
                    </div>

                    {{-- ⚙️ Settings Dropdown (Theme/Accent/Mode) --}}
                    <div class="relative" x-data="{ openSettings: false }">
                        <button @click="openSettings = !openSettings" class="w-10 h-10 rounded-xl bg-surface-2 border border-border-col flex items-center justify-center text-gray-400 hover:text-brand hover:border-brand/50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </button>
                        <div x-show="openSettings" @click.away="openSettings = false" 
                             class="absolute right-0 mt-3 w-64 bg-gray-900 border border-white/10 rounded-[1.5rem] shadow-2xl z-[110] p-6 space-y-6 bg-adaptive border-adaptive">
                            
                            <div>
                                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-3">Appearance</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <button onclick="setTheme('dark')" class="p-3 rounded-xl bg-white/5 border border-white/10 text-xs font-bold hover:bg-brand/10 transition-all bg-adaptive border-adaptive text-main-area">🌙 Dark</button>
                                    <button onclick="setTheme('light')" class="p-3 rounded-xl bg-white/5 border border-white/10 text-xs font-bold hover:bg-brand/10 transition-all bg-adaptive border-adaptive text-main-area">☀️ Light</button>
                                </div>
                            </div>

                            <div>
                                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-3">Accent Color</p>
                                <div class="flex gap-3">
                                    <button onclick="setAccent('#22c55e')" class="w-8 h-8 rounded-full bg-green-500 border-2 border-white/20 hover:scale-110 transition-all"></button>
                                    <button onclick="setAccent('#3b82f6')" class="w-8 h-8 rounded-full bg-blue-500 border-2 border-white/20 hover:scale-110 transition-all"></button>
                                    <button onclick="setAccent('#a855f7')" class="w-8 h-8 rounded-full bg-purple-500 border-2 border-white/20 hover:scale-110 transition-all"></button>
                                    <button onclick="setAccent('#f43f5e')" class="w-8 h-8 rounded-full bg-rose-500 border-2 border-white/20 hover:scale-110 transition-all"></button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-white/5 border-adaptive">
                                <span class="text-xs font-bold text-gray-300 text-main-area">Compact Mode</span>
                                <button onclick="toggleCompact()" id="compactToggle" class="w-10 h-5 bg-gray-700 rounded-full relative transition-all bg-adaptive">
                                    <div class="absolute top-1 left-1 w-3 h-3 bg-white rounded-full transition-all" id="compactDot"></div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('profile.index') }}" class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-bold text-sm hover:scale-110 active:scale-95 transition-all shadow-lg shadow-brand/20">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-6 no-scrollbar">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- 🔔 Notification Hub -->
    <div id="notification-hub" class="fixed top-5 right-5 z-[1000] space-y-4"></div>

    <script>
        // --- Theme Engine ---
        function setTheme(theme) {
            if (theme === 'light') document.documentElement.classList.add('light-mode');
            else document.documentElement.classList.remove('light-mode');
            localStorage.setItem('theme', theme);
        }

        function setAccent(color) {
            document.documentElement.style.setProperty('--brand', color);
            
            // Derive a darker version for gradients and hovers
            const darken = (hex, amount) => {
                let col = hex.replace('#', '');
                let r = parseInt(col.substring(0,2), 16);
                let g = parseInt(col.substring(2,4), 16);
                let b = parseInt(col.substring(4,6), 16);
                r = Math.max(0, Math.min(255, r - amount));
                g = Math.max(0, Math.min(255, g - amount));
                b = Math.max(0, Math.min(255, b - amount));
                return `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1)}`;
            };
            
            const darkColor = darken(color, 30);
            document.documentElement.style.setProperty('--brand-dark', darkColor);
            localStorage.setItem('accent', color);
        }

        function toggleCompact() {
            const isCompact = document.documentElement.classList.toggle('compact-mode');
            const dot = document.getElementById('compactDot');
            const bg = document.getElementById('compactToggle');
            if (isCompact) {
                dot.style.transform = 'translateX(20px)';
                bg.style.backgroundColor = 'var(--brand)';
            } else {
                dot.style.transform = 'translateX(0)';
                bg.style.backgroundColor = 'var(--border)';
            }
            localStorage.setItem('compact', isCompact);
        }

        // Initialize
        setTheme(localStorage.getItem('theme') || 'dark');
        if (localStorage.getItem('accent')) setAccent(localStorage.getItem('accent'));
        if (localStorage.getItem('compact') === 'true') toggleCompact();

        // Global Toast System
        function showToast(msg, type = 'info') {
            const hub = document.getElementById('notification-hub');
            if (!hub) return;
            
            const toast = document.createElement("div");
            
            const colors = {
                'info': 'border-brand/30 bg-adaptive',
                'success': 'border-green-500/30 bg-green-500/10',
                'achievement': 'border-yellow-500/30 bg-yellow-500/10 shadow-[0_0_20px_rgba(234,179,8,0.2)]'
            };

            toast.className = `p-4 rounded-2xl border backdrop-blur-md text-main-area shadow-2xl font-black uppercase text-[10px] tracking-widest animate-slide-up flex items-center gap-4 ${colors[type] || colors.info} bg-adaptive border-adaptive`;
            
            const icon = type === 'achievement' ? '🏆' : (type === 'success' ? '✅' : '🔔');
            
            toast.innerHTML = `<span class="text-xl">${icon}</span> <div>${msg}</div>`;
            hub.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full', 'transition-all', 'duration-500');
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }

        // Check for new notifications via API
        async function checkNotifications() {
            try {
                const res = await fetch('/api/notifications/latest');
                if (!res.ok) return;
                const data = await res.json();
                
                data.forEach(n => {
                    showToast(n.data.message, n.data.type === 'achievement' ? 'achievement' : 'info');
                    if (n.data.type === 'achievement' && window.confetti) {
                        window.confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
                    }
                });
            } catch (e) {}
        }

        @auth
            setInterval(checkNotifications, 10000); // Check every 10s
        @endauth

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // PWA LOGIC (FINAL TOUCH)
        let deferredPrompt;
        const installBtn = document.getElementById('installBtn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installBtn.classList.remove('hidden');
        });

        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    installBtn.classList.add('hidden');
                }
                deferredPrompt = null;
            }
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(reg => {
                    console.log('SW Registered', reg);
                }).catch(err => {
                    console.log('SW Registration failed', err);
                });
            });
        }
    </script>
    <script>
        // FitCore Global API – must be defined BEFORE page scripts run
        window.FitCore = {
            isLight: () => document.documentElement.classList.contains('light-mode'),
            // Convert a CSS hex colour to an RGB triplet string e.g. "34,197,94"
            _hexToRgb: (hex) => {
                const r = parseInt(hex.slice(1,3),16);
                const g = parseInt(hex.slice(3,5),16);
                const b = parseInt(hex.slice(5,7),16);
                return `${r},${g},${b}`;
            },
            // Get the current --brand CSS variable value
            brandHex: () => {
                const v = getComputedStyle(document.documentElement).getPropertyValue('--brand').trim();
                return v || '#22c55e';
            },
            colors: {
                // Returns "r,g,b" of the current brand accent for use in rgba()
                brandRgb: () => window.FitCore._hexToRgb(window.FitCore.brandHex()),
                grid:    () => document.documentElement.classList.contains('light-mode') ? 'rgba(0,0,0,0.05)'  : 'rgba(255,255,255,0.04)',
                text:    () => document.documentElement.classList.contains('light-mode') ? '#1f2937'            : '#9ca3af',
                tooltip: () => document.documentElement.classList.contains('light-mode') ? '#ffffff'            : '#1f2937'
            }
        };
    </script>
    @yield('scripts')
</body>
</html>
