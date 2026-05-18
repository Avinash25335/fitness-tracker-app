@extends('layouts.dashboard')

@section('page-title', 'Elite Analytics')
@section('page-subtitle', 'Real-time performance intelligence & AI coaching')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div class="hidden lg:block"></div>
    <a href="{{ route('export.progress') }}" target="_blank" class="bg-adaptive border border-adaptive text-main-area px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center gap-3 hover:bg-brand hover:text-white hover:border-brand transition-all shadow-xl active:scale-95">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export Intelligence Report (PDF)
    </a>
</div>

<div class="space-y-10 fade-up">

    {{-- 🏆 GAMIFICATION DASHBOARD --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Level Progress Card --}}
        <div class="xl:col-span-2 bg-gradient-to-br from-gray-800 to-gray-900 border border-brand/20 rounded-[2.5rem] p-10 relative overflow-hidden group bg-adaptive border-adaptive shadow-2xl">
            <div class="absolute -right-10 -top-10 p-20 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-700">
                <svg class="w-64 h-64 text-brand" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-6 mb-8">
                    <div class="w-24 h-24 rounded-3xl bg-brand/10 border-2 border-brand/20 flex flex-col items-center justify-center text-brand bg-adaptive">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60">Level</span>
                        <span class="text-4xl font-black">{{ $user->stat->level ?? 1 }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-3xl font-black text-main-area tracking-tighter">{{ $user->name }}</h2>
                            <span class="text-xs font-black text-brand uppercase tracking-widest">{{ $user->stat->xp ?? 0 }} XP</span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">{{ $nextLevelXp - ($user->stat->xp ?? 0) }} XP to Next Level</p>
                    </div>
                </div>
                <div class="h-4 bg-white/5 rounded-full overflow-hidden border border-white/5 p-1 bg-adaptive border-adaptive shadow-inner">
                    <div class="h-full bg-gradient-to-r from-brand to-green-400 rounded-full transition-all duration-1000 shadow-[0_0_20px_rgba(34,197,94,0.4)]" style="width: {{ $progressToNextLevel }}%"></div>
                </div>
            </div>
        </div>

        {{-- Hero Stats Quick-View --}}
        <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-10 flex flex-col justify-between bg-adaptive border-adaptive shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-xl font-black text-main-area tracking-tight">Personal Records</h4>
                    @php
                        $userRank = $leaderboard->search(fn($s) => $user && $s->user_id === $user->id);
                        $rankDisplay = $userRank !== false ? $userRank + 1 : '--';
                    @endphp
                    <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Global Ranking: #{{ $rankDisplay }}</p>
                </div>
                <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-2xl bg-adaptive border border-adaptive">🏅</div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-8">
                <div class="bg-white/5 p-4 rounded-2xl bg-adaptive border border-adaptive">
                    <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1">Streak</p>
                    <p class="text-lg font-black text-orange-400">{{ $streak }} Days</p>
                </div>
                <div class="bg-white/5 p-4 rounded-2xl bg-adaptive border border-adaptive">
                    <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1">Badges</p>
                    <p class="text-lg font-black text-brand">{{ $achievements->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 1 ─ HERO STATS BAR --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        @php
            $heroStats = [
                ['label' => 'Current Weight',  'value' => $currentWeight.' kg',    'color' => 'text-brand',       'icon' => '⚖️'],
                ['label' => 'Goal Weight',      'value' => ($goalWeight ?: '--').' '.($goalWeight ? 'kg' : ''), 'color' => 'text-main-area', 'icon' => '🎯'],
                ['label' => 'Total Change',     'value' => ($totalChange > 0 ? '+' : '').$totalChange.' kg', 'color' => $totalChange <= 0 ? 'text-brand' : 'text-orange-400', 'icon' => '📉'],
                ['label' => 'Total Workouts',   'value' => ($user->stat->total_workouts ?? 0).' sessions', 'color' => 'text-purple-400', 'icon' => '🏋️'],
                ['label' => 'This Month',       'value' => $workoutsThisMonth.' sessions', 'color' => 'text-blue-400', 'icon' => '📅'],
                ['label' => 'Calories Burned',  'value' => number_format($totalCaloriesBurned).' kcal', 'color' => 'text-red-400', 'icon' => '🔥'],
            ];
        @endphp
        @foreach($heroStats as $stat)
        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5 hover:border-brand/30 transition-all group bg-adaptive border-adaptive shadow-sm">
            <p class="text-xl mb-2">{{ $stat['icon'] }}</p>
            <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
            <p class="text-xl font-black {{ $stat['color'] }} group-hover:scale-105 transition-transform origin-left">{{ $stat['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ROW 2 ─ ACHIEVEMENTS & LEADERBOARD --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        {{-- Achievements --}}
        <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-10 bg-adaptive border-adaptive shadow-xl">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-2xl font-black text-main-area tracking-tight">Unlocked Badges</h3>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Achievements & Milestones</p>
                </div>
                <span class="text-[10px] font-black bg-white/5 text-gray-400 px-4 py-2 rounded-full uppercase tracking-widest bg-adaptive border border-adaptive">{{ $achievements->count() }} / {{ $allAchievements->count() }}</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                @foreach($allAchievements as $a)
                    @php $isUnlocked = $achievements->contains('id', $a->id); @endphp
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="w-16 h-16 rounded-2xl mb-3 flex items-center justify-center text-2xl transition-all duration-500
                                   {{ $isUnlocked ? 'bg-brand/10 border-2 border-brand/20 grayscale-0 scale-110 shadow-[0_0_20px_rgba(34,197,94,0.2)]' : 'bg-white/5 border border-white/5 grayscale opacity-30 bg-adaptive border-adaptive' }}">
                            {{ $a->icon }}
                        </div>
                        <p class="text-[9px] font-black uppercase tracking-widest transition-colors {{ $isUnlocked ? 'text-main-area' : 'text-gray-600' }}">{{ $a->title }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Leaderboard --}}
        <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-10 bg-adaptive border-adaptive shadow-xl">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-2xl font-black text-main-area tracking-tight">Elite Leaderboard</h3>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Top Athletes by Experience</p>
                </div>
                <div class="w-10 h-10 bg-yellow-500/10 rounded-xl flex items-center justify-center text-yellow-500 bg-adaptive border border-adaptive shadow-inner">🏆</div>
            </div>
            <div class="space-y-4">
                @foreach($leaderboard->take(5) as $index => $stat)
                <div class="flex items-center justify-between p-4 rounded-2xl transition-all {{ $user && $stat->user_id === $user->id ? 'bg-brand/10 border border-brand/20 scale-[1.02]' : 'bg-white/5 border border-white/5 bg-adaptive border-adaptive shadow-sm' }}">
                    <div class="flex items-center gap-4">
                        <span class="w-6 text-center text-xs font-black {{ $index < 3 ? 'text-yellow-500' : 'text-gray-500' }}">#{{ $index + 1 }}</span>
                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-[10px] font-black text-main-area bg-adaptive border border-adaptive">
                            {{ substr($stat->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs font-black text-main-area">{{ $stat->user->name ?? 'Unknown Athlete' }}</p>
                            <p class="text-[8px] font-bold text-gray-500 uppercase tracking-widest">Level {{ $stat->level }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-black text-brand">{{ number_format($stat->xp) }}</p>
                        <p class="text-[8px] font-bold text-gray-500 uppercase tracking-widest">Total XP</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ROW 3 ─ AI COACH PANEL --}}
    <div class="bg-adaptive border border-brand/20 rounded-[2.5rem] p-10 shadow-2xl bg-adaptive border-adaptive">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-xl bg-brand/20 flex items-center justify-center text-brand shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-black text-brand uppercase tracking-[0.2em]">AI Performance Coach</span>
                    <p class="text-xs text-gray-500 mt-0.5">{{ count($aiInsights) }} personalised insight(s) — Workout · Diet · Recovery</p>
                </div>
            </div>
            <div class="flex gap-2 flex-wrap" id="aiFilterBtns">
                @php $filterCategories = ['All', 'Consistency', 'Goal Analysis', 'Diet AI', 'Recovery', 'Strength', 'Volume Balance', 'Streak']; @endphp
                @foreach($filterCategories as $f)
                <button onclick="filterInsights('{{ $f }}')"
                    class="ai-filter-btn px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all
                           {{ $f === 'All' ? 'bg-brand text-white' : 'bg-white/5 text-gray-500 hover:bg-white/10 hover:text-main-area bg-adaptive border border-adaptive' }}"
                    data-filter="{{ $f }}">
                    {{ $f }}
                </button>
                @endforeach
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" id="aiInsightsGrid">
            @foreach($aiInsights as $idx => $insight)
            @php
                $borderColor = match($insight['type']) {
                    'success' => 'border-brand/30 bg-brand/5',
                    'warning' => 'border-orange-500/30 bg-orange-500/5',
                    default   => 'border-blue-500/30 bg-blue-500/5',
                };
                $badgeColor = match($insight['type']) {
                    'success' => 'bg-brand/20 text-brand',
                    'warning' => 'bg-orange-500/20 text-orange-400',
                    default   => 'bg-blue-500/20 text-blue-400',
                };
            @endphp
            <div class="ai-card border {{ $borderColor }} rounded-2xl p-5 space-y-3 transition-all bg-adaptive shadow-sm" data-category="{{ $insight['category'] ?? 'General' }}">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-block px-2 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $badgeColor }}">{{ strtoupper($insight['type']) }}</span>
                    <span class="inline-block px-2 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest bg-white/10 text-gray-500 bg-adaptive border border-adaptive">{{ $insight['category'] ?? 'General' }}</span>
                </div>
                <p class="text-sm text-main-area leading-relaxed font-medium">{{ $insight['message'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ROW 4 ─ CHARTS GRID --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        {{-- Chart 1: Weight Trend --}}
        <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-8 bg-adaptive border-adaptive shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-main-area tracking-tight">Weight Trend</h3>
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Historical body weight</p>
                </div>
                <span class="text-xs font-black px-3 py-1.5 rounded-xl {{ $totalChange <= 0 ? 'bg-brand/10 text-brand' : 'bg-orange-500/10 text-orange-400' }}">
                    {{ $totalChange > 0 ? '+' : '' }}{{ $totalChange }} KG
                </span>
            </div>
            <div class="h-64"><canvas id="weightChart"></canvas></div>
        </div>

        {{-- Chart 2: Weekly Workouts --}}
        <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-8 bg-adaptive border-adaptive shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-main-area tracking-tight">Weekly Consistency</h3>
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Sessions per week (last 8 weeks)</p>
                </div>
                <span class="text-xs font-black bg-purple-500/10 text-purple-400 px-3 py-1.5 rounded-xl">{{ $workoutsThisWeek }} this week</span>
            </div>
            <div class="h-64"><canvas id="workoutsChart"></canvas></div>
        </div>

        {{-- Chart 3: Calories Burned --}}
        <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-8 bg-adaptive border-adaptive shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-main-area tracking-tight">Calories Burned</h3>
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Weekly calorie expenditure</p>
                </div>
                <span class="text-xs font-black bg-red-500/10 text-red-400 px-3 py-1.5 rounded-xl">{{ number_format($totalCaloriesBurned) }} total</span>
            </div>
            <div class="h-64"><canvas id="caloriesChart"></canvas></div>
        </div>

        {{-- Chart 4: Strength PR Graph --}}
        <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-8 bg-adaptive border-adaptive shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-main-area tracking-tight">Strength Progression</h3>
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Personal records over time</p>
                </div>
                <span class="text-xs font-black bg-yellow-500/10 text-yellow-400 px-3 py-1.5 rounded-xl">{{ count($prData) }} exercises tracked</span>
            </div>
            <div class="h-64">
                @if(count($prData) > 0)
                    <canvas id="strengthChart"></canvas>
                @else
                    <div class="h-full flex items-center justify-center">
                        <p class="text-gray-500 font-black text-xs uppercase tracking-widest text-center">Complete workouts with weight logging<br>to unlock strength charts</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ROW 5 ─ PR TABLE --}}
    @if(count($prData) > 0)
    <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-10 bg-adaptive border-adaptive shadow-2xl">
        <div class="flex items-center gap-3 mb-8">
            <span class="text-2xl">🏆</span>
            <div>
                <h3 class="text-xl font-black text-main-area tracking-tight">Personal Records</h3>
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Your strength history</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($prData as $exercise => $pr)
            <div class="bg-white/3 border border-white/5 rounded-2xl p-6 hover:border-brand/30 transition-all group bg-adaptive border-adaptive shadow-sm">
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">{{ $exercise }}</p>
                <div class="flex items-end justify-between mt-3">
                    <div>
                        <p class="text-[8px] text-gray-600 uppercase font-black">Starting</p>
                        <p class="text-lg font-black text-gray-400">{{ $pr['starting_weight'] }}kg</p>
                    </div>
                    <div class="text-center px-4">
                        <svg class="w-4 h-4 text-gray-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] text-brand uppercase font-black">Current PR</p>
                        <p class="text-2xl font-black text-main-area group-hover:text-brand transition-colors">{{ $pr['current_pr'] }}<span class="text-xs text-gray-500">kg</span></p>
                    </div>
                </div>
                @if($pr['improvement'] > 0)
                <div class="mt-4 pt-4 border-t border-white/5 border-adaptive">
                    <div class="flex items-center justify-between">
                        <span class="text-[8px] text-gray-600 uppercase font-black">Improvement</span>
                        <span class="text-xs font-black text-brand">+{{ $pr['improvement'] }}%</span>
                    </div>
                    <div class="h-1.5 bg-white/5 rounded-full mt-2 overflow-hidden bg-adaptive border border-adaptive">
                        <div class="h-full bg-gradient-to-r from-brand to-green-400 rounded-full" style="width: {{ min(100, $pr['improvement'] * 2) }}%"></div>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ROW 6 ─ FORMS & HISTORY --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="space-y-8">
            <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-10 space-y-8 bg-adaptive border-adaptive shadow-xl">
                <h3 class="text-xl font-black text-main-area tracking-tight">Log Today's Weight</h3>
                @if(session('success'))
                    <div class="bg-brand/10 border border-brand/20 text-brand px-4 py-3 rounded-xl text-sm font-bold">{{ session('success') }}</div>
                @endif
                <form action="{{ route('progress.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Weight (kg)</label>
                        <input type="number" step="0.1" name="weight" placeholder="00.0" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-2xl font-black text-main-area focus:border-brand transition-all outline-none bg-adaptive border-adaptive">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Date</label>
                        <input type="date" name="log_date" value="{{ date('Y-m-d') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-3 text-sm font-black text-main-area focus:border-brand transition-all outline-none bg-adaptive border-adaptive">
                    </div>
                    <button type="submit" class="w-full bg-brand text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-dark shadow-lg shadow-brand/20 transition-all active:scale-95">Save Progress →</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 bg-gray-800 border border-gray-700 rounded-[2.5rem] p-10 bg-adaptive border-adaptive shadow-xl">
            <h3 class="text-xl font-black text-main-area tracking-tight mb-8">Transformation History</h3>
            <div class="grid grid-cols-1 gap-4">
                @forelse($progressLogs->sortByDesc('log_date')->take(8) as $log)
                <div class="group flex items-center justify-between p-5 rounded-[2rem] bg-white/5 border border-white/5 hover:border-brand/30 transition-all bg-adaptive border-adaptive shadow-sm">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-brand group-hover:scale-110 transition-transform bg-adaptive border border-adaptive">⚖️</div>
                        <div>
                            <p class="text-xl font-black text-main-area">{{ $log->weight }} <span class="text-xs font-bold text-gray-500">KG</span></p>
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($log->log_date)->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($log->transformation_image)
                            <img src="{{ asset('storage/' . $log->transformation_image) }}" class="w-12 h-12 rounded-xl object-cover border border-white/10 group-hover:scale-125 transition-transform border-adaptive">
                        @else
                            <span class="text-[9px] font-black text-gray-600 uppercase tracking-widest">No Photo</span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-600 font-black uppercase tracking-widest text-xs py-10">Start your journey today</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function filterInsights(filter) {
    document.querySelectorAll('.ai-filter-btn').forEach(btn => {
        const isActive = btn.dataset.filter === filter;
        btn.className = btn.className.replace('bg-brand text-white', '').replace('bg-white/5 text-gray-500', '').trim();
        btn.classList.add(...(isActive ? ['bg-brand', 'text-white'] : ['bg-white/5', 'text-gray-500']));
    });
    document.querySelectorAll('.ai-card').forEach(card => {
        const show = filter === 'All' || card.dataset.category === filter;
        card.style.display = show ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const gridColor  = window.FitCore.colors.grid();
    const tickColor  = window.FitCore.colors.text();
    const tooltipBg  = window.FitCore.colors.tooltip();
    const brandRgb   = window.FitCore.colors.brandRgb();
    const brandHex   = window.FitCore.brandHex();
    const axisStyle  = { grid: { color: gridColor, drawBorder: false }, ticks: { color: tickColor, font: { weight: '900', size: 10 }, padding: 8 } };

    const CHART_DEFAULTS = { 
        responsive: true, 
        maintainAspectRatio: false, 
        plugins: { 
            legend: { 
                display: true, 
                position: 'top',
                align: 'end',
                labels: { 
                    color: tickColor, 
                    font: { size: 10, weight: '900' }, 
                    usePointStyle: true,
                    padding: 20
                },
                onClick: (e) => e.stopPropagation() // Disable click-to-hide
            } 
        } 
    };
    const tooltipPlugin = { tooltip: { backgroundColor: tooltipBg, titleColor: tickColor, bodyColor: tickColor, padding: 12, cornerRadius: 12 } };

    // 1. Weight Trend
    const wCtx = document.getElementById('weightChart')?.getContext('2d');
    if (wCtx) {
        const wGrad = wCtx.createLinearGradient(0, 0, 0, 300);
        wGrad.addColorStop(0, `rgba(${brandRgb},0.25)`);
        wGrad.addColorStop(1, `rgba(${brandRgb},0)`);
        new Chart(wCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($progressLogs->pluck('log_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))->toArray()) !!},
                datasets: [{ label: 'Weight (kg)', data: {!! json_encode($progressLogs->pluck('weight')->toArray()) !!}, borderColor: brandHex, backgroundColor: wGrad, borderWidth: 3, fill: true, tension: 0.4, pointRadius: 5, pointBackgroundColor: brandHex, pointBorderColor: window.FitCore.isLight() ? '#fff' : '#0a0f1a' }]
            },
            options: { ...CHART_DEFAULTS, scales: { y: axisStyle, x: { ...axisStyle, grid: { display: false } } }, plugins: { ...CHART_DEFAULTS.plugins, ...tooltipPlugin } }
        });
    }

    // 2. Weekly Workouts
    const wkCtx = document.getElementById('workoutsChart')?.getContext('2d');
    if (wkCtx) {
        new Chart(wkCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($weekLabels) !!},
                datasets: [{ label: 'Sessions', data: {!! json_encode($weeklyWorkouts) !!}, backgroundColor: 'rgba(168,85,247,0.4)', borderColor: 'rgba(168,85,247,0.8)', borderWidth: 2, borderRadius: 8 }]
            },
            options: { ...CHART_DEFAULTS, scales: { y: { ...axisStyle, ticks: { ...axisStyle.ticks, stepSize: 1 } }, x: { ...axisStyle, grid: { display: false } } }, plugins: { ...CHART_DEFAULTS.plugins, ...tooltipPlugin } }
        });
    }

    // 3. Calories Burned
    const calCtx = document.getElementById('caloriesChart')?.getContext('2d');
    if (calCtx) {
        const calGrad = calCtx.createLinearGradient(0, 0, 0, 300);
        calGrad.addColorStop(0, 'rgba(249,115,22,0.5)');
        calGrad.addColorStop(1, 'rgba(249,115,22,0.05)');
        new Chart(calCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($weekLabels) !!},
                datasets: [{ label: 'Calories', data: {!! json_encode($weeklyCalories) !!}, backgroundColor: calGrad, borderColor: 'rgba(249,115,22,0.9)', borderWidth: 2, borderRadius: 8 }]
            },
            options: { ...CHART_DEFAULTS, scales: { y: axisStyle, x: { ...axisStyle, grid: { display: false } } }, plugins: { ...CHART_DEFAULTS.plugins, ...tooltipPlugin } }
        });
    }

    // 4. Strength PRs
    @if(count($prData) > 0)
    const strCtx = document.getElementById('strengthChart')?.getContext('2d');
    if (strCtx) {
        const exercises = {!! json_encode(array_keys($prData)) !!};
        const colors = [brandHex, '#f59e0b', '#a855f7', '#3b82f6', '#ef4444', '#06b6d4'];
        const allDates = [...new Set(exercises.slice(0, 6).flatMap(name => {
            return {!! json_encode(collect($prData)->map(fn($v) => collect($v['history'])->pluck('date')->toArray())->toArray()) !!}[name] || [];
        }))].sort((a, b) => new Date(a + ' 2024') - new Date(b + ' 2024')); // basic sort

        const datasets = exercises.slice(0, 6).map((name, i) => {
            const history = {!! json_encode(collect($prData)->map(fn($v) => collect($v['history'])->map(fn($h) => ['date' => $h['date'], 'weight' => $h['weight']])->toArray())->toArray()) !!}[name] || [];
            
            // Map the history values to the exact index of the allDates array
            const dataMapped = allDates.map(date => {
                const record = history.find(h => h.date === date);
                return record ? record.weight : null;
            });

            return { 
                label: name, 
                data: dataMapped, 
                spanGaps: true,
                borderColor: colors[i % colors.length], 
                backgroundColor: 'transparent', 
                borderWidth: 2.5, 
                tension: 0.3, 
                pointRadius: 5, 
                pointBackgroundColor: colors[i % colors.length], 
                pointBorderColor: window.FitCore.isLight() ? '#fff' : '#0a0f1a' 
            };
        });

        new Chart(strCtx, {
            type: 'line',
            data: { labels: allDates, datasets },
            options: { ...CHART_DEFAULTS, plugins: { ...CHART_DEFAULTS.plugins, ...tooltipPlugin }, scales: { y: { ...axisStyle, ticks: { ...axisStyle.ticks, callback: v => v + 'kg' } }, x: { ...axisStyle, grid: { display: false } } } }
        });
    }
    @endif
});
</script>
@endsection
