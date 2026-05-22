@extends('layouts.dashboard')

@section('page-title', 'Elite Profile')
@section('page-subtitle', 'Manage your biometric identity')

@section('content')
<div class="max-w-5xl mx-auto space-y-10 fade-up">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- 👤 1. USER CARD -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card p-8 text-center relative overflow-hidden group bg-adaptive border-adaptive shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-brand/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="relative inline-block mb-6">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-brand to-brand-dark p-1 shadow-2xl">
                        <div class="w-full h-full rounded-full bg-adaptive flex items-center justify-center text-4xl font-black text-main-area">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="absolute bottom-1 right-1 w-8 h-8 rounded-full bg-brand border-4 border-adaptive flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>

                <h3 class="text-2xl font-black text-main-area tracking-tight">{{ $user->name }}</h3>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">{{ $user->email }}</p>
                
                <div class="mt-8 pt-8 border-t border-adaptive grid grid-cols-2 gap-4">
                    <div class="bg-white/5 p-4 rounded-2xl bg-adaptive border border-adaptive shadow-sm">
                        <p class="text-[9px] font-black text-gray-500 uppercase mb-1">Status</p>
                        <p class="text-xs font-black text-brand uppercase">Verified</p>
                    </div>
                    <div class="bg-white/5 p-4 rounded-2xl bg-adaptive border border-adaptive shadow-sm">
                        <p class="text-[9px] font-black text-gray-500 uppercase mb-1">Role</p>
                        <p class="text-xs font-black text-main-area uppercase">{{ $user->role ?? 'Athlete' }}</p>
                    </div>
                </div>
            </div>

            <!-- 🧬 BMI QUICK STAT -->
            <div class="bg-brand/10 border border-brand/20 rounded-[2.5rem] p-8 space-y-4 bg-adaptive border-adaptive shadow-lg">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-black text-main-area uppercase tracking-widest">Body Index</h4>
                    <span class="text-xs font-black text-brand bg-brand/10 px-3 py-1 rounded-full">{{ $profile->bmi ?? 'N/A' }} BMI</span>
                </div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden bg-adaptive border border-adaptive">
                    @php 
                        $bmiWidth = min(max((($profile->bmi ?? 20) - 15) / 25 * 100, 0), 100);
                        $bmiColor = ($profile->bmi ?? 22) < 18.5 ? 'bg-blue-400' : (($profile->bmi ?? 22) < 25 ? 'bg-brand' : 'bg-orange-500');
                    @endphp
                    <div class="h-full {{ $bmiColor }} transition-all duration-1000 shadow-[0_0_10px_rgba(34,197,94,0.3)]" style="width: {{ $bmiWidth }}%"></div>
                </div>
                <p class="text-[10px] text-gray-500 font-medium leading-relaxed italic">
                    "Your BMI of {{ $profile->bmi ?? '?' }} indicates a {{ ($profile->bmi ?? 22) < 25 ? 'healthy' : 'performance' }} biological baseline."
                </p>
            </div>
        </div>

        <!-- ⚙️ 2. SETTINGS FORM -->
        <div class="lg:col-span-2">
            <form action="{{ route('profile.update') }}" method="POST" class="card p-10 space-y-10 shadow-2xl bg-adaptive border-adaptive">
                @csrf
                
                <div>
                    <h3 class="text-xl font-black text-main-area tracking-tight mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-brand/20 flex items-center justify-center text-brand text-xs">01</span>
                        Identity & Biometrics
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Full Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Current Age</label>
                            <input type="number" name="age" value="{{ $user->age ?? 25 }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Gender</label>
                            <select name="gender" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all appearance-none cursor-pointer">
                                <option value="male" {{ ($user->gender ?? 'male') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ ($user->gender ?? 'male') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Weight (kg)</label>
                                <input type="number" name="weight" value="{{ $profile->weight ?? 70 }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Height (cm)</label>
                                <input type="number" name="height" value="{{ $profile->height ?? 175 }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-black text-main-area tracking-tight mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400 text-xs">02</span>
                        Strategic Intent
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Primary Fitness Goal</label>
                            <select name="goal" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all appearance-none cursor-pointer">
                                <option value="weight_loss" {{ ($profile->goal ?? 'maintenance') == 'weight_loss' ? 'selected' : '' }}>Weight Loss (Cut)</option>
                                <option value="muscle_gain" {{ ($profile->goal ?? 'maintenance') == 'muscle_gain' ? 'selected' : '' }}>Muscle Gain (Bulk)</option>
                                <option value="maintenance" {{ ($profile->goal ?? 'maintenance') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Activity Coefficient</label>
                            <select name="activity_level" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all appearance-none cursor-pointer">
                                <option value="sedentary" {{ ($profile->activity_level ?? 'moderate') == 'sedentary' ? 'selected' : '' }}>Sedentary (Low)</option>
                                <option value="light" {{ ($profile->activity_level ?? 'moderate') == 'light' ? 'selected' : '' }}>Lightly Active</option>
                                <option value="moderate" {{ ($profile->activity_level ?? 'moderate') == 'moderate' ? 'selected' : '' }}>Moderately Active</option>
                                <option value="active" {{ ($profile->activity_level ?? 'moderate') == 'active' ? 'selected' : '' }}>Very Active</option>
                                <option value="extra_active" {{ ($profile->activity_level ?? 'moderate') == 'extra_active' ? 'selected' : '' }}>Extra Active (Pro)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-brand text-white font-black uppercase text-xs tracking-widest py-5 rounded-2xl shadow-xl shadow-brand/20 hover:scale-[1.02] active:scale-95 transition-all">
                        Update Professional Identity
                    </button>
                </div>
            </form>

            <!-- 🚨 DANGER ZONE -->
            <div class="mt-8 card p-10 space-y-6 shadow-2xl border-red-500/30 bg-red-500/5">
                <h3 class="text-xl font-black text-red-500 tracking-tight flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Danger Zone
                </h3>
                <p class="text-xs text-red-400 font-bold uppercase tracking-widest">Once you delete your account, there is no going back. All your data, progress, and settings will be permanently erased.</p>
                <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white font-black uppercase text-xs tracking-widest py-3 px-8 rounded-xl shadow-lg shadow-red-500/20 hover:bg-red-600 hover:scale-[1.02] active:scale-95 transition-all">
                        Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
