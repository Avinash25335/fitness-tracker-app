<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessPro — Your Ultimate Fitness Platform</title>
    <meta name="description" content="Track workouts, follow personalised diet plans, and achieve your fitness goals with FitnessPro.">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand': '#22c55e',
                        'brand-dark': '#16a34a',
                        'brand-orange': '#f97316',
                        'surface': '#111827',
                        'surface-2': '#1f2937',
                        'border-col': '#374151',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { scroll-behavior: smooth; }
        body { background-color: #0a0f1a; color: #f9fafb; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .hero-glow { background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(34,197,94,0.15), transparent); }
        .btn-primary { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .btn-primary:hover { box-shadow: 0 0 30px rgba(34,197,94,0.35); transform: translateY(-2px); }
        .card { background: rgba(31,41,55,0.6); border: 1px solid #374151; backdrop-filter: blur(12px); }
        .feature-card:hover { border-color: rgba(34,197,94,0.4); transform: translateY(-4px); }
        .nav-link::after { content:''; display:block; height:2px; background:#22c55e; width:0; transition:width .3s; }
        .nav-link:hover::after { width:100%; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        .fade-up { animation: fadeUp 0.6s ease forwards; }
        .fade-up-2 { animation: fadeUp 0.6s 0.15s ease both; }
        .fade-up-3 { animation: fadeUp 0.6s 0.3s ease both; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        .float { animation: float 4s ease-in-out infinite; }
        .gradient-text { background: linear-gradient(135deg, #22c55e, #86efac, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        ::-webkit-scrollbar { width:4px; } ::-webkit-scrollbar-track { background:#111827; } ::-webkit-scrollbar-thumb { background:#374151; border-radius:2px; }
    </style>
</head>
<body class="antialiased">

<!-- ─── NAVBAR ─── -->
<nav class="fixed top-0 inset-x-0 z-50 bg-[#0a0f1a]/80 backdrop-blur-xl border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-xl font-extrabold text-white tracking-tight">Fitness<span class="text-brand">Pro</span></span>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('workouts.index') }}" class="nav-link text-sm text-gray-400 hover:text-white transition font-medium pb-0.5">Workouts</a>
                <a href="{{ route('diets.index') }}" class="nav-link text-sm text-gray-400 hover:text-white transition font-medium pb-0.5">Nutrition</a>
                <a href="{{ route('trainers.index') }}" class="nav-link text-sm text-gray-400 hover:text-white transition font-medium pb-0.5">Trainers</a>
                <a href="{{ route('blog.index') }}" class="nav-link text-sm text-gray-400 hover:text-white transition font-medium pb-0.5">Blog</a>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-all duration-200">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white font-medium transition">Sign In</a>
                    <a href="{{ route('register') }}" class="btn-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-all duration-200">Get Started Free</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- ─── HERO ─── -->
<section class="relative min-h-screen flex items-center hero-glow pt-16">
    <!-- BG Image -->
    <div class="absolute inset-0 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1920&auto=format&fit=crop"
             class="w-full h-full object-cover opacity-10" alt="Gym">
        <div class="absolute inset-0 bg-gradient-to-b from-[#0a0f1a]/30 via-[#0a0f1a]/70 to-[#0a0f1a]"></div>
    </div>

    <!-- Floating Glow Orbs -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand/8 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-brand-orange/8 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-brand/10 border border-brand/20 px-4 py-1.5 rounded-full text-sm text-brand font-semibold mb-8 fade-up">
            <span class="w-2 h-2 rounded-full bg-brand animate-pulse"></span>
            The #1 Fitness Platform for Serious Athletes
        </div>

        <!-- Headline -->
        <h1 class="text-5xl sm:text-6xl lg:text-8xl font-black text-white leading-none tracking-tight mb-6 fade-up-2">
            Transform Your<br>
            <span class="gradient-text">Body & Mind</span>
        </h1>

        <p class="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed fade-up-3">
            Personalised workout plans, smart nutrition tracking, expert trainers, and real-time progress analytics — all in one place.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16 fade-up-3">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary text-white font-bold px-8 py-4 rounded-2xl text-base transition-all duration-200 shadow-lg shadow-green-500/20">
                    Go to Dashboard →
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-primary text-white font-bold px-8 py-4 rounded-2xl text-base transition-all duration-200 shadow-lg shadow-green-500/20">
                    Start for Free — No Card Needed
                </a>
                <a href="{{ route('login') }}" class="bg-white/5 border border-white/10 hover:bg-white/10 text-white font-bold px-8 py-4 rounded-2xl text-base transition-all duration-200">
                    Sign In
                </a>
            @endauth
        </div>

        <!-- Stats Strip -->
        <div class="grid grid-cols-3 gap-4 max-w-lg mx-auto fade-up-3">
            @foreach([['10K+','Active Members'],['500+','Workout Plans'],['98%','Success Rate']] as [$num, $label])
            <div class="card rounded-2xl py-4">
                <p class="text-2xl font-extrabold text-brand">{{ $num }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── FEATURES ─── -->
<section class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
        <p class="text-brand font-semibold text-sm uppercase tracking-widest mb-3">Why FitnessPro</p>
        <h2 class="text-4xl font-extrabold text-white">Everything You Need to<br>Crush Your Goals</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['🏋️','Smart Workouts','Beginner to advanced plans curated by certified trainers. Each plan adapts to your level.','text-blue-400','bg-blue-500/10'],
            ['🥗','Nutrition Plans','Goal-based meal plans with detailed calorie and macro breakdowns for maximum results.','text-orange-400','bg-orange-500/10'],
            ['📈','Progress Tracking','Log your weight daily and visualise your transformation with interactive charts.','text-green-400','bg-green-500/10'],
            ['🤖','AI Recommendations','Rule-based engine recommends the best plan for your BMI and goals. OpenAI-ready.','text-purple-400','bg-purple-500/10'],
            ['👤','Expert Trainers','Book 1-on-1 sessions with certified professionals who specialise in your goals.','text-pink-400','bg-pink-500/10'],
            ['📱','Mobile-Ready','Fully responsive design that works beautifully on any device, anywhere, anytime.','text-yellow-400','bg-yellow-500/10'],
        ] as [$icon, $title, $desc, $color, $bg])
        <div class="card feature-card rounded-2xl p-6 transition-all duration-300 cursor-default">
            <div class="w-12 h-12 rounded-2xl {{ $bg }} flex items-center justify-center text-2xl mb-4">{{ $icon }}</div>
            <h3 class="font-bold text-white text-lg mb-2">{{ $title }}</h3>
            <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</section>

<!-- ─── HOW IT WORKS ─── -->
<section class="py-24 bg-surface/40 border-y border-white/5">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-brand font-semibold text-sm uppercase tracking-widest mb-3">Simple Process</p>
        <h2 class="text-4xl font-extrabold text-white mb-16">Start in 3 Easy Steps</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            <!-- Connector line -->
            <div class="hidden md:block absolute top-8 left-1/3 right-1/3 h-px bg-gradient-to-r from-transparent via-brand/40 to-transparent"></div>
            @foreach([
                ['01','Create Account','Sign up free in 30 seconds. Tell us your goals, height, and weight.'],
                ['02','Get Your Plan','Our smart engine instantly recommends the best workout and diet plan for you.'],
                ['03','Track Progress','Log workouts, update your weight daily, and watch your transformation unfold.'],
            ] as [$num, $title, $desc])
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-black text-xl mb-5 shadow-lg shadow-green-500/20 float">{{ $num }}</div>
                <h3 class="font-bold text-white text-xl mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── WORKOUTS PREVIEW ─── -->
@if(isset($featuredWorkouts) && $featuredWorkouts->isNotEmpty())
<section class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-12">
        <div>
            <p class="text-brand font-semibold text-sm uppercase tracking-widest mb-2">Popular Plans</p>
            <h2 class="text-3xl font-extrabold text-white">Featured Workouts</h2>
        </div>
        <a href="{{ route('workouts.index') }}" class="text-sm text-brand font-semibold hover:text-green-400 transition hidden sm:flex items-center gap-1">View all <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($featuredWorkouts as $workout)
        <a href="{{ route('workouts.show', $workout) }}" class="card rounded-2xl p-6 hover:border-brand/30 hover:-translate-y-1 transition-all duration-300 block group">
            <div class="w-10 h-10 rounded-xl bg-brand/10 flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span class="text-xs font-bold uppercase text-brand/70 tracking-wider">{{ $workout->level }}</span>
            <h3 class="text-lg font-bold text-white mt-1 mb-2 group-hover:text-brand transition-colors">{{ $workout->title }}</h3>
            <p class="text-sm text-gray-500 line-clamp-2">{{ $workout->description }}</p>
            <div class="flex items-center gap-1 mt-4 text-xs text-brand font-semibold">Start Plan <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></div>
        </a>
        @endforeach
    </div>
</section>
@endif

<!-- ─── TESTIMONIALS ─── -->
<section class="py-24 bg-surface/40 border-y border-white/5">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-brand font-semibold text-sm uppercase tracking-widest mb-3">Success Stories</p>
        <h2 class="text-4xl font-extrabold text-white mb-16">Real People, Real Results</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['Aditya K.','Lost 18 kg in 4 months using the weight-loss plan and daily logging. The charts kept me motivated every single day!','Weight Loss · -18 kg'],
                ['Priya M.','Gained 8 kg of lean muscle. The AI recommendations matched me perfectly to a high-protein diet and strength program.','Muscle Gain · +8 kg'],
                ['Rahul S.','Booked a trainer through the platform and my form improved drastically. Best investment in my fitness journey.','Trainer Booking'],
            ] as [$name, $quote, $result])
            <div class="card rounded-2xl p-6 text-left">
                <div class="flex gap-1 mb-4">@for($i=0;$i<5;$i++)<svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor</div>
                <p class="text-gray-300 text-sm leading-relaxed italic mb-5">"{{ $quote }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-bold text-sm">{{ strtoupper($name[0]) }}</div>
                    <div>
                        <p class="text-sm font-semibold text-white">{{ $name }}</p>
                        <p class="text-xs text-brand">{{ $result }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── CTA BANNER ─── -->
<section class="py-24 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <div class="card rounded-3xl p-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand/10 via-transparent to-brand-orange/10"></div>
        <div class="relative z-10">
            <h2 class="text-4xl font-black text-white mb-4">Ready to Start Your<br><span class="gradient-text">Transformation?</span></h2>
            <p class="text-gray-400 mb-8 max-w-md mx-auto">Join thousands of members who track their fitness and achieve real results with FitnessPro.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary inline-block text-white font-bold px-10 py-4 rounded-2xl text-base transition-all duration-200 shadow-lg shadow-green-500/20">Go to My Dashboard →</a>
            @else
                <a href="{{ route('register') }}" class="btn-primary inline-block text-white font-bold px-10 py-4 rounded-2xl text-base transition-all duration-200 shadow-lg shadow-green-500/20">Create Free Account →</a>
            @endauth
        </div>
    </div>
</section>

<!-- ─── FOOTER ─── -->
<footer class="border-t border-white/5 py-12 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-lg font-extrabold text-white">Fitness<span class="text-brand">Pro</span></span>
                </div>
                <p class="text-sm text-gray-500 max-w-xs leading-relaxed">Your all-in-one fitness companion. Track, improve, and transform — every single day.</p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white mb-4">Platform</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="{{ route('workouts.index') }}" class="hover:text-brand transition">Workouts</a></li>
                    <li><a href="{{ route('diets.index') }}" class="hover:text-brand transition">Nutrition</a></li>
                    <li><a href="{{ route('trainers.index') }}" class="hover:text-brand transition">Trainers</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-brand transition">Blog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white mb-4">Legal</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="{{ route('privacy') }}" class="hover:text-brand transition">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-brand transition">Terms of Service</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-brand transition">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div class="pt-6 border-t border-white/5 text-center text-xs text-gray-600">
            &copy; {{ date('Y') }} FitnessPro. All rights reserved. Built with ❤️ for fitness enthusiasts.
        </div>
    </div>
</footer>

</body>
</html>
