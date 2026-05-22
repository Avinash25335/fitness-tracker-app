@extends('layouts.dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Your fitness command center')

@section('content')
<!-- Achievement Popup Overlay -->
<div id="achievementPopup" class="hidden fixed inset-0 z-[300] bg-black/60 backdrop-blur-md flex items-center justify-center p-6">
    <div class="bg-surface-2 border-2 border-brand/50 rounded-[3rem] p-12 max-w-sm w-full text-center space-y-6 shadow-2xl scale-90 transition-all duration-500" id="achievementCard">
        <div class="w-24 h-24 bg-brand/20 rounded-full flex items-center justify-center text-brand mx-auto shadow-lg animate-bounce">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
        </div>
        <div class="space-y-2">
            <h3 class="text-[10px] font-black text-brand uppercase tracking-[0.3em]">Achievement Unlocked</h3>
            <h2 id="achievementTitle" class="text-3xl font-black text-text-main tracking-tighter">Legendary Session</h2>
        </div>
        <button onclick="closeAchievement()" class="w-full bg-brand text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-green-600 transition-all">Claim Rewards</button>
    </div>
</div>

<div class="space-y-10 fade-up">
    
    <!-- One Clear Action: Resume Workout -->
    @php
        $activeUserPlan = Auth::user()->userPlans()->where('is_completed', false)->first();
        $goalMsg = match($profile?->goal) {
            'weight_loss'  => "Focus on calorie deficit and high-intensity cardio today 🔥",
            'muscle_gain'  => "Prioritize high-protein meals and progressive overload 💪",
            'maintenance'  => "Maintain consistency and focus on balanced macros ⚖️",
            default        => "Stay consistent and keep pushing your limits 🚀"
        };
    @endphp
    @if($activeUserPlan && isset($activeUserPlan->workout_plan_id))
    <div class="bg-gradient-to-r from-brand to-brand-dark rounded-[2.5rem] p-8 shadow-xl shadow-brand/20 flex flex-col md:flex-row items-center justify-between gap-6 group">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center text-white">
                <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-white tracking-tight">Active Training Session</h3>
                <p class="text-white/80 text-xs font-bold uppercase tracking-widest mt-1">{{ $goalMsg }}</p>
            </div>
        </div>
        <a href="{{ route('workouts.show', $activeUserPlan->workout_plan_id) }}" onclick="this.innerText='Loading...'" class="bg-white text-brand px-10 py-5 rounded-[1.5rem] font-black text-sm uppercase tracking-widest hover:scale-105 transition-all shadow-xl active:scale-95 flex items-center gap-3">
            ▶ Resume Workout
    </div>
    @endif

    <!-- Profile Overview Card -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 card p-10 relative overflow-hidden group bg-adaptive border-adaptive shadow-xl">
            <div class="absolute right-0 top-0 p-12 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-48 h-48 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-brand/10 flex items-center justify-center text-brand border border-brand/20 shadow-inner bg-adaptive">
                            <span class="text-3xl font-black" id="levelDisplay">{{ $user->stat?->level ?? 1 }}</span>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-main-area tracking-tighter">Welcome, {{ $user->name }}</h2>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1" id="aiMessage">SMART GOAL: {{ strtoupper(str_replace('_', ' ', $profile?->goal ?? 'FITNESS')) }}</p>
                        </div>
                    </div>
                    
                    <!-- XP Bar -->
                    <div class="w-full max-w-sm space-y-3">
                        <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-500">
                            <span>Experience Points</span>
                            <span id="xpText" class="text-main-area">{{ $user->stat?->xp ?? 0 }} / {{ ($user->stat?->level ?? 1) * 100 }} XP</span>
                        </div>
                        <div class="h-3 bg-white/5 rounded-full overflow-hidden border border-white/5 p-0.5 bg-adaptive border-adaptive">
                            <div id="xpBar" class="h-full bg-brand shadow-[0_0_15px_rgba(34,197,94,0.4)] transition-all duration-1000 rounded-full" style="width: {{ (($user->stat?->xp ?? 0) / (($user->stat?->level ?? 1) * 100)) * 100 }}%"></div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        @if($profile?->goal)
                            <span class="text-[10px] font-black text-brand uppercase tracking-widest px-4 py-2 rounded-full bg-brand/5 border border-brand/20 bg-adaptive">
                                🎯 {{ str_replace('_', ' ', $profile->goal) }}
                            </span>
                        @endif
                        <span class="text-[10px] font-black text-orange-400 uppercase tracking-widest px-4 py-2 rounded-full bg-orange-500/5 border border-orange-500/20 flex items-center gap-2 bg-adaptive">
                            <span class="animate-pulse">🔥</span> {{ $user->stat?->streak ?? 0 }} Day Streak
                        </span>
                    </div>
                </div>
                <div class="text-right space-y-2">
                    <p class="text-5xl font-black text-main-area tracking-tighter">{{ $user->stat?->total_workouts ?? 0 }}</p>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Workouts</p>
                    @if(!$activeUserPlan)
                        <a href="{{ route('workouts.index') }}" class="inline-block mt-4 bg-brand text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-dark transition-all shadow-lg active:scale-95">Explore Plans</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Weekly Activity & Smart Reminders -->
        <div class="card p-8 bg-adaptive border-adaptive shadow-xl">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">Smart Reminders</h3>
                <div class="w-2 h-2 rounded-full bg-brand animate-pulse"></div>
            </div>
            <div class="space-y-4">
                @forelse($upcomingSessions as $session)
                    <div class="bg-blue-500/5 border border-blue-500/20 p-4 rounded-2xl flex items-center justify-between bg-adaptive">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">📅</span>
                            <div>
                                <p class="text-[10px] font-black text-main-area uppercase tracking-widest">Session w/ {{ $session->trainer->name ?? 'Trainer' }}</p>
                                <p class="text-[8px] font-bold text-gray-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($session->session_date)->format('M d') }} @ {{ $session->time_slot }}</p>
                            </div>
                        </div>
                        <a href="{{ route('trainers.index') }}" class="text-[8px] font-black text-brand border border-brand/30 px-2 py-1 rounded-lg uppercase">View</a>
                    </div>
                @empty
                    @if(!$mealReminder)
                        <p class="text-[9px] text-gray-500 font-black uppercase tracking-widest text-center py-4">No urgent alerts 🛡️</p>
                    @endif
                @endforelse
            </div>

            <div class="pt-8 border-t border-adaptive">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-[10px] font-black text-main-area uppercase tracking-widest">Growth Curve</h3>
                </div>
                <div class="flex-1 min-h-[160px] relative">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card p-8 group bg-adaptive border-adaptive shadow-xl">
            <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Total Time</p>
            <p class="text-3xl font-black text-main-area group-hover:text-brand transition-colors" id="statTime">0 <span class="text-xs">MINS</span></p>
        </div>
        <div class="card p-8 group bg-adaptive border-adaptive shadow-xl">
            <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Weekly Load</p>
            <p class="text-3xl font-black text-main-area group-hover:text-brand transition-colors" id="statAvg">0.0 <span class="text-xs">/ WEEK</span></p>
        </div>
        <div class="card p-8 group border-brand/20 bg-brand/5 relative overflow-hidden">
            <div class="absolute right-[-10px] top-[-10px] opacity-10 rotate-12">
                <svg class="w-16 h-16 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="text-[9px] font-black text-brand uppercase tracking-widest mb-2">Nutrition Hub</p>
            <a href="{{ route('diets.index') }}" class="text-xl font-black text-main-area hover:text-brand transition-all flex items-center gap-2">
                Generate Diet <span class="text-brand">→</span>
            </a>
        </div>
        <div class="card p-8 group bg-adaptive border-adaptive shadow-xl">
            <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Peak Streak</p>
            <p class="text-3xl font-black text-orange-400 group-hover:text-orange-500 transition-colors" id="statStreak">0 <span class="text-xs">DAYS</span></p>
        </div>
    </div>

    <!-- 🤖 AI Nutrition Logger -->
    <div class="card p-10 bg-adaptive border-adaptive shadow-xl relative overflow-hidden group">
        <div class="absolute right-0 top-0 p-12 opacity-5 group-hover:opacity-10 transition-opacity">
            <svg class="w-48 h-48 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        
        <div class="flex items-center gap-4 mb-8 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white shadow-lg shadow-brand/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-main-area tracking-tight">AI Nutrition Logger</h3>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Powered by NVIDIA NIM</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
            <div class="lg:col-span-2 space-y-4">
                <form id="aiCalorieForm" class="flex flex-col sm:flex-row gap-4">
                    <input type="text" id="mealInput" placeholder="What did you eat? e.g. 2 boiled eggs and a slice of toast" class="flex-1 bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all placeholder-gray-500" required>
                    <button type="submit" id="logMealBtn" class="bg-brand text-white font-black uppercase text-xs tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-brand/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                        <span>Analyze & Log</span>
                        <svg class="w-4 h-4 hidden animate-spin" id="logSpinner" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </form>
                
                <!-- Today's Logs Display -->
                <div class="bg-surface-3/50 p-6 rounded-[2rem] border border-adaptive bg-adaptive mt-6">
                    <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4">Today's Meals</h4>
                    <div class="space-y-3 max-h-[150px] overflow-y-auto no-scrollbar" id="todayMealsContainer">
                        @forelse($todayCalorieLogs ?? [] as $log)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5 bg-adaptive border-adaptive">
                                <span class="text-xs font-bold text-main-area">{{ $log->meal_description }}</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-black text-brand bg-brand/10 px-2 py-1 rounded-md">{{ $log->calories }} kcal</span>
                                    <span class="text-[9px] font-bold text-gray-500">P:{{ $log->protein }} C:{{ $log->carbs }} F:{{ $log->fats }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 font-bold italic" id="emptyMealsText">No meals logged today yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Summary Box -->
            <div class="bg-gradient-to-br from-brand/10 to-brand/5 border border-brand/20 rounded-[2.5rem] p-8 flex flex-col justify-center items-center text-center">
                <p class="text-[10px] font-black text-brand uppercase tracking-widest mb-2">Today's Intake</p>
                <div class="relative">
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="none" class="text-brand/10" />
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="none" class="text-brand shadow-[0_0_15px_rgba(34,197,94,0.4)] transition-all duration-1000" stroke-dasharray="351.8" stroke-dashoffset="{{ max(0, 351.8 - (351.8 * min(1, ($todayTotalCalories ?? 0) / 2500))) }}" id="calorieCircle" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-main-area" id="calorieTotal">{{ $todayTotalCalories ?? 0 }}</span>
                        <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest mt-1">/ 2500 kcal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Achievements -->
    <div class="card p-10 bg-adaptive border-adaptive shadow-xl">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h3 class="text-2xl font-black text-main-area tracking-tight">Unlocked Achievements</h3>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Consistency pays in trophies</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
            </div>
        </div>
        <div id="achievementsContainer" class="flex items-center gap-6 overflow-x-auto no-scrollbar pb-4">
            <p class="text-gray-500 font-black uppercase tracking-widest text-[10px]">Scanning trophies...</p>
        </div>
    </div>

    <!-- Sidebar Integration (Heatmap & AI Coach) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 card p-10">
            <h3 class="text-sm font-black text-white uppercase tracking-[0.2em] mb-10">Activity Heatmap</h3>
            <div class="grid grid-cols-7 gap-3" id="calendarGrid"></div>
        </div>
        
        <div class="card p-10 bg-gradient-to-br from-brand/20 to-brand/5 border border-brand/20">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-brand/20 flex items-center justify-center text-brand">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest">AI Performance Cue</h4>
            </div>
            <p id="aiBox" class="text-sm text-gray-400 leading-relaxed font-medium italic">"Analyzing your consistency metrics..."</p>
            <div class="mt-8 pt-8 border-t border-brand/10">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Today's Goal Logic</p>
                <p class="text-xs text-white font-bold">{{ $goalMsg }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const achievementPopup = document.getElementById('achievementPopup');
    const achievementCard = document.getElementById('achievementCard');
    const achievementTitle = document.getElementById('achievementTitle');

    function playSuccessSound() {
        const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2013/2013-preview.mp3');
        audio.volume = 0.5;
        audio.play().catch(e => {});
    }

    window.showAchievement = function(title) {
        achievementTitle.innerText = title;
        achievementPopup.classList.remove('hidden');
        playSuccessSound();
        setTimeout(() => achievementCard.classList.replace('scale-90', 'scale-100'), 10);
    };

    window.closeAchievement = function() {
        achievementCard.classList.replace('scale-100', 'scale-90');
        setTimeout(() => achievementPopup.classList.add('hidden'), 300);
    };

    // 1. AI Recommendation
    async function loadAIRecommendation() {
        const res = await fetch('/api/recommendation');
        const data = await res.json();
        document.getElementById("aiBox").innerText = `"${data.message}"`;
    }

    // 2. Weekly Activity (BEAUTIFUL ANALYTICS)
    async function loadWeeklyChart() {
        const res = await fetch('/api/weekly-stats');
        const data = await res.json();
        const ctx = document.getElementById("weeklyChart").getContext("2d");
        
        const isLight = document.documentElement.classList.contains('light-mode');
        const gridColor = isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)';
        const textColor = isLight ? '#6b7280' : '#4b5563';

        // Gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 160);
        gradient.addColorStop(0, 'rgba(34, 197, 94, 0.2)');
        gradient.addColorStop(1, 'rgba(34, 197, 94, 0)');

        const labels = data.map(d => new Date(d.date).toLocaleDateString('en-US', { weekday: 'short' }));
        const values = data.map(d => d.total);

        new Chart(ctx, {
            type: "line",
            data: {
                labels: labels.length ? labels : ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
                datasets: [{
                    label: "Workouts",
                    data: values.length ? values : [0,0,0,0,0,0,0],
                    borderColor: '#22c55e',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#22c55e',
                    pointBorderColor: isLight ? '#ffffff' : '#0a0f1a',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isLight ? '#ffffff' : '#1f2937',
                        titleColor: isLight ? '#111827' : '#ffffff',
                        bodyColor: isLight ? '#374151' : '#d1d5db',
                        titleFont: { size: 10, weight: 'black' },
                        bodyFont: { size: 12, weight: 'bold' },
                        displayColors: false,
                        padding: 12,
                        cornerRadius: 12,
                        borderWidth: 1,
                        borderColor: isLight ? 'rgba(0,0,0,0.1)' : 'rgba(255,255,255,0.1)'
                    }
                },
                scales: { 
                    y: { display: false, beginAtZero: true }, 
                    x: { grid: { display: false }, ticks: { color: textColor, font: { weight: 'black', size: 9 } } } 
                }
            }
        });
    }

    // 3. Heatmap
    async function loadCalendar() {
        const grid = document.getElementById('calendarGrid');
        const res = await fetch('/api/calendar');
        const sessions = await res.json();
        const completedDates = sessions.map(s => new Date(s.completed_at).toISOString().split('T')[0]);
        for (let i = 27; i >= 0; i--) {
            const date = new Date(); date.setDate(date.getDate() - i);
            const dateStr = date.toISOString().split('T')[0];
            const el = document.createElement('div');
            el.className = `aspect-square rounded-xl border border-adaptive transition-all duration-700 ${completedDates.includes(dateStr) ? 'bg-brand shadow-[0_0_15px_rgba(34,197,94,0.4)] scale-110' : 'bg-adaptive'}`;
            grid.appendChild(el);
        }
    }

    // 4. Analytics & Achievements
    async function loadAnalytics() {
        const res = await fetch('/api/analytics');
        const data = await res.json();
        document.getElementById('statTime').innerHTML = `${data.total_time} <span class="text-xs">MINS</span>`;
        document.getElementById('statAvg').innerHTML = `${data.avg_per_week} <span class="text-xs">/ WEEK</span>`;
        document.getElementById('statStreak').innerHTML = `${data.streak} <span class="text-xs">DAYS</span>`;
    }

    async function loadAchievements() {
        const res = await fetch('/api/achievements');
        const data = await res.json();
        const container = document.getElementById('achievementsContainer');
        container.innerHTML = '';
        const lastSeenCount = localStorage.getItem('achievement_count') || 0;
        if (data.length > lastSeenCount) {
            showAchievement(data[data.length - 1]);
            localStorage.setItem('achievement_count', data.length);
        }
        if (data.length === 0) {
            container.innerHTML = '<p class="text-gray-500 font-black uppercase tracking-widest text-[9px]">Unlock trophies through consistency</p>';
            return;
        }
        data.forEach(a => {
            const badge = document.createElement('div');
            badge.className = "flex-shrink-0 bg-adaptive border border-adaptive hover:border-orange-500/30 px-6 py-5 rounded-[2rem] flex items-center gap-4 group transition-all cursor-default shadow-xl";
            badge.innerHTML = `
                <span class="text-2xl">${a.split(' ')[0]}</span>
                <div>
                    <p class="text-[10px] font-black text-main-area uppercase tracking-widest">${a.split(' ').slice(1).join(' ')}</p>
                    <p class="text-[8px] font-black text-brand uppercase tracking-widest mt-0.5">UNLOCKED</p>
                </div>
            `;
            container.appendChild(badge);
        });
    }

    // 5. AI Nutrition Logger
    const aiCalorieForm = document.getElementById('aiCalorieForm');
    if (aiCalorieForm) {
        aiCalorieForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const input = document.getElementById('mealInput');
            const btn = document.getElementById('logMealBtn');
            const spinner = document.getElementById('logSpinner');
            const btnText = btn.querySelector('span');
            
            if (!input.value.trim()) return;

            btn.disabled = true;
            btnText.innerText = 'Analyzing...';
            spinner.classList.remove('hidden');

            try {
                const res = await fetch('/nutrition/log', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ meal_description: input.value })
                });
                
                const data = await res.json();
                
                if (!res.ok) throw new Error(data.error || 'Failed to analyze meal');
                
                if (data.success) {
                    showToast('Meal analyzed & logged successfully!', 'success');
                    input.value = '';
                    
                    // Add new log to the list
                    const container = document.getElementById('todayMealsContainer');
                    const emptyText = document.getElementById('emptyMealsText');
                    if (emptyText) emptyText.remove();
                    
                    const newLog = document.createElement('div');
                    newLog.className = 'flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5 bg-adaptive border-adaptive animate-slide-up';
                    newLog.innerHTML = `
                        <span class="text-xs font-bold text-main-area">${data.data.meal_description}</span>
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-black text-brand bg-brand/10 px-2 py-1 rounded-md">${data.data.calories} kcal</span>
                            <span class="text-[9px] font-bold text-gray-500">P:${data.data.protein} C:${data.data.carbs} F:${data.data.fats}</span>
                        </div>
                    `;
                    container.prepend(newLog);
                    
                    // Update Circle & Total
                    const totalEl = document.getElementById('calorieTotal');
                    const currentTotal = parseInt(totalEl.innerText) + data.data.calories;
                    totalEl.innerText = currentTotal;
                    
                    const circle = document.getElementById('calorieCircle');
                    const maxVal = 2500;
                    const offset = Math.max(0, 351.8 - (351.8 * Math.min(1, currentTotal / maxVal)));
                    circle.style.strokeDashoffset = offset;
                }
            } catch (err) {
                showToast(err.message, 'error');
            } finally {
                btn.disabled = false;
                btnText.innerText = 'Analyze & Log';
                spinner.classList.add('hidden');
            }
        });
    }

    loadAIRecommendation();
    loadWeeklyChart();
    loadCalendar();
    loadAnalytics();
    loadAchievements();
});
</script>
@endsection
