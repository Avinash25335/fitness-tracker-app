<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel Fitness') }}</title>
    <!-- Tailwind CSS CDN for instant styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#030712',
                        darker: '#020617',
                        primary: {
                            DEFAULT: '#f43f5e',
                            dark: '#e11d48'
                        },
                        secondary: {
                            DEFAULT: '#fbbf24',
                            dark: '#f59e0b'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1)',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
            --primary: #f43f5e;
            --secondary: #fbbf24;
        }
        .light-mode {
            color-scheme: light;
        }
        body {
            background-color: #020617;
            color: #f8fafc;
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
        }
        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .glass-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .text-gradient {
            background: linear-gradient(to right, var(--primary), #fb7185);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #e11d48 100%);
            box-shadow: 0 10px 20px -5px rgba(244, 63, 94, 0.4);
        }
        .btn-primary:hover {
            box-shadow: 0 15px 25px -5px rgba(244, 63, 94, 0.6);
            transform: translateY(-2px);
        }
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body class="antialiased">
    <nav class="glass sticky top-0 z-50 p-4 border-b border-white/5">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-3xl font-extrabold tracking-tighter flex items-center">
                <span class="text-primary">FIT</span>
                <span class="text-white">CORE</span>
                <div class="w-2 h-2 rounded-full bg-primary ml-1"></div>
            </a>
            <div class="space-x-8 hidden md:flex items-center">
                <a href="{{ route('home') }}" class="nav-link font-medium text-gray-300 hover:text-white transition">Home</a>
                <a href="{{ route('workouts.index') }}" class="nav-link font-medium text-gray-300 hover:text-white transition">Workouts</a>
                <a href="{{ route('diets.index') }}" class="nav-link font-medium text-gray-300 hover:text-white transition">Diets</a>
                <a href="{{ route('trainers.index') }}" class="nav-link font-medium text-gray-300 hover:text-white transition">Trainers</a>
                <a href="{{ route('blog.index') }}" class="nav-link font-medium text-gray-300 hover:text-white transition">Blog</a>
                
                <button id="themeToggle" class="p-2 rounded-xl bg-white/5 hover:bg-white/10 transition border border-white/10">
                    <svg id="themeIcon" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-secondary font-bold hover:text-white transition">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-primary transition font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition font-medium">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary text-white px-6 py-2.5 rounded-xl font-bold transition">Join Now</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-darker text-gray-500 py-12 border-t border-white/5">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <div class="col-span-1 md:col-span-2">
                <div class="text-2xl font-bold text-white mb-4">FITCORE</div>
                <p class="max-w-sm">Elevate your fitness journey with personalized plans, smart recommendations, and a community that drives you forward.</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('privacy') }}" class="hover:text-primary transition">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-primary transition">Terms of Service</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-primary transition">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">Follow Us</h4>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-primary hover:text-white transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-primary hover:text-white transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.335 3.608 1.31.975.975 1.248 2.242 1.31 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.335 2.633-1.31 3.608-.975.975-2.242 1.248-3.608 1.31-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.335-3.608-1.31-.975-.975-1.248-2.242-1.31-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.335-2.633 1.31-3.608.975-.975 2.242-1.248 3.608-1.31 1.266-.058 1.646-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.337 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.397-.2 6.78-2.618 6.98-6.98.058-1.281.072-1.689.072-4.947s-.014-3.667-.072-4.947c-.2-4.349-2.619-6.78-6.98-6.98-1.281-.058-1.689-.072-4.948-.072zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                </div>
            </div>
        </div>
        <div class="text-center pt-8 border-t border-white/5">
            <p>&copy; {{ date('Y') }} FitCore. All rights reserved.</p>
        </div>
    </footer>

    <!-- 🔔 Notification Hub -->
    <div id="notification-hub" class="fixed top-5 right-5 z-[1000] space-y-4"></div>

    <script>
        // Global Toast System
        function showToast(msg, type = 'info') {
            const hub = document.getElementById('notification-hub');
            const toast = document.createElement("div");
            
            const colors = {
                'info': 'border-brand/30 bg-gray-900',
                'success': 'border-green-500/30 bg-green-900/20',
                'achievement': 'border-yellow-500/30 bg-yellow-900/20 shadow-[0_0_20px_rgba(234,179,8,0.2)]'
            };

            toast.className = `p-4 rounded-2xl border backdrop-blur-md text-white shadow-2xl font-black uppercase text-[10px] tracking-widest animate-slide-up flex items-center gap-4 ${colors[type] || colors.info}`;
            
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
                });
            } catch (e) {}
        }

        @auth
            setInterval(checkNotifications, 10000); // Check every 10s
        @endauth

        // Theme Toggle Logic
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        
        function setTheme(theme) {
            if (theme === 'light') {
                document.documentElement.classList.add('light-mode');
                themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>';
                document.body.style.backgroundColor = '#ffffff';
                document.body.style.color = '#1f2937';
            } else {
                document.documentElement.classList.remove('light-mode');
                themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>';
                document.body.style.backgroundColor = '#030712';
                document.body.style.color = '#f3f4f6';
            }
            localStorage.setItem('theme', theme);
        }

        const savedTheme = localStorage.getItem('theme') || 'dark';
        setTheme(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = localStorage.getItem('theme');
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });
    </script>
</body>
</html>
