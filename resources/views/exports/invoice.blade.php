@extends('exports.layout')

@section('content')
<div class="space-y-12">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-black uppercase tracking-tighter border-b-2 border-black inline-block pb-1">Trainer Session Invoice</h2>
    </div>

    <div class="flex justify-between items-start">
        <div class="space-y-1">
            <p class="text-[10px] font-black uppercase text-gray-500">Bill To:</p>
            <p class="text-lg font-black uppercase">{{ $booking->user->name }}</p>
            <p class="text-xs font-bold text-gray-500">{{ $booking->user->email }}</p>
        </div>
        <div class="text-right space-y-1">
            <p class="text-[10px] font-black uppercase text-gray-500">Service Provider:</p>
            <p class="text-lg font-black uppercase">{{ $booking->trainer->user->name }}</p>
            <p class="text-xs font-bold text-brand uppercase">{{ $booking->trainer->specialization }}</p>
        </div>
    </div>

    <div class="bg-gray-50 border-2 border-black p-8">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-black pb-4">
                    <th class="text-left text-[10px] font-black uppercase pb-4">Description</th>
                    <th class="text-center text-[10px] font-black uppercase pb-4">Date</th>
                    <th class="text-right text-[10px] font-black uppercase pb-4">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr class="pt-6">
                    <td class="pt-6">
                        <p class="font-black text-sm uppercase">Personal Training Session</p>
                        <p class="text-[10px] text-gray-500 font-bold uppercase mt-1">Status: {{ strtoupper($booking->status) }}</p>
                    </td>
                    <td class="text-center pt-6">
                        <p class="text-xs font-bold">{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}</p>
                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mt-1">{{ $booking->slot }}</p>
                    </td>
                    <td class="text-right pt-6">
                        <p class="text-lg font-black">${{ number_format($booking->trainer->hourly_rate, 2) }}</p>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-black mt-10">
                    <td colspan="2" class="text-right pt-6 pr-10 text-[10px] font-black uppercase">Total Due:</td>
                    <td class="text-right pt-6">
                        <p class="text-2xl font-black text-brand">${{ number_format($booking->trainer->hourly_rate, 2) }}</p>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="border-l-4 border-black pl-6 italic text-xs text-gray-500 max-w-lg">
        Thank you for choosing FitCore Elite Marketplace. This document serves as an official receipt of service. Please present this to your trainer at the start of your session.
    </div>
</div>
@endsection
