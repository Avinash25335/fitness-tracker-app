@extends('layouts.dashboard')

@section('page-title', 'Elite Marketplace')
@section('page-subtitle', 'Schedule private sessions with top experts')

@section('content')
<div class="space-y-12 fade-up">
    
    <!-- My Bookings Dynamic Section -->
    <div class="bg-gray-800 border border-gray-700 rounded-[2rem] lg:rounded-[2.5rem] p-6 lg:p-10 relative overflow-hidden group bg-adaptive border-adaptive shadow-xl">
        <div class="absolute right-0 top-0 p-12 opacity-5 group-hover:opacity-10 transition-opacity hidden lg:block">
            <svg class="w-48 h-48 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row items-start lg:items-center justify-between gap-6 lg:gap-8 mb-10">
            <div>
                <h2 class="text-2xl lg:text-3xl font-black text-main-area tracking-tighter">My Scheduled Sessions</h2>
                <div class="flex gap-4 mt-3">
                    <button onclick="switchBookingTab('upcoming')" id="tab_upcoming" class="text-[10px] font-black uppercase tracking-widest text-brand border-b-2 border-brand pb-1 transition-all">Upcoming</button>
                    <button onclick="switchBookingTab('history')" id="tab_history" class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition-all">History</button>
                </div>
            </div>
            <div id="bookingStats" class="flex items-center gap-4">
                 <!-- Stats injected via JS -->
            </div>
        </div>

        <!-- Bookings List Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" id="bookingList">
            <!-- Bookings injected via JS -->
        </div>
    </div>

    <!-- Trainers Grid -->
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-black text-main-area tracking-tight">Available Trainers</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($trainers as $trainer)
            <div class="flex flex-col bg-gray-800 border border-gray-700 rounded-[2.5rem] overflow-hidden group hover:border-brand/40 transition-all duration-500 relative hover:scale-[1.02] hover:shadow-[0_20px_50px_rgba(34,197,94,0.1)] bg-adaptive border-adaptive">
                @if(($trainer->rating ?? 4.8) >= 4.8)
                    <div class="absolute top-6 right-6 z-20">
                        <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-black text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">
                            ⭐ Top Rated
                        </span>
                    </div>
                @endif

                <div class="h-32 bg-gradient-to-br from-brand to-brand-dark opacity-10 group-hover:opacity-20 transition-opacity"></div>
                
                <div class="px-8 pb-8 -mt-16 flex flex-col h-full relative z-10">
                    <div class="flex items-end justify-between mb-6">
                        <div class="w-24 h-24 rounded-[2rem] border-4 border-gray-800 bg-gray-700 overflow-hidden shadow-2xl group-hover:scale-105 transition-transform duration-500">
                            @if($trainer->image)
                                <img src="{{ Str::startsWith($trainer->image, ['http://', 'https://']) ? $trainer->image : asset('storage/' . $trainer->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white font-black text-3xl bg-brand/20">
                                    {{ substr($trainer->user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="flex items-center gap-1.5 text-orange-400 mb-1">
                                <span class="text-xs font-black">{{ $trainer->rating ?? 4.8 }}</span>
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <p class="text-xs font-black text-main-area">${{ number_format($trainer->hourly_rate, 0) }}<span class="text-[9px] text-gray-500 uppercase tracking-tighter">/Session</span></p>
                        </div>
                    </div>

                    <h4 class="text-2xl font-black text-main-area tracking-tight group-hover:text-brand transition-colors">{{ $trainer->user->name }}</h4>
                    <p class="text-[10px] font-black text-brand uppercase tracking-widest mt-1">{{ $trainer->specialization ?? 'Elite Coach' }}</p>
                    
                    <p class="text-sm text-gray-500 mt-4 line-clamp-2 leading-relaxed">
                        {{ $trainer->bio ?? 'Passionate about helping you reach your peak performance through science-backed training.' }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-white/5 border-adaptive">
                        <div>
                            <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1">Experience</p>
                            <p class="text-xs font-black text-main-area">{{ $trainer->experience ?? 5 }}+ Years</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1">Students</p>
                            <p class="text-xs font-black text-main-area">250+ Active</p>
                        </div>
                    </div>

                    <div class="mt-8 space-y-4">
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Pick Date</label>
                                <input type="date" id="date_{{ $trainer->id }}" value="{{ date('Y-m-d') }}"
                                       onchange="loadSlots({{ $trainer->id }})"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-[10px] font-black text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all">
                            </div>
                            <div class="flex-1">
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Select Slot</label>
                                <div class="relative">
                                    <select id="slot_{{ $trainer->id }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[10px] font-black text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all appearance-none cursor-pointer">
                                        <option value="" disabled selected>Pick Slot</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none opacity-40">
                                        <svg class="w-3 h-3 text-main-area" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button onclick="bookTrainer({{ $trainer->id }})" id="btn_{{ $trainer->id }}"
                                class="w-full bg-brand text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-green-600 shadow-lg shadow-brand/20 transition-all active:scale-95">
                            Book Session →
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="fixed inset-0 z-[600] hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
    <div class="bg-gray-900 border border-brand/20 rounded-[2.5rem] p-10 w-full max-w-md shadow-2xl animate-fade-in bg-adaptive border-adaptive">
        <h3 class="text-2xl font-black text-main-area tracking-tighter mb-6">Reschedule Session</h3>
        <div class="space-y-6">
            <div class="space-y-2">
                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-2">New Date</label>
                <input type="date" id="reschedule_date" onchange="loadRescheduleSlots()" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-2">New Time Slot</label>
                <select id="reschedule_slot" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-main-area bg-adaptive border-adaptive focus:border-brand outline-none transition-all appearance-none cursor-pointer">
                    <option value="" disabled selected>Pick Slot</option>
                </select>
            </div>
            <div class="flex gap-4 pt-4">
                <button onclick="closeRescheduleModal()" class="flex-1 bg-white/5 text-gray-500 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white/10 transition-all bg-adaptive">Cancel</button>
                <button onclick="submitReschedule()" id="rescheduleSubmitBtn" class="flex-1 bg-brand text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-green-600 shadow-lg shadow-brand/20 transition-all">Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentBookings = [];
let activeTab = 'upcoming';
let reschedulingId = null;
let reschedulingTrainerId = null;

document.addEventListener('DOMContentLoaded', () => {
    loadBookings();
    @foreach($trainers as $trainer)
        loadSlots({{ $trainer->id }});
    @endforeach
});

function switchBookingTab(tab) {
    activeTab = tab;
    document.getElementById('tab_upcoming').className = tab === 'upcoming' ? 'text-[10px] font-black uppercase tracking-widest text-brand border-b-2 border-brand pb-1 transition-all' : 'text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition-all';
    document.getElementById('tab_history').className = tab === 'history' ? 'text-[10px] font-black uppercase tracking-widest text-brand border-b-2 border-brand pb-1 transition-all' : 'text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition-all';
    renderBookings();
}

async function loadSlots(trainerId) {
    const date = document.getElementById(`date_${trainerId}`).value;
    const select = document.getElementById(`slot_${trainerId}`);
    if (!date) return;
    try {
        const standardSlots = ["09:00", "10:00", "11:00", "12:00", "14:00", "15:00", "16:00", "17:00"];
        const res = await fetch(`/booked-slots/${trainerId}/${date}`);
        const bookedSlots = await res.json();
        select.innerHTML = `<option value="" disabled selected>Pick Slot</option>`;
        standardSlots.forEach(slot => {
            const isBooked = bookedSlots.includes(slot);
            select.innerHTML += `<option value="${slot}" ${isBooked ? 'disabled' : ''} class="bg-adaptive ${isBooked ? 'text-gray-500' : 'text-main-area'}">${slot}${isBooked ? ' (Full ❌)' : ''}</option>`;
        });
    } catch (e) { console.error("Error loading slots", e); }
}

async function bookTrainer(trainerId) {
    const date = document.getElementById(`date_${trainerId}`).value;
    const time = document.getElementById(`slot_${trainerId}`).value;
    const btn = document.getElementById(`btn_${trainerId}`);
    if (!date || !time) { showToast("Pick date & time ❌"); return; }
    btn.innerText = "Booking..."; btn.disabled = true;
    try {
        const res = await fetch("/book-session", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ trainer_id: trainerId, date: date, time: time })
        });
        const data = await res.json();
        if (res.ok) {
            showToast("Booked! ✅"); loadBookings(); loadSlots(trainerId);
        } else { showToast(data.message || "Error ❌"); }
    } catch (e) { showToast("Failed ❌"); } finally { btn.innerText = "Book Session →"; btn.disabled = false; }
}

async function loadBookings() {
    const res = await fetch("/my-sessions");
    currentBookings = await res.json();
    renderBookings();
    updateStats();
}

function updateStats() {
    const stats = document.getElementById('bookingStats');
    const upcoming = currentBookings.filter(b => b.status === 'booked' && new Date(b.session_date) >= new Date().setHours(0,0,0,0)).length;
    stats.innerHTML = `
        <div class="text-center px-6 border-r border-white/5 border-adaptive"><p class="text-2xl font-black text-main-area">${upcoming}</p><p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Upcoming</p></div>
        <div class="text-center px-6"><p class="text-2xl font-black text-brand">${currentBookings.length}</p><p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Total</p></div>
    `;
}

function renderBookings() {
    const list = document.getElementById("bookingList");
    list.innerHTML = "";
    const today = new Date().setHours(0,0,0,0);
    const filtered = currentBookings.filter(b => {
        const isUpcoming = b.status === 'booked' && new Date(b.session_date) >= today;
        return activeTab === 'upcoming' ? isUpcoming : !isUpcoming;
    });

    if (filtered.length === 0) {
        list.innerHTML = '<p class="col-span-full text-center py-10 text-gray-500 font-black uppercase tracking-widest text-[10px]">No sessions found.</p>';
        return;
    }

    filtered.forEach(b => {
        const isCancelled = b.status === 'cancelled';
        const sessionDate = new Date(b.session_date);
        const isSoon = activeTab === 'upcoming' && (sessionDate - today) < (24 * 60 * 60 * 1000);
        
        list.innerHTML += `
            <div class="flex flex-col p-6 rounded-3xl bg-white/5 border ${isSoon ? 'border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.1)]' : 'border-white/5'} hover:border-brand/30 transition-all group bg-adaptive border-adaptive shadow-lg">
                ${isSoon ? '<div class="text-[8px] font-black text-orange-400 uppercase tracking-widest mb-3 flex items-center gap-1.5"><span class="w-1.5 h-1.5 bg-orange-400 rounded-full animate-pulse"></span> Urgent: Starts Soon</div>' : ''}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand/10 flex items-center justify-center text-brand font-black text-xs bg-adaptive">
                            ${b.trainer.user.name.charAt(0)}
                        </div>
                        <div>
                            <p class="text-sm font-black text-main-area group-hover:text-brand transition-colors">${b.trainer.user.name}</p>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">${new Date(b.session_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric'})} @ ${b.session_time}</p>
                        </div>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg ${b.status === 'booked' ? 'bg-brand/10 text-brand' : 'bg-red-500/10 text-red-500'}">
                        ${b.status}
                    </span>
                </div>
                ${activeTab === 'upcoming' && !isCancelled ? `
                    <div class="flex gap-3 pt-4 border-t border-white/5 border-adaptive">
                        <button onclick="openRescheduleModal(${b.id}, ${b.trainer_id})" class="flex-1 text-[8px] font-black text-gray-400 hover:text-brand uppercase tracking-widest transition-all duration-300">Reschedule</button>
                        <button onclick="cancelBooking(${b.id})" class="flex-1 text-[8px] font-black text-red-500/70 hover:text-red-500 uppercase tracking-widest transition-all duration-300 text-right">Cancel</button>
                    </div>
                ` : `
                    <div class="flex gap-3 pt-4 border-t border-white/5 border-adaptive">
                        <a href="/export/invoice/${b.id}" target="_blank" class="flex-1 text-[8px] font-black text-brand hover:text-brand-dark uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Invoice PDF
                        </a>
                    </div>
                `}
            </div>
        `;
    });
}

function openRescheduleModal(id, trainerId) {
    reschedulingId = id;
    reschedulingTrainerId = trainerId;
    document.getElementById('rescheduleModal').classList.remove('hidden');
}

function closeRescheduleModal() {
    document.getElementById('rescheduleModal').classList.add('hidden');
}

async function loadRescheduleSlots() {
    const date = document.getElementById('reschedule_date').value;
    const select = document.getElementById('reschedule_slot');
    if (!date) return;
    const standardSlots = ["09:00", "10:00", "11:00", "12:00", "14:00", "15:00", "16:00", "17:00"];
    const res = await fetch(`/booked-slots/${reschedulingTrainerId}/${date}`);
    const booked = await res.json();
    select.innerHTML = '<option value="" disabled selected>Pick Slot</option>';
    standardSlots.forEach(s => {
        const isBooked = booked.includes(s);
        select.innerHTML += `<option value="${s}" ${isBooked ? 'disabled' : ''} class="bg-adaptive ${isBooked ? 'text-gray-500' : 'text-main-area'}">${s}${isBooked ? ' (Full)' : ''}</option>`;
    });
}

async function submitReschedule() {
    const date = document.getElementById('reschedule_date').value;
    const time = document.getElementById('reschedule_slot').value;
    if (!date || !time) { showToast("Pick date & time ❌"); return; }
    const btn = document.getElementById('rescheduleSubmitBtn');
    btn.innerText = "Updating..."; btn.disabled = true;
    try {
        const res = await fetch(`/reschedule-session/${reschedulingId}`, {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ date: date, time: time })
        });
        if (res.ok) {
            showToast("Rescheduled! ✅"); closeRescheduleModal(); loadBookings();
        } else { showToast("Failed ❌"); }
    } catch (e) { showToast("Error ❌"); } finally { btn.innerText = "Confirm"; btn.disabled = false; }
}

async function cancelBooking(id) {
    if (!confirm("Cancel this session?")) return;
    try {
        const res = await fetch(`/cancel-session/${id}`, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
        });
        if (res.ok) { showToast("Cancelled ❌"); loadBookings(); }
    } catch (e) { showToast("Error ❌"); }
}

function showToast(msg) {
    const toast = document.createElement("div");
    toast.innerText = msg;
    toast.className = "fixed top-5 right-5 z-[700] bg-gray-900 border border-brand/30 text-white px-6 py-3 rounded-2xl shadow-2xl font-black uppercase text-[10px] tracking-widest animate-bounce";
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 500); }, 2000);
}
</script>
@endsection
