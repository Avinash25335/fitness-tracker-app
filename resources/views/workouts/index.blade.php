@extends('layouts.dashboard')

@section('page-title', 'Workout Plans')
@section('page-subtitle', 'Choose and track your training program')

@section('content')
<div class="space-y-10 fade-up" x-data="{ filter: 'all' }">

    <!-- Adaptive AI Discovery Card (NEW) -->
    <div id="aiDiscoveryCard" class="hidden bg-gradient-to-br from-brand/20 to-brand/5 border border-brand/30 rounded-[2.5rem] p-8 sm:p-12 relative overflow-hidden group bg-adaptive">
        <div class="absolute right-0 top-0 p-12 opacity-5 group-hover:opacity-10 transition-opacity">
            <svg class="w-64 h-64 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="space-y-4 max-w-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand/20 flex items-center justify-center text-brand">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <span class="text-[10px] font-black text-brand uppercase tracking-[0.2em]">Adaptive AI Engine</span>
                </div>
                <h2 class="text-3xl font-black text-main-area tracking-tighter">Your Intelligence Report is Ready</h2>
                <p id="aiPlanMessage" class="text-gray-500 font-medium leading-relaxed">Analyzing your training history to optimize your next cycle...</p>
                <div class="flex items-center gap-6 pt-2">
                    <div>
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Recommended Level</p>
                        <span id="aiPlanLevel" class="text-xs font-black text-main-area bg-brand/20 px-3 py-1 rounded-lg border border-brand/20 uppercase tracking-widest">---</span>
                    </div>
                    <div class="w-px h-8 bg-white/5 border-adaptive"></div>
                    <div>
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Consistency Score</p>
                        <span id="aiPlanRate" class="text-xs font-black text-main-area tracking-widest">0%</span>
                    </div>
                </div>
            </div>
            <div class="shrink-0">
                <button @click="filter = document.getElementById('aiPlanLevel').innerText.toLowerCase()" class="bg-brand text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-green-600 transition-all shadow-lg shadow-brand/20 active:scale-95">
                    View Recommended Plans
                </button>
            </div>
        </div>
    </div>

    <!-- Header + Premium Pill Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-black text-main-area tracking-tight">Training Programs</h2>
            <p class="text-sm text-gray-500 mt-1">Science-backed plans designed for your success</p>
        </div>
        
        <div class="flex p-1.5 bg-surface-2 rounded-2xl border border-border-col bg-adaptive border-adaptive">
            <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-brand text-white shadow-lg scale-105' : 'text-gray-500 hover:text-brand hover:bg-brand/5'"
                class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                All
            </button>
            <button @click="filter = 'beginner'" :class="filter === 'beginner' ? 'bg-brand text-white shadow-lg scale-105' : 'text-gray-500 hover:text-brand hover:bg-brand/5'"
                class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-2">
                🟢 Beginner
            </button>
            <button @click="filter = 'intermediate'" :class="filter === 'intermediate' ? 'bg-yellow-500 text-white shadow-lg scale-105' : 'text-gray-500 hover:text-yellow-600 hover:bg-yellow-500/5'"
                class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-2">
                💪 Pro
            </button>
            <button @click="filter = 'advanced'" :class="filter === 'advanced' ? 'bg-red-500 text-white shadow-lg scale-105' : 'text-gray-500 hover:text-red-600 hover:bg-red-500/5'"
                class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-2">
                🔥 Elite
            </button>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @forelse($workouts as $index => $workout)
        @php
            $level = $workout->level ?? 'beginner';
            $userPlan = Auth::check() ? Auth::user()->userPlans()->where('plan_id', $workout->id)->where('is_completed', false)->first() : null;
            $isActive = (bool)$userPlan;
            
            $currentDay = $userPlan ? $userPlan->current_day : 0;
            $progressCount = $userPlan ? $userPlan->sessions()->where('completed', true)->count() : 0;
            $totalExpected = 30; // 30-day program standard
            $percent = round(($progressCount / $totalExpected) * 100);
            $isCompleted = $percent >= 100;

            $isRecommended = $index === 0 && !$isActive;
            
            $bannerClass = match($level) {
                'beginner'     => 'from-green-500 to-emerald-400',
                'intermediate' => 'from-yellow-500 to-orange-500',
                'advanced'     => 'from-red-500 to-rose-500',
                default        => 'from-brand to-brand-dark',
            };

            $btnText = $isCompleted ? '✓ Plan Completed' : ($isActive ? '✓ Following Plan' : 'Start Program →');
            $insight = match($level) {
                'beginner' => 'AI Suggested: Best for beginners',
                'intermediate' => 'Smart Choice: Best for muscle growth',
                'advanced' => 'Expert: Elite strength builder',
                default => 'Personalized for you',
            };
        @endphp
        
        <div class="flex flex-col h-full bg-gray-800 border transition-all duration-500 rounded-[2.5rem] overflow-hidden group relative bg-adaptive border-adaptive"
             x-show="filter === 'all' || filter === '{{ $level }}'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             :class="{
                'border-brand shadow-[0_0_40px_rgba(34,197,94,0.15)] scale-[1.01] z-10': {{ $isActive ? 'true' : 'false' }},
                'border-gray-700/50 shadow-xl hover:-translate-y-2 hover:shadow-brand/30 hover:border-brand/40': !{{ $isActive ? 'true' : 'false' }}
             }">
            
            <!-- Ripple Effect Overlay -->
            <div class="absolute inset-0 bg-white/0 group-active:bg-white/5 transition-colors pointer-events-none"></div>

            <!-- Top Status Bar -->
            <div class="h-1.5 w-full bg-gradient-to-r {{ $bannerClass }}"></div>

            <!-- Status Badges -->
            <div class="absolute top-6 right-6 flex flex-col items-end gap-2">
                @if($isActive)
                    <span class="bg-brand text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg border border-white/10 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                        Active
                    </span>
                @elseif($isCompleted)
                    <span class="bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">
                        ✓ Finished
                    </span>
                @elseif($isRecommended)
                    <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-black text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">
                        ⭐ Recommended
                    </span>
                @endif
            </div>

            <div class="p-8 flex flex-col flex-1 space-y-6">
                <!-- Meta Info -->
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] px-2.5 py-1 rounded-lg bg-white/5 border border-white/5 text-gray-500 bg-adaptive border-adaptive">
                        {{ strtoupper($level) }}
                    </span>
                    <span class="text-gray-700">•</span>
                    <div class="flex items-center gap-1.5 text-gray-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-[10px] font-black uppercase tracking-widest">30 Days Cycle</span>
                    </div>
                </div>

                <!-- Icon + Title -->
                <div class="flex items-start gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gray-700 to-gray-800 border border-white/5 flex items-center justify-center shrink-0 group-hover:border-brand/30 transition-all shadow-inner group-hover:scale-110 duration-500 bg-adaptive border-adaptive">
                        <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-black text-main-area leading-tight tracking-tight group-hover:text-brand transition-colors">
                            {{ $workout->title }}
                        </h3>
                        <p class="text-[10px] font-black text-brand uppercase tracking-widest mt-1 opacity-80">{{ $insight }}</p>
                    </div>
                </div>

                <!-- Detailed Progress (UX Fix) -->
                <div class="space-y-3">
                    <div class="flex justify-between items-end">
                        @if($isActive)
                            <div>
                                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Current Progress</p>
                                <p class="text-xs font-black text-main-area">DAY {{ $currentDay }} OF 30</p>
                            </div>
                            <span class="text-[10px] font-black text-brand">{{ $percent }}%</span>
                        @else
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Progress: 0% (Start Plan)</p>
                        @endif
                    </div>
                    <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden border border-white/5 bg-adaptive border-adaptive">
                        <div class="h-full bg-brand transition-all duration-1000 shadow-[0_0_15px_rgba(34,197,94,0.4)]" style="width: {{ $percent }}%"></div>
                    </div>
                </div>

                <!-- Description -->
                <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">
                    {{ $workout->description }}
                </p>

                <!-- Stats Divider -->
                <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/5 border-adaptive mt-auto">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.2em] mb-1">Estimated Burn</span>
                        <span class="text-sm font-black text-main-area">450 <span class="text-[9px] text-gray-600 uppercase">KCAL</span></span>
                    </div>
                    <div class="flex flex-col border-l border-white/5 pl-4">
                        <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.2em] mb-1">Difficulty</span>
                        <div class="flex gap-0.5">
                            @for($i=0; $i<3; $i++)
                                <div class="w-2.5 h-1 rounded-full {{ $i < (match($level){'beginner'=>1,'intermediate'=>2,'advanced'=>3}) ? 'bg-brand' : 'bg-gray-700' }}"></div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4">
                    <a href="{{ route('workouts.show', $workout) }}"
                        class="w-full px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-center transition-all duration-300 shadow-lg block 
                        {{ $isActive ? 'bg-brand/10 border border-brand/50 text-brand shadow-[0_0_20px_rgba(34,197,94,0.15)] hover:bg-brand/20' : 'bg-brand text-white hover:bg-brand-dark hover:shadow-brand/30' }}">
                        {{ $btnText }}
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-20 text-center">
            <p class="text-gray-500 font-black uppercase tracking-widest">No programs available.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    async function loadAdaptivePlan() {
        try {
            const res = await fetch('/api/adaptive-plan');
            const data = await res.json();
            
            const card = document.getElementById('aiDiscoveryCard');
            const messageEl = document.getElementById('aiPlanMessage');
            const levelEl = document.getElementById('aiPlanLevel');
            const rateEl = document.getElementById('aiPlanRate');

            messageEl.innerText = data.message;
            levelEl.innerText = data.level.toUpperCase();
            rateEl.innerText = data.completion_rate + "%";

            card.classList.remove('hidden');
            card.classList.add('fade-up');
        } catch (e) { console.error("AI Plan load error", e); }
    }

    loadAdaptivePlan();
});
</script>
@endsection
