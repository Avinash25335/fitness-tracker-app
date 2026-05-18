@extends('layouts.dashboard')

@section('page-title', $diet->title)
@section('page-subtitle', 'Full Nutrition Plan')

@section('content')
<div class="space-y-6 fade-up">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('diets.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Diet Plans
        </a>
        <a href="{{ route('diets.download', $diet) }}" class="border border-gray-600 text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-700 transition-all duration-300 text-sm flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download Plan
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main -->
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-6 hover:scale-105 hover:shadow-green-500/10 transition-all duration-300">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-orange-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-white mb-1">{{ $diet->title }}</h1>
                        <div class="badge-orange">{{ number_format($diet->daily_calories) }} kcal / day</div>
                    </div>
                </div>
                <p class="text-gray-400 leading-relaxed">{{ $diet->description }}</p>
            </div>

            <!-- Meal Breakdown -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-6 hover:scale-105 hover:shadow-green-500/10 transition-all duration-300">
                <h3 class="font-bold text-white text-base mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Daily Meal Plan
                </h3>
                @php
                    $mealsArray = is_string($diet->meals_json) ? json_decode($diet->meals_json, true) : (array)$diet->meals_json;
                    $meals = $mealsArray ?: [
                        'Breakfast' => 'Oatmeal & Eggs',
                        'Morning Snack' => 'Protein Shake',
                        'Lunch' => 'Chicken Breast & Brown Rice',
                        'Afternoon Snack' => 'Greek Yogurt',
                        'Dinner' => 'Salmon & Asparagus'
                    ];
                    $icons = [
                        'Breakfast'       => '🍳', 
                        'Morning Snack'   => '🥜', 
                        'Lunch'           => '🥗', 
                        'Afternoon Snack' => '🥤', 
                        'Dinner'          => '🍗'
                    ];
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($meals as $type => $food)
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg hover:shadow-green-500/20 hover:scale-105 transition-all duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-xl">
                                {{ $icons[$type] ?? '🍽️' }}
                            </div>
                            <h4 class="text-sm font-bold text-orange-400 uppercase tracking-wider">{{ $type }}</h4>
                        </div>
                        <ul class="text-sm text-gray-300 space-y-2">
                            @php
                                // Support multiple separators: comma, ampersand, or semi-colon
                                $items = preg_split('/[,&;]/', is_string($food) ? $food : '');
                            @endphp
                            @foreach($items as $item)
                                @if(trim($item))
                                    <li class="flex items-start gap-2">
                                        <span class="text-brand-orange mt-0.5">•</span>
                                        <span>{{ trim($item) }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-5">
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-6 hover:scale-105 hover:shadow-green-500/10 transition-all duration-300">
                <h3 class="font-bold text-white text-sm mb-5">Nutritional Summary</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs mb-1.5"><span class="text-gray-500">Daily Calories</span><span class="text-orange-400 font-bold">{{ number_format($diet->daily_calories) }} kcal</span></div>
                        <div class="h-1.5 bg-surface rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-brand-orange to-yellow-400 rounded-full" style="width: 80%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1.5"><span class="text-gray-500">Protein</span><span class="text-blue-400 font-bold">~150g</span></div>
                        <div class="h-1.5 bg-surface rounded-full overflow-hidden"><div class="h-full bg-blue-500 rounded-full" style="width: 60%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1.5"><span class="text-gray-500">Carbs</span><span class="text-green-400 font-bold">~200g</span></div>
                        <div class="h-1.5 bg-surface rounded-full overflow-hidden"><div class="h-full bg-green-500 rounded-full" style="width: 70%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1.5"><span class="text-gray-500">Fats</span><span class="text-yellow-400 font-bold">~60g</span></div>
                        <div class="h-1.5 bg-surface rounded-full overflow-hidden"><div class="h-full bg-yellow-500 rounded-full" style="width: 45%"></div></div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-6 hover:scale-105 hover:shadow-green-500/10 transition-all duration-300 border-l-4 border-l-brand">
                <p class="text-xs text-gray-500 mb-2 font-semibold uppercase tracking-wider">Disclaimer</p>
                <p class="text-xs text-gray-400 leading-relaxed">Consult a nutritionist or doctor before starting any new diet plan to ensure it meets your specific health requirements.</p>
            </div>
        </div>
    </div>
</div>
@endsection
