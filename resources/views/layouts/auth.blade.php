<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand': '#22c55e',
                        'brand-dark': '#16a34a',
                        'brand-orange': '#f97316',
                        'surface': '#111827',
                        'surface-2': '#1f2937',
                        'surface-3': '#374151',
                        'border-col': '#374151',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0a0f1a; color: #f9fafb; font-family: 'Inter', sans-serif; }
        .input-field { @apply w-full bg-surface border border-border-col text-white rounded-xl px-4 py-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/50 transition-all duration-200 placeholder-gray-600 text-sm; }
        .btn-primary { background: linear-gradient(135deg, #22c55e, #16a34a); }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
    </style>
</head>
<body class="min-h-screen antialiased">
    @yield('content')
</body>
</html>
