<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitCore Enterprise Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: white; color: black; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
            .print-break { page-break-after: always; }
        }
        .enterprise-border { border: 2px solid black; }
    </style>
</head>
<body class="p-10" onload="window.print()">
    <div class="max-w-4xl mx-auto border-4 border-black p-8 shadow-2xl relative">
        {{-- Header --}}
        <div class="flex justify-between items-center border-b-4 border-black pb-6 mb-10">
            <div>
                <h1 class="text-4xl font-black tracking-tighter uppercase">FITCORE <span class="text-gray-400">PRO</span></h1>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Official Health & Performance Report</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold uppercase">Date: {{ date('F d, Y') }}</p>
                <p class="text-[10px] text-gray-400 uppercase">ID: {{ uniqid('FC-') }}</p>
            </div>
        </div>

        @yield('content')

        {{-- Footer --}}
        <div class="mt-20 pt-6 border-t-2 border-black/10 flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-gray-400">
            <p>&copy; {{ date('Y') }} FITCORE ENTERPRISE SUITE</p>
            <p>Generated for {{ auth()->user()->name }}</p>
        </div>

        {{-- Stamp --}}
        <div class="absolute bottom-10 right-10 opacity-10 rotate-12 border-4 border-black p-4 rounded-full font-black text-2xl uppercase">
            VERIFIED PRO
        </div>
    </div>

    <div class="fixed bottom-10 left-1/2 -translate-x-1/2 no-print">
        <button onclick="window.print()" class="bg-black text-white px-8 py-3 rounded-full font-black text-xs uppercase tracking-widest shadow-2xl hover:scale-105 transition-all">
            🖨️ Print / Save as PDF
        </button>
    </div>
</body>
</html>
