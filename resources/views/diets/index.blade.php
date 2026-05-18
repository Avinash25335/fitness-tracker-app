@extends('layouts.dashboard')

@section('page-title', 'Nutrition Hub')
@section('page-subtitle', 'Autonomous Diet Engineering')

@section('content')
<div class="space-y-12 fade-up">

    <!-- 🧠 1. INPUT HUB -->
    <div class="bg-gray-800 border border-brand/20 rounded-[2.5rem] p-10 shadow-2xl shadow-brand/15 relative overflow-hidden bg-adaptive border-adaptive">
        <div class="relative z-10 space-y-8">
            <h2 class="text-3xl font-black text-main-area tracking-tighter">Diet Generation Engine</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-6">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-2">Weight (kg)</label>
                    <input type="number" id="input_weight" value="{{ $profile->weight ?? 70 }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-2">Height (cm)</label>
                    <input type="number" id="input_height" value="{{ $profile->height ?? 175 }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-2">Age</label>
                    <input type="number" id="input_age" value="{{ $profile->age ?? 25 }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-2">Gender</label>
                    <select id="input_gender" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all appearance-none cursor-pointer">
                        <option value="male" {{ ($profile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ ($profile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-2">Target Goal</label>
                    <select id="input_goal" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all appearance-none cursor-pointer">
                        <option value="cut" {{ ($profile->goal ?? '') == 'cut' ? 'selected' : '' }}>Cut (Fat Loss)</option>
                        <option value="bulk" {{ ($profile->goal ?? '') == 'bulk' ? 'selected' : '' }}>Bulk (Muscle Gain)</option>
                        <option value="maintain" {{ ($profile->goal ?? '') == 'maintain' ? 'selected' : '' }}>Maintain</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-2">Activity Level</label>
                    <select id="input_activity" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all appearance-none cursor-pointer">
                        <option value="sedentary" {{ ($profile->activity_level ?? '') == 'sedentary' ? 'selected' : '' }}>Sedentary</option>
                        <option value="light" {{ ($profile->activity_level ?? '') == 'light' ? 'selected' : '' }}>Lightly Active</option>
                        <option value="moderate" {{ ($profile->activity_level ?? '') == 'moderate' ? 'selected' : '' }}>Moderately Active</option>
                        <option value="active" {{ ($profile->activity_level ?? '') == 'active' ? 'selected' : '' }}>Very Active</option>
                        <option value="extra_active" {{ ($profile->activity_level ?? '') == 'extra_active' ? 'selected' : '' }}>Extra Active</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-between gap-6 pt-4">
                 <div class="flex items-center gap-4">
                    <button onclick="simulateTest()" class="text-[8px] font-black text-gray-600 uppercase tracking-widest hover:text-brand transition-colors">
                        🧪 Run UI Render Test
                    </button>
                    <a href="{{ route('export.diet') }}" target="_blank" class="text-[8px] font-black text-brand uppercase tracking-widest hover:opacity-80 transition-all flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download Current Strategy (PDF)
                    </a>
                 </div>
                <button onclick="generateDiet()" id="genBtn" class="w-64 bg-brand text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-brand-dark shadow-lg shadow-brand/20 transition-all active:scale-95">
                    GENERATE ELITE PLAN
                </button>
            </div>
        </div>
    </div>

    <!-- 📊 2. TARGET IDS (CRITICAL) -->
    <div id="results_container" class="hidden space-y-10 animate-fade-in">
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
            <!-- New Bio-Metrics Card -->
            <div class="bg-gray-800 border border-gray-700 rounded-[2.5rem] p-10 flex flex-col justify-center space-y-6 bg-adaptive border-adaptive shadow-xl">
                <div>
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Your BMI</p>
                    <p id="bmi_val" class="text-3xl font-black text-main-area">0.0</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Basal Metabolic Rate (BMR)</p>
                    <p id="bmr_val" class="text-3xl font-black text-main-area">0 kcal</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Total Expenditure (TDEE)</p>
                    <p id="tdee_val" class="text-3xl font-black text-brand">0 kcal</p>
                </div>
            </div>

            <div class="xl:col-span-2 bg-gray-800 border border-gray-700 rounded-[2.5rem] p-10 bg-adaptive border-adaptive shadow-xl">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-xl font-black text-main-area tracking-tight">Daily Target Calories</h3>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Goal Adjusted Intake</p>
                    </div>
                    <p id="calories" class="text-6xl font-black text-brand tracking-tighter">0 kcal</p>
                </div>
                <div class="grid grid-cols-3 gap-6">
                    <div class="bg-white/5 p-6 rounded-3xl text-center bg-adaptive border-adaptive">
                        <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1">Protein</p>
                        <p id="protein" class="text-2xl font-black text-main-area">0g</p>
                    </div>
                    <div class="bg-white/5 p-6 rounded-3xl text-center bg-adaptive border-adaptive">
                        <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1">Carbs</p>
                        <p id="carbs" class="text-2xl font-black text-main-area">0g</p>
                    </div>
                    <div class="bg-white/5 p-6 rounded-3xl text-center bg-adaptive border-adaptive">
                        <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1">Fats</p>
                        <p id="fat" class="text-2xl font-black text-main-area">0g</p>
                    </div>
                </div>
            </div>
            <div class="bg-brand/10 border border-brand/20 rounded-[2.5rem] p-10 flex flex-col items-center justify-center text-center space-y-4 bg-adaptive border-adaptive shadow-xl">
                <div class="w-16 h-16 bg-brand/20 rounded-full flex items-center justify-center text-brand text-2xl">⚡</div>
                <p id="strategyText" class="text-sm font-black text-main-area leading-relaxed italic">"Dynamic synthesis active..."</p>
            </div>
        </div>

        <!-- 🍽️ MEALS CONTAINER -->
        <div id="meals" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <!-- Meal cards will be injected here -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
console.log("🚀 Nutrition Hub Script Initialized");

async function generateDiet() {
    const genBtn = document.getElementById('genBtn');
    const weight = document.getElementById('input_weight').value;
    const height = document.getElementById('input_height').value;
    const age = document.getElementById('input_age').value;
    const gender = document.getElementById('input_gender').value;
    const goal = document.getElementById('input_goal').value;
    const activity_level = document.getElementById('input_activity').value;

    genBtn.innerText = 'CALCULATING...';
    genBtn.disabled = true;

    try {
        const response = await fetch('/api/generate-diet', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ weight, height, age, gender, goal, activity_level })
        });

        const data = await response.json();
        updateUI(data);

    } catch (error) {
        console.error("❌ API Call Failed:", error);
    } finally {
        genBtn.innerText = 'GENERATE ELITE PLAN';
        genBtn.disabled = false;
    }
}

function updateUI(data) {
    const container = document.getElementById('results_container');
    container.classList.remove('hidden');

    document.getElementById('bmi_val').innerText = data.bmi;
    document.getElementById('bmr_val').innerText = `${data.bmr} kcal`;
    document.getElementById('tdee_val').innerText = `${data.tdee} kcal`;
    
    document.getElementById('calories').innerText = `${data.calories} kcal`;
    document.getElementById('protein').innerText = `${data.macros.protein}g`;
    document.getElementById('carbs').innerText = `${data.macros.carbs}g`;
    document.getElementById('fat').innerText = `${data.macros.fat}g`;
    
    document.getElementById('strategyText').innerText = `Engineered for your ${data.activity_level} lifestyle. BMR at ${data.bmr} kcal with a ${data.calories} kcal target.`;

    renderMeals(data.meals);
    container.scrollIntoView({ behavior: 'smooth' });
}

function renderMeals(meals) {
    const container = document.getElementById('meals');
    container.innerHTML = "";

    const icons = {'Breakfast':'🍳','Morning Snack':'🥜','Lunch':'🥗','Afternoon Snack':'🥤','Dinner':'🍗'};

    Object.keys(meals).forEach((type) => {
        const items = meals[type];
        const card = `
            <div class="bg-adaptive border-adaptive p-8 rounded-[2rem] hover:border-brand/40 transition-all shadow-xl">
                <div class="text-3xl mb-4">${icons[type] || '🍽️'}</div>
                <h4 class="text-[10px] font-black text-brand uppercase tracking-widest mb-3">${type}</h4>
                <ul class="space-y-1">
                    ${items.map(i => `<li class="text-xs text-main-area font-bold tracking-tight">${i}</li>`).join('')}
                </ul>
            </div>
        `;
        container.innerHTML += card;
    });
}

function simulateTest() {
    const mockData = {
        bmi: "22.5",
        bmr: "1850",
        tdee: "2400",
        calories: 2500,
        activity_level: "Moderately Active",
        macros: { protein: 180, carbs: 250, fat: 70 },
        meals: {
            'Breakfast': ['Oats (100g)', '3 Eggs'],
            'Lunch': ['Chicken (200g)', 'Rice (150g)'],
            'Dinner': ['Fish (200g)', 'Salad']
        }
    };
    updateUI(mockData);
}
</script>
@endsection
