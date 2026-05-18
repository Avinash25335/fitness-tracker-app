@extends('exports.layout')

@section('content')
<div class="space-y-12">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-black uppercase tracking-tighter border-b-2 border-black inline-block pb-1">Nutritional Strategy Report</h2>
    </div>

    <div class="grid grid-cols-2 gap-8">
        <div class="space-y-4">
            <h3 class="text-sm font-black uppercase border-l-4 border-black pl-3">Personal Metrics</h3>
            <div class="bg-gray-50 p-6 space-y-2 border border-black/10">
                <div class="flex justify-between text-xs"><span>Current Weight:</span><span class="font-bold">{{ $profile->weight }} kg</span></div>
                <div class="flex justify-between text-xs"><span>Current Height:</span><span class="font-bold">{{ $profile->height }} cm</span></div>
                <div class="flex justify-between text-xs"><span>Body Mass Index (BMI):</span><span class="font-bold">{{ $profile->bmi }}</span></div>
                <div class="flex justify-between text-xs"><span>Primary Goal:</span><span class="font-bold uppercase text-brand">{{ str_replace('_', ' ', $profile->goal) }}</span></div>
            </div>
        </div>
        <div class="space-y-4">
            <h3 class="text-sm font-black uppercase border-l-4 border-black pl-3">Caloric Intelligence</h3>
            <div class="bg-black text-white p-6 space-y-4">
                <div class="text-center">
                    <p class="text-[10px] uppercase font-bold opacity-70">Daily Target</p>
                    <p class="text-4xl font-black">{{ number_format($targetCals) }} <span class="text-xs">KCAL</span></p>
                </div>
                <div class="grid grid-cols-2 gap-2 text-[9px] uppercase font-bold text-center border-t border-white/20 pt-4">
                    <div>TDEE: {{ number_format($tdee) }}</div>
                    <div>STATUS: ACTIVE</div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-black uppercase mb-4 border-l-4 border-black pl-3">Macronutrient Breakdown</h3>
        <div class="grid grid-cols-3 gap-4">
            <div class="border-2 border-black p-4 text-center">
                <p class="text-2xl font-black">{{ round(($targetCals * 0.3) / 4) }}g</p>
                <p class="text-[10px] font-bold uppercase text-gray-500">Protein (30%)</p>
            </div>
            <div class="border-2 border-black p-4 text-center">
                <p class="text-2xl font-black">{{ round(($targetCals * 0.4) / 4) }}g</p>
                <p class="text-[10px] font-bold uppercase text-gray-500">Carbs (40%)</p>
            </div>
            <div class="border-2 border-black p-4 text-center">
                <p class="text-2xl font-black">{{ round(($targetCals * 0.3) / 9) }}g</p>
                <p class="text-[10px] font-bold uppercase text-gray-500">Fats (30%)</p>
            </div>
        </div>
    </div>

    @if($activeUserPlan && $activeUserPlan->plan)
    <div class="border-4 border-black p-8 bg-gray-50">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-black text-white flex items-center justify-center font-black text-xl">!</div>
            <div>
                <h3 class="font-black uppercase text-lg">Integrated Plan Action</h3>
                <p class="text-xs text-gray-500">This diet is optimized for your <strong>{{ $activeUserPlan->plan->title }}</strong> program.</p>
            </div>
        </div>
        <p class="text-xs leading-relaxed">
            To maximize results, ensure you are hitting your protein targets within 2 hours of your training sessions. Stay hydrated with at least 3L of water daily.
        </p>
    </div>
    @endif
</div>
@endsection
