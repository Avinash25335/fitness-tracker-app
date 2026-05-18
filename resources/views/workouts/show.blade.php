@extends('layouts.dashboard')

@section('page-title', $workout->title)
@section('page-subtitle', 'Interactive Workout Session')

@section('content')
<!-- Confetti & Toast Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<div class="space-y-6 fade-up relative">
    
    <!-- SUMMARY SCREEN -->
    <div id="summaryScreen" class="hidden fixed inset-0 z-[200] bg-gray-900/90 backdrop-blur-xl flex items-center justify-center p-6">
        <div class="bg-gray-800 border border-white/10 rounded-[2rem] lg:rounded-[3rem] p-6 lg:p-12 max-w-2xl w-full shadow-[0_20px_60px_rgba(0,0,0,0.5)] text-center space-y-6 lg:space-y-10 scale-95 transition-transform duration-500" id="summaryContent">
            <div class="space-y-4">
                <div class="w-16 h-16 lg:w-24 lg:h-24 bg-brand/20 rounded-full flex items-center justify-center text-brand mx-auto mb-4 lg:mb-6 shadow-[0_0_30px_rgba(34,197,94,0.3)]">
                    <svg class="w-8 h-8 lg:w-12 lg:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="text-3xl lg:text-5xl font-black text-white tracking-tighter">Session Complete!</h2>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px] lg:text-sm">You dominated today's routine</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white/5 p-8 rounded-[2rem] border border-white/5 group hover:border-brand/30 transition-all">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Total Time</p>
                    <p id="summaryTime" class="text-3xl font-black text-white tracking-tight">00:00</p>
                </div>
                <div class="bg-white/5 p-8 rounded-[2rem] border border-white/5 group hover:border-brand/30 transition-all">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Exercises</p>
                    <p id="summaryExercises" class="text-3xl font-black text-white tracking-tight">0 / 0</p>
                </div>
                <div class="bg-white/5 p-8 rounded-[2rem] border border-white/5 group hover:border-brand/30 transition-all">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">XP Earned</p>
                    <p id="summaryXP" class="text-3xl font-black text-brand tracking-tight">+0 XP</p>
                </div>
                <div class="bg-white/5 p-8 rounded-[2rem] border border-white/5 group hover:border-brand/30 transition-all">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Calories</p>
                    <p id="summaryCalories" class="text-3xl font-black text-orange-500 tracking-tight">0</p>
                </div>
                <div class="bg-white/5 p-8 rounded-[2rem] border border-white/5 group hover:border-brand/30 transition-all">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Streak</p>
                    <p id="summaryStreak" class="text-3xl font-black text-red-500 tracking-tight">0 Days</p>
                </div>
                <div class="bg-white/5 p-8 rounded-[2rem] border border-white/5 group hover:border-brand/30 transition-all">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Status</p>
                    <p class="text-xl font-black text-blue-400 tracking-tight uppercase">ELITE</p>
                </div>
            </div>

            <a href="{{ route('dashboard') }}" class="block w-full bg-brand text-white py-6 rounded-[1.5rem] font-black text-sm uppercase tracking-widest hover:bg-green-600 shadow-lg shadow-green-500/20 transition-all hover:scale-[1.02] active:scale-95">
                Finish & Return to Dashboard
            </a>
        </div>
    </div>

    <!-- Back Navigation -->
    <div class="flex items-center justify-between py-4">
        <a href="{{ route('workouts.index') }}" class="group inline-flex items-center gap-3 text-sm font-black text-white hover:text-brand transition-all bg-white/5 hover:bg-white/10 px-6 py-3 rounded-2xl border border-white/5">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            BACK TO DASHBOARD
        </a>
        <div class="flex items-center gap-4">
            <span id="dayBadge" class="text-[10px] font-black text-brand uppercase tracking-widest bg-brand/5 border border-brand/10 px-4 py-2 rounded-xl">DAY 1 OF CYCLE</span>
            <span id="streakBadge" class="text-[10px] font-black text-orange-400 uppercase tracking-widest bg-orange-500/5 border border-orange-500/10 px-4 py-2 rounded-xl">🔥 STREAK: 0</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Hero Card -->
            <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-10 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-12 opacity-5">
                    <svg class="w-48 h-48 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>

                <div class="relative z-10 space-y-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-gray-400">
                                {{ strtoupper($workout->level) }} SESSION
                            </span>
                            <span class="text-[10px] font-black text-brand uppercase tracking-widest px-4 py-1.5 rounded-full bg-brand/5 border border-brand/10" id="liveIndicator">
                                • TRACKING LIVE
                            </span>
                        </div>
                    </div>

                    <div id="exerciseHero">
                        <h1 id="activeExerciseName" class="text-3xl lg:text-5xl font-black text-white tracking-tighter">{{ $workout->title }}</h1>
                        <p id="activeExerciseSub" class="text-[10px] lg:text-sm text-gray-400 font-bold uppercase tracking-widest mt-2">Tap Start to begin your session</p>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row items-center gap-10">
                        <!-- Dynamic Status -->
                        <div class="flex flex-col gap-3">
                            <button id="startWorkoutBtn" data-plan-id="{{ $workout->id }}"
                                class="bg-brand text-white px-10 py-5 rounded-[1.5rem] font-black text-xs uppercase tracking-widest hover:bg-brand-dark shadow-brand/30 transition-all active:scale-95 flex items-center gap-3">
                                <span id="btnText">⚡ Start Session</span>
                            </button>
                            <button id="endSessionBtn" class="hidden text-[10px] font-black text-red-500 uppercase tracking-widest hover:text-red-400 transition-colors">
                                [ End Session Early ]
                            </button>
                        </div>

                        <!-- Progress Analytics -->
                        <div class="flex-1 w-full space-y-4">
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Session Progress</p>
                                    <h2 id="progressText" class="text-4xl font-black text-white">0%</h2>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-white font-black" id="countText">0 / {{ $workout->exercises->count() }}</p>
                                    <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Exercises Logged</p>
                                </div>
                            </div>
                            <div class="progress-bar w-full h-3 bg-white/5 rounded-full overflow-hidden border border-white/5">
                                <div id="progressBar" class="h-full bg-brand transition-all duration-500 shadow-[0_0_20px_rgba(34,197,94,0.5)]" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interactive Exercise Curriculum -->
            <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-10 space-y-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-black text-white tracking-tight">Today's Routine</h3>
                </div>

                <div class="grid grid-cols-1 gap-5" id="exerciseList">
                    @foreach($workout->exercises as $i => $exercise)
                    <div class="exercise-item-container relative group" id="ex-container-{{ $exercise->id }}">
                        <div class="exercise-item flex flex-col sm:flex-row items-start sm:items-center gap-6 p-6 rounded-[2rem] border border-white/5 bg-[#151a24] hover:border-white/10 transition-all duration-500 relative overflow-hidden"
                             data-id="{{ $exercise->id }}"
                             data-name="{{ $exercise->name }}"
                             data-sets="{{ $exercise->sets }}"
                             data-reps="{{ $exercise->reps }}">
                            
                            <!-- Custom Styled UI Box -->
                            <div class="checkbox-ui shrink-0 w-16 h-16 rounded-[1.25rem] bg-white/5 text-gray-500 flex items-center justify-center transition-all duration-300 relative z-10 border border-transparent">
                                <div class="flex flex-col items-center uncompleted-icon">
                                    <span class="font-black text-base">{{ $i + 1 }}</span>
                                    <span class="text-[8px] font-black opacity-40 uppercase tracking-widest">SET</span>
                                </div>
                                <div class="completed-icon hidden">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>

                            <!-- Identity -->
                            <div class="flex-1 min-w-0 relative z-10">
                                <h4 class="exercise-name text-xl font-black text-white tracking-tight transition-all duration-500">
                                    {{ $exercise->name }}
                                </h4>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $exercise->body_part }}</span>
                                    <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                                    <span class="text-[10px] font-black text-brand uppercase tracking-widest">{{ $exercise->sets }} Sets × {{ $exercise->reps }} Reps</span>
                                    <div id="history-{{ $exercise->id }}" class="hidden items-center gap-1.5 px-3 py-1 bg-white/5 rounded-lg border border-white/5">
                                        <span class="text-[8px] font-black text-gray-500 uppercase">Last:</span>
                                        <span class="text-[9px] font-black text-blue-400" id="history-val-{{ $exercise->id }}">--</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Performance Logging -->
                            <div class="flex items-center gap-4 relative z-10 mt-4 sm:mt-0 log-inputs">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest px-1">Sets</span>
                                    <input type="number" value="{{ $exercise->sets }}" class="w-14 bg-white/5 border border-white/10 rounded-xl px-2.5 py-2 text-xs font-black text-white focus:border-brand log-sets">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest px-1">Reps</span>
                                    <input type="number" value="{{ $exercise->reps }}" class="w-14 bg-white/5 border border-white/10 rounded-xl px-2.5 py-2 text-xs font-black text-white focus:border-brand log-reps">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest px-1">Weight</span>
                                    <input type="number" placeholder="--" class="w-14 bg-white/5 border border-white/10 rounded-xl px-2.5 py-2 text-xs font-black text-white focus:border-brand log-weight">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[8px] font-black text-orange-500 uppercase tracking-widest px-1">RPE</span>
                                    <select class="w-14 bg-white/5 border border-white/10 rounded-xl px-2 py-2 text-[10px] font-black text-white focus:border-brand log-rpe appearance-none">
                                        @for($r = 1; $r <= 10; $r++)
                                            <option value="{{ $r }}" {{ $r == 7 ? 'selected' : '' }} class="bg-gray-800">{{ $r }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <!-- Trigger -->
                            <div class="flex items-center gap-2 shrink-0 ml-2">
                                <button onclick="toggleMedia('{{ $exercise->id }}')" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-gray-500 hover:text-blue-400 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                <button class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-gray-600 hover:text-brand transition-all complete-trigger">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- 🎞️ MEDIA DRAWER (NEW) -->
                        <div id="media-{{ $exercise->id }}" class="hidden bg-gray-900/50 border-x border-b border-white/5 rounded-b-[2rem] p-6 mx-4 animate-fade-in space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="aspect-video bg-black rounded-2xl overflow-hidden flex items-center justify-center border border-white/10 group-hover:border-brand/40 transition-all">
                                    @if($exercise->media_url)
                                        <img src="{{ $exercise->media_url }}" class="w-full h-full object-cover opacity-80" alt="{{ $exercise->name }}">
                                    @else
                                        <img src="/assets/demo/exercise_placeholder.png" class="w-full h-full object-cover opacity-50" alt="{{ $exercise->name }}">
                                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 backdrop-blur-[2px]">
                                             <svg class="w-12 h-12 text-brand mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                             <p class="text-[8px] font-black text-white uppercase tracking-[0.2em]">Visual Demo Active</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[9px] font-black text-brand uppercase tracking-widest mb-1">Target</p>
                                            <p class="text-xs font-bold text-white uppercase tracking-tight">{{ $exercise->target_muscles ?? $exercise->body_part }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1">Category</p>
                                            <p class="text-xs font-bold text-white uppercase tracking-tight">{{ $exercise->muscle_group ?? 'Strength' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <p class="text-[9px] font-black text-white uppercase tracking-widest mb-2 border-l-2 border-brand pl-2">Common Mistakes</p>
                                        <ul class="text-[10px] text-red-400/80 space-y-1.5 font-medium leading-relaxed">
                                            @if($exercise->mistakes)
                                                @foreach(explode(',', $exercise->mistakes) as $mistake)
                                                    <li class="flex items-start gap-2">⚠️ {{ trim($mistake) }}</li>
                                                @endforeach
                                            @else
                                                <li class="flex items-start gap-2">⚠️ Arching your back during the heavy phase.</li>
                                                <li class="flex items-start gap-2">⚠️ Using momentum rather than controlled strength.</li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div>
                                        <p class="text-[9px] font-black text-white uppercase tracking-widest mb-2 border-l-2 border-blue-400 pl-2">Pro Form Cues</p>
                                        <ul class="text-[10px] text-gray-400 space-y-1.5 font-medium leading-relaxed">
                                            @if($exercise->form_cues)
                                                @foreach(explode(',', $exercise->form_cues) as $cue)
                                                    <li class="flex items-start gap-2">✅ {{ trim($cue) }}</li>
                                                @endforeach
                                            @else
                                                <li class="flex items-start gap-2">✅ Inhale on the way down, exhale on the effort.</li>
                                                <li class="flex items-start gap-2">✅ Maintain a neutral neck position throughout.</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- REST TIMER -->
            <div id="restBox" class="hidden bg-gradient-to-br from-brand/20 to-brand/5 border border-brand/20 rounded-[2.5rem] p-8 text-center animate-pulse shadow-[0_0_40px_rgba(34,197,94,0.1)]">
                <h3 class="text-sm font-black text-brand uppercase tracking-[0.2em] mb-4">Resting Time</h3>
                <div class="relative inline-flex items-center justify-center mb-4">
                    <svg class="w-24 h-24 transform -rotate-90">
                        <circle cx="48" cy="48" r="44" stroke="currentColor" stroke-width="4" fill="transparent" class="text-white/5"/>
                        <circle id="restRing" cx="48" cy="48" r="44" stroke="currentColor" stroke-width="4" fill="transparent" class="text-brand" stroke-dasharray="276" stroke-dashoffset="0"/>
                    </svg>
                    <span id="restTimer" class="absolute text-3xl font-black text-white">30</span>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Prepare for next set</p>
                <button id="skipRestBtn" class="mt-6 text-[10px] font-black text-white bg-brand/20 hover:bg-brand px-6 py-2 rounded-full transition-all uppercase tracking-widest">Skip Rest →</button>
            </div>

            <!-- UPCOMING EXERCISE (NEW) -->
            <div id="nextUpCard" class="hidden bg-gray-800 border border-gray-700 rounded-[2.5rem] p-8 space-y-4">
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Next Up</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-white font-black" id="nextUpIndex">2</div>
                    <div>
                        <h4 class="text-white font-black" id="nextUpName">Push Ups</h4>
                        <p class="text-[10px] text-brand font-bold uppercase tracking-widest" id="nextUpStats">3 Sets × 15 Reps</p>
                    </div>
                </div>
            </div>

            <!-- Session Stats -->
            <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-8 space-y-8">
                <h3 class="text-sm font-black text-white uppercase tracking-[0.2em]">Session Stats</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-[#151a24] p-5 rounded-2xl border border-white/5 flex items-center justify-between">
                        <div>
                            <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Time Elapsed</p>
                            <p class="text-2xl font-black text-white" id="sessionTimer">00:00</p>
                        </div>
                        <button id="pauseBtn" class="hidden w-10 h-10 rounded-xl bg-brand/10 border border-brand/20 flex items-center justify-center text-brand hover:bg-brand hover:text-white transition-all group">
                            <svg id="pauseIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <svg id="resumeIcon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 📱 STICKY MOBILE CONTROLS (NEW) -->
<div id="mobileActiveBar" class="lg:hidden fixed bottom-6 left-6 right-6 z-[150] bg-gray-900/80 backdrop-blur-xl border border-white/10 rounded-[2rem] p-4 shadow-2xl hidden animate-fade-in-up">
    <div class="flex items-center justify-between gap-4">
        <div class="flex-1 min-w-0">
            <p class="text-[8px] font-black text-brand uppercase tracking-widest mb-0.5">Active Exercise</p>
            <h4 class="text-white font-black text-sm truncate" id="mobileActiveName">Bench Press</h4>
            <p class="text-[9px] text-gray-500 font-bold" id="mobileActiveStats">Set 1 of 3</p>
        </div>
        <div class="flex items-center gap-3">
             <button onclick="toggleMedia(currentExerciseId)" class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </button>
            <button id="mobileCompleteBtn" class="bg-brand text-white h-14 px-8 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-brand/20 active:scale-95">
                LOG SET
            </button>
        </div>
    </div>
</div>

<style>
    #progressBar { transition: width 0.8s cubic-bezier(0.34, 1.56, 0.64, 1); }
    
    /* 🌟 ACTIVE EXERCISE GLOW */
    .exercise-item.active-exercise { 
        @apply border-brand bg-[#1c2331] scale-[1.02] shadow-[0_0_50px_rgba(34,197,94,0.15)];
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 20;
    }

    .active-exercise .checkbox-ui {
        @apply bg-brand/20 border-brand text-brand shadow-[0_0_20px_rgba(34,197,94,0.3)];
        animation: pulse-glow 2s infinite;
    }

    @keyframes pulse-glow {
        0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    /* ✅ COMPLETED STATE */
    .exercise-item.completed { 
        @apply bg-brand/5 border-brand/20 opacity-60 grayscale-[0.5]; 
        transform: scale(0.98);
    }
    .exercise-item.completed .checkbox-ui { @apply bg-brand border-brand shadow-none; }
    .exercise-item.completed .uncompleted-icon { display: none; }
    .exercise-item.completed .completed-icon { display: block; }
    .exercise-item.completed .exercise-name { @apply text-gray-400 line-through; }
    .exercise-item.completed .log-inputs { opacity: 0.3; pointer-events: none; }

    /* ⏱️ PREMIUM TIMER */
    #restRing { transition: stroke-dashoffset 1s linear; }
    
    .floating-feedback {
        animation: float-up 1.5s ease-out forwards;
        pointer-events: none;
    }
    @keyframes float-up {
        0% { transform: translateY(0) scale(0.5); opacity: 0; }
        20% { transform: translateY(-20px) scale(1.2); opacity: 1; }
        100% { transform: translateY(-100px) scale(1); opacity: 0; }
    }

    /* Mesh Gradient for Active */
    .active-bg-mesh {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 50%, rgba(34, 197, 94, 0.05) 0%, transparent 70%);
        opacity: 0.5;
    }
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const startBtn = document.getElementById('startWorkoutBtn');
    const endBtn = document.getElementById('endSessionBtn');
    const restBox = document.getElementById('restBox');
    const restTimerEl = document.getElementById('restTimer');
    const skipRestBtn = document.getElementById('skipRestBtn');
    const summaryScreen = document.getElementById('summaryScreen');
    
    if (!startBtn) return;
    
    const planId = startBtn.dataset.planId;
    let sessionId = null;
    let timerInterval = null;
    let exercises = Array.from(document.querySelectorAll('.exercise-item'));
    
    // ✅ PRO ENGINE STATE
    let currentExerciseIndex = localStorage.getItem(`curEx_${planId}`) ? parseInt(localStorage.getItem(`curEx_${planId}`)) : 0;
    let currentSet = localStorage.getItem(`curSet_${planId}`) ? parseInt(localStorage.getItem(`curSet_${planId}`)) : 1;
    let isResting = false;
    let restInterval;
    let restSeconds = 30;
    window.currentExerciseId = null; // Global for media button

    // ✅ PRO FEEDBACK
    function speak(text) {

    // ✅ PRO FEEDBACK
    function speak(text) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); 
            const msg = new SpeechSynthesisUtterance(text);
            msg.rate = 1.1;
            window.speechSynthesis.speak(msg);
        }
    }

    function hapticFeedback() {
        if ('vibrate' in navigator) navigator.vibrate(200);
    }

    // ✅ LOCAL-FIRST TIMER STATE
    let startTime = localStorage.getItem(`startTime_${planId}`) ? parseInt(localStorage.getItem(`startTime_${planId}`)) : null;
    let pausedAt = localStorage.getItem(`pausedAt_${planId}`) ? parseInt(localStorage.getItem(`pausedAt_${planId}`)) : null;
    let totalPaused = parseInt(localStorage.getItem(`totalPaused_${planId}`)) || 0;
    let isPausedLocal = localStorage.getItem(`isPaused_${planId}`) === "true";
    let lastSessionId = localStorage.getItem(`sessionId_${planId}`);

    // ✅ TIMER ENGINE
    function startTimer() {
        if (!startTime) return;
        clearInterval(timerInterval);
        document.getElementById('pauseBtn').classList.remove('hidden');

        if (isPausedLocal) {
            updateUIFrozen();
            updatePauseUI(true);
        } else {
            updatePauseUI(false);
            runActiveInterval();
        }
        if (endBtn) endBtn.classList.remove('hidden');
    }

    function runActiveInterval() {
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            const elapsed = Math.floor((Date.now() - startTime - totalPaused) / 1000);
            updateTimerUI(elapsed < 0 ? 0 : elapsed);
        }, 1000);
    }

    function updateUIFrozen() {
        const effectiveNow = pausedAt || Date.now();
        const elapsed = Math.floor((effectiveNow - startTime - totalPaused) / 1000);
        updateTimerUI(elapsed < 0 ? 0 : elapsed);
    }

    function updatePauseUI(paused) {
        const pIcon = document.getElementById('pauseIcon');
        const rIcon = document.getElementById('resumeIcon');
        if (paused) { pIcon.classList.add('hidden'); rIcon.classList.remove('hidden'); }
        else { pIcon.classList.remove('hidden'); rIcon.classList.add('hidden'); }
    }

    async function togglePause() {
        if (!sessionId) return;
        if (!isPausedLocal) {
            clearInterval(timerInterval);
            pausedAt = Date.now();
            isPausedLocal = true;
            localStorage.setItem(`pausedAt_${planId}`, pausedAt);
            localStorage.setItem(`isPaused_${planId}`, "true");
            updateUIFrozen();
            updatePauseUI(true);
            fetch(`/api/session/pause/${sessionId}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': token } });
            showToast("Paused ⏸️");
            speak("Session paused");
        } else {
            const pauseDuration = Date.now() - (pausedAt || Date.now());
            totalPaused += pauseDuration;
            isPausedLocal = false;
            pausedAt = null;
            localStorage.setItem(`totalPaused_${planId}`, totalPaused);
            localStorage.setItem(`isPaused_${planId}`, "false");
            localStorage.removeItem(`pausedAt_${planId}`);
            updatePauseUI(false);
            runActiveInterval();
            fetch(`/api/session/resume/${sessionId}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': token } });
            showToast("Resumed ▶️");
            speak("Resuming");
        }
    }

    document.getElementById('pauseBtn').addEventListener('click', togglePause);

    function updateTimerUI(seconds) {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;
        document.getElementById('sessionTimer').innerText = `${h > 0 ? h + ':' : ''}${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }

    function startRestTimer() {
        if (isResting) return;
        isResting = true;
        let timeLeft = restSeconds;
        restBox.classList.remove('hidden');
        restBox.classList.add('animate-bounce-in'); // New animation class
        updateRestRing(1);
        restTimerEl.innerText = timeLeft;
        speak("Rest started. Deep breaths.");
        
        clearInterval(restInterval);
        restInterval = setInterval(() => {
            timeLeft--;
            restTimerEl.innerText = timeLeft;
            
            // Smooth ring animation
            const offset = (timeLeft / restSeconds) * 276;
            document.getElementById('restRing').style.strokeDashoffset = 276 - offset;

            if (timeLeft <= 3 && timeLeft > 0) {
                speak(timeLeft.toString());
                hapticFeedback(); // Triple pulse for final seconds
            }

            if (timeLeft <= 0) {
                clearInterval(restInterval);
                isResting = false;
                hapticFeedback(); 
                hapticFeedback();
                restBox.classList.add('hidden');
                speak("Rest time over! Go! Go! Go!");
                showToast("GET BACK TO WORK! 🚀", 'success');
                nextSetOrExercise();
            }
        }, 1000);
    }

    function updateRestRing(percent) {
        const ring = document.getElementById('restRing');
        if (ring) ring.style.strokeDashoffset = 276 - (percent * 276);
    }

    skipRestBtn.addEventListener('click', () => {
        clearInterval(restInterval);
        isResting = false;
        restBox.classList.add('hidden');
        speak("Go!");
        nextSetOrExercise();
    });

    function stopTimer() {
        clearInterval(timerInterval);
        localStorage.removeItem(`startTime_${planId}`);
        localStorage.removeItem(`pausedAt_${planId}`);
        localStorage.removeItem(`totalPaused_${planId}`);
        localStorage.removeItem(`isPaused_${planId}`);
        localStorage.removeItem(`sessionId_${planId}`);
        localStorage.removeItem(`curEx_${planId}`);
        localStorage.removeItem(`curSet_${planId}`);
        document.getElementById('pauseBtn').classList.add('hidden');
    }

    // ✅ WORKOUT LOGIC
    async function startSession() {
        const res = await fetch(`/api/plan/start/${planId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' }
        });
        const data = await res.json();
        
        // Reset Local State for New Session
        startTime = Date.now();
        totalPaused = 0;
        isPausedLocal = false;
        currentExerciseIndex = 0;
        currentSet = 1;

        localStorage.setItem(`startTime_${planId}`, startTime);
        localStorage.setItem(`totalPaused_${planId}`, 0);
        localStorage.setItem(`isPaused_${planId}`, "false");
        localStorage.setItem(`curEx_${planId}`, 0);
        localStorage.setItem(`curSet_${planId}`, 1);

        showToast('Session Started! 🏋️', 'success');
        speak("Let's go. First exercise is " + exercises[0].dataset.name);
        loadProgress();
    }

    async function loadProgress() {
        try {
            const res = await fetch(`/api/progress/${planId}`);
            const data = await res.json();
            sessionId = data.session_id;

            // 🔑 SESSION VALIDATION: If server session ID changed, wipe local stale data
            if (sessionId && lastSessionId != sessionId) {
                console.log("New Session Detected. Syncing state...");
                stopTimer(); // Clear old plan state
                startTime = data.started_at;
                totalPaused = (data.total_paused || 0) * 1000;
                isPausedLocal = data.is_paused;
                pausedAt = data.paused_at;
                
                localStorage.setItem(`sessionId_${planId}`, sessionId);
                localStorage.setItem(`startTime_${planId}`, startTime);
                localStorage.setItem(`totalPaused_${planId}`, totalPaused);
                localStorage.setItem(`isPaused_${planId}`, isPausedLocal);
            }

            if (sessionId) {
                document.getElementById('btnText').innerText = "⚡ Session Active";
                startBtn.classList.add('opacity-80');
                
                // POPULATE HISTORICAL STATS
                if (data.historical_stats) {
                    for (const [exId, stats] of Object.entries(data.historical_stats)) {
                        const historyEl = document.getElementById(`history-${exId}`);
                        const valEl = document.getElementById(`history-val-${exId}`);
                        if (historyEl && valEl) {
                            historyEl.classList.replace('hidden', 'flex');
                            valEl.innerText = `${stats.weight}kg × ${stats.reps}`;
                        }
                    }
                }

                startTimer();
                updateLocalProgress();
                syncExerciseState(data.completed_exercises);
                loadExerciseUI();
            }
        } catch (err) { console.error("Load Error:", err); }
    }

    function syncExerciseState(completedIds) {
        if (!completedIds) return;
        completedIds.forEach(id => {
            let item = exercises.find(ex => ex.dataset.id == id);
            if(item) item.classList.add('completed');
        });
        
        // Only trust localStorage if it's the SAME session
        if (lastSessionId != sessionId) {
            const nextIdx = exercises.findIndex(ex => !ex.classList.contains('completed'));
            currentExerciseIndex = nextIdx === -1 ? exercises.length : nextIdx;
            currentSet = 1;
            saveInternalState();
        }
    }

    function saveInternalState() {
        localStorage.setItem(`curEx_${planId}`, currentExerciseIndex);
        localStorage.setItem(`curSet_${planId}`, currentSet);
    }

    function loadExerciseUI() {
        if (currentExerciseIndex >= exercises.length) { 
            finalizeSession(); 
            document.getElementById('mobileActiveBar').classList.add('hidden');
            return; 
        }
        
        exercises.forEach(ex => {
            ex.classList.remove('active-exercise');
            const mesh = ex.querySelector('.active-bg-mesh');
            if(mesh) mesh.remove();
        });

        const activeEx = exercises[currentExerciseIndex];
        window.currentExerciseId = activeEx.dataset.id;
        activeEx.classList.add('active-exercise');
        
        // Add dynamic mesh for energy
        const mesh = document.createElement('div');
        mesh.className = 'active-bg-mesh';
        activeEx.prepend(mesh);

        activeEx.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        document.getElementById('activeExerciseName').innerText = activeEx.dataset.name;
        document.getElementById('activeExerciseSub').innerText = `Set ${currentSet} of ${activeEx.dataset.sets}`;
        
        // 📱 MOBILE SYNC
        const mobileBar = document.getElementById('mobileActiveBar');
        mobileBar.classList.remove('hidden');
        document.getElementById('mobileActiveName').innerText = activeEx.dataset.name;
        document.getElementById('mobileActiveStats').innerText = `Set ${currentSet} of ${activeEx.dataset.sets}`;

        // ⏭️ NEXT UP SYNC
        const nextIdx = currentExerciseIndex + 1;
        const nextUpCard = document.getElementById('nextUpCard');
        if (nextIdx < exercises.length) {
            const nextEx = exercises[nextIdx];
            nextUpCard.classList.remove('hidden');
            document.getElementById('nextUpIndex').innerText = nextIdx + 1;
            document.getElementById('nextUpName').innerText = nextEx.dataset.name;
            document.getElementById('nextUpStats').innerText = `${nextEx.dataset.sets} Sets × ${nextEx.dataset.reps} Reps`;
        } else {
            nextUpCard.classList.add('hidden');
        }

        const trigger = activeEx.querySelector('.complete-trigger');
        if (trigger) {
            trigger.innerHTML = `<span class="text-xs font-black text-brand">${currentSet}/${activeEx.dataset.sets}</span>`;
            trigger.classList.add('ring-2', 'ring-brand/30');
        }
    }

    function spawnFeedback(text, element) {
        const el = document.createElement('div');
        el.className = 'fixed z-[300] text-brand font-black text-xl pointer-events-none floating-feedback';
        el.innerText = text;
        const rect = element.getBoundingClientRect();
        el.style.left = `${rect.left + rect.width/2}px`;
        el.style.top = `${rect.top}px`;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 1500);
    }

    // 🎞️ Toggle Media Drawer
    window.toggleMedia = function(id) {
        const drawer = document.getElementById(`media-${id}`);
        drawer.classList.toggle('hidden');
    }

    document.getElementById('mobileCompleteBtn').addEventListener('click', () => {
        const activeEx = exercises[currentExerciseIndex];
        const trigger = activeEx.querySelector('.complete-trigger');
        if (trigger) trigger.click();
    });

    document.querySelectorAll('.complete-trigger').forEach((btn, idx) => {
        btn.addEventListener('click', async function() {
            if (!sessionId || idx !== currentExerciseIndex || isResting) return;
            
            hapticFeedback();
            const item = exercises[idx];
            const maxSets = parseInt(item.dataset.sets);
            
            if (currentSet < maxSets) {
                spawnFeedback("SET COMPLETE 💪", btn);
                currentSet++;
                saveInternalState();
                startRestTimer();
                loadExerciseUI();
            } else {
                spawnFeedback("EXERCISE SMASHED 🔥", btn);
                const res = await fetch("/api/exercise/complete", {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": token, "Content-Type": "application/json" },
                    body: JSON.stringify({ 
                        session_id: sessionId, exercise_id: item.dataset.id,
                        sets: item.querySelector('.log-sets').value,
                        reps: item.querySelector('.log-reps').value,
                        weight: item.querySelector('.log-weight').value,
                        rpe: item.querySelector('.log-rpe').value
                    })
                });
                const data = await res.json();
                if (res.ok) {
                    item.classList.add('completed');
                    
                    // 🧠 INTELLIGENCE: SHOW COACHING TIP
                    if (data.coaching_tip) {
                        showToast(data.coaching_tip, 'info');
                        speak(data.coaching_tip.split('.')[0]); // Speak the first sentence
                    } else {
                        showToast("Exercise Complete! 🔥", 'success');
                    }

                    currentExerciseIndex++;
                    currentSet = 1;
                    saveInternalState();
                    if (currentExerciseIndex < exercises.length) {
                        speak("Incredible work. Next is " + exercises[currentExerciseIndex].dataset.name);
                        startRestTimer();
                    } else { finalizeSession(); }
                    updateLocalProgress();
                }
            }
        });
    });

    function nextSetOrExercise() { 
        loadExerciseUI(); 
        if (currentExerciseIndex < exercises.length) {
            speak("Set " + currentSet + ". Let's crush it.");
        }
    }

    function updateLocalProgress() {
        const done = document.querySelectorAll('.exercise-item.completed').length;
        const total = exercises.length;
        const percent = Math.round((done/total)*100);
        document.getElementById("progressBar").style.width = percent + "%";
        document.getElementById("progressText").innerText = percent + "%";
        document.getElementById("countText").innerText = `${done} / ${total}`;
    }

    async function finalizeSession() {
        const res = await fetch(`/api/session/complete/${sessionId}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': token } });
        const data = await res.json();
        stopTimer();
        speak("Workout complete. Extraordinary effort.");
        
        // Populate Enhanced Summary Screen
        document.getElementById('summaryTime').innerText = document.getElementById('sessionTimer').innerText;
        document.getElementById('summaryCalories').innerText = (data.calories || 0) + " KCAL";
        document.getElementById('summaryExercises').innerText = (data.exercises_completed || 0) + " / " + exercises.length;
        document.getElementById('summaryXP').innerText = `+${data.xp_gained || 50} XP`;
        document.getElementById('summaryStreak').innerText = (data.streak || 0) + " Days";
        
        if (data.leveled_up) {
            showToast(`LEVEL UP! You are now Level ${data.new_level}! 🎊`, 'achievement');
            speak(`Level up! You are now level ${data.new_level}`);
        }

        summaryScreen.classList.remove('hidden');
        confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
    }

    // Redirect to global showToast if possible, else use local
    function showToast(message, type = 'info') {
        if (window.showToast) {
            window.showToast(message, type);
        } else {
            let toast = document.createElement("div");
            toast.innerText = message;
            toast.className = "fixed bottom-10 right-10 z-[100] bg-gray-900 border border-brand/30 text-white px-8 py-4 rounded-2xl shadow-2xl font-black text-[10px] tracking-widest uppercase";
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2500);
        }
    }

    startBtn.addEventListener('click', startSession);
    loadProgress();
});
</script>
@endsection
@endsection
