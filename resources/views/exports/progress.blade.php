@extends('exports.layout')

@section('content')
<div class="space-y-12">
    <div class="text-center mb-10">
        <h2 class="text-2xl font-black uppercase tracking-widest border-b-2 border-black inline-block pb-1">Athlete Progress Summary</h2>
    </div>

    <div class="grid grid-cols-3 gap-8">
        <div class="border-2 border-black p-6 text-center">
            <p class="text-[10px] font-black uppercase text-gray-500 mb-1">Current Level</p>
            <p class="text-4xl font-black">Lvl {{ $stat->level ?? 1 }}</p>
        </div>
        <div class="border-2 border-black p-6 text-center">
            <p class="text-[10px] font-black uppercase text-gray-500 mb-1">Total Experience</p>
            <p class="text-4xl font-black">{{ number_format($stat->xp ?? 0) }} XP</p>
        </div>
        <div class="border-2 border-black p-6 text-center">
            <p class="text-[10px] font-black uppercase text-gray-500 mb-1">Workouts Completed</p>
            <p class="text-4xl font-black">{{ $stat->total_workouts ?? 0 }}</p>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-black uppercase mb-4 border-l-4 border-black pl-3">Achievements Unlocked</h3>
        <div class="grid grid-cols-2 gap-4">
            @foreach($achievements as $achievement)
                <div class="border border-black p-4 flex items-center gap-4">
                    <span class="text-2xl">🏆</span>
                    <div>
                        <p class="text-xs font-black uppercase">{{ $achievement->title }}</p>
                        <p class="text-[9px] text-gray-500 font-bold uppercase">{{ $achievement->pivot->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div>
        <h3 class="text-sm font-black uppercase mb-4 border-l-4 border-black pl-3">Recent Progress History</h3>
        <table class="w-full border-collapse border-2 border-black">
            <thead>
                <tr class="bg-black text-white">
                    <th class="p-3 text-[10px] uppercase text-left border border-white/20">Date</th>
                    <th class="p-3 text-[10px] uppercase text-left border border-white/20">Weight</th>
                    <th class="p-3 text-[10px] uppercase text-left border border-white/20">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td class="p-3 border border-black text-xs font-bold">{{ \Carbon\Carbon::parse($log->log_date)->format('M d, Y') }}</td>
                        <td class="p-3 border border-black text-xs">{{ $log->weight }} kg</td>
                        <td class="p-3 border border-black text-xs uppercase font-black text-brand">Verified Log</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
