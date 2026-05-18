<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | FitCore Elite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-mesh {
            background: radial-gradient(at 0% 0%, rgba(34, 197, 94, 0.15) 0, transparent 50%),
                        radial-gradient(at 100% 100%, rgba(34, 197, 94, 0.1) 0, transparent 50%),
                        #0a0f1a;
        }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-gray-900/50 backdrop-blur-2xl border border-white/10 rounded-[3rem] p-12 text-center shadow-2xl">
        <div class="w-20 h-20 bg-brand/10 border-2 border-brand/20 rounded-3xl flex items-center justify-center mx-auto mb-8 text-brand">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>

        <h1 class="text-3xl font-black text-white tracking-tighter mb-4">Verify Your Email</h1>
        <p class="text-sm text-gray-400 font-medium leading-relaxed mb-10">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-8 p-4 bg-brand/10 border border-brand/20 rounded-2xl text-xs font-bold text-brand uppercase tracking-widest">
                A new verification link has been sent!
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-brand text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-brand-dark transition-all shadow-xl shadow-brand/20 active:scale-95">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-[10px] font-black text-gray-500 uppercase tracking-widest hover:text-white transition-colors">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</body>
</html>
