@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Branding -->
    <div class="hidden lg:flex lg:w-5/12 relative overflow-hidden bg-surface">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?q=80&w=1920&auto=format&fit=crop"
                 class="w-full h-full object-cover opacity-25" alt="Gym">
            <div class="absolute inset-0 bg-gradient-to-br from-brand/20 via-surface/70 to-surface"></div>
        </div>
        <div class="relative z-10 flex flex-col justify-center p-12 w-full">
            <a href="{{ route('home') }}" class="flex items-center gap-3 mb-12">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-2xl font-extrabold text-white">Fitness<span class="text-brand">Pro</span></span>
            </a>
            <h2 class="text-4xl font-extrabold text-white leading-tight mb-4">
                Start your<br><span class="text-brand">transformation</span><br>today.
            </h2>
            <p class="text-gray-400 leading-relaxed mb-8">Join thousands of members who track, improve, and crush their fitness goals every day.</p>
            <ul class="space-y-3">
                @foreach(['Personalized workout & diet plans', 'Smart AI recommendations', 'Progress tracking with charts', 'Expert trainer booking'] as $feature)
                <li class="flex items-center gap-3 text-sm text-gray-300">
                    <div class="w-5 h-5 rounded-full bg-brand/20 border border-brand/30 flex items-center justify-center shrink-0">
                        <svg class="w-3 h-3 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="w-full lg:w-7/12 flex items-center justify-center p-6 overflow-y-auto">
        <div class="w-full max-w-lg py-8 fade-up">
            <div class="lg:hidden mb-8 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-xl font-extrabold text-white">Fitness<span class="text-brand">Pro</span></span>
            </div>

            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-white mb-2">Create your account</h1>
                <p class="text-gray-500">It's free — no credit card required</p>
            </div>

            {{-- Server-side error summary --}}
            @if($errors->any())
            <div class="mb-6 flex items-start gap-3 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-5" id="registerForm" novalidate>
                @csrf

                {{-- Full Name --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" autofocus
                           placeholder="John Doe" maxlength="100"
                           class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm {{ $errors->has('name') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                    @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="field-error text-red-400 text-xs mt-1 hidden" id="name-error"></p>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           placeholder="you@example.com"
                           class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm {{ $errors->has('email') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="field-error text-red-400 text-xs mt-1 hidden" id="email-error"></p>
                </div>

                {{-- Account Type --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Account Type</label>
                    <input type="hidden" name="role" value="{{ old('role', 'user') }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <input id="role_user" type="radio" name="role" value="user" class="sr-only peer" {{ old('role', 'user') === 'user' ? 'checked' : '' }}>
                            <label for="role_user" class="block cursor-pointer rounded-2xl border border-border-col bg-surface-2 p-4 transition-all duration-200 hover:border-brand peer-checked:border-brand peer-checked:bg-surface">
                                <div class="text-sm font-bold text-white">User</div>
                                <p class="text-xs text-gray-400 mt-1">Access workouts, nutrition, and trainer bookings.</p>
                            </label>
                        </div>
                        <div>
                            <input id="role_trainer" type="radio" name="role" value="trainer" class="sr-only peer" {{ old('role') === 'trainer' ? 'checked' : '' }}>
                            <label for="role_trainer" class="block cursor-pointer rounded-2xl border border-border-col bg-surface-2 p-4 transition-all duration-200 hover:border-brand peer-checked:border-brand peer-checked:bg-surface">
                                <div class="text-sm font-bold text-white">Trainer</div>
                                <p class="text-xs text-gray-400 mt-1">Register as a trainer to appear on the trainer marketplace.</p>
                            </label>
                        </div>
                    </div>
                    <p class="text-red-400 text-xs mt-1 hidden" id="role-error"></p>
                    @error('role')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Age & Gender --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Age</label>
                        <input type="number" name="age" id="age" value="{{ old('age') }}"
                               placeholder="25" min="10" max="120"
                               class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm {{ $errors->has('age') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                        @error('age')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="field-error text-red-400 text-xs mt-1 hidden" id="age-error"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Gender</label>
                        <select name="gender" id="gender"
                                class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 text-sm {{ $errors->has('gender') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                            <option value="">Select</option>
                            <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other"  {{ old('gender') === 'other'  ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="field-error text-red-400 text-xs mt-1 hidden" id="gender-error"></p>
                    </div>
                </div>

                {{-- Height & Weight --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Height (cm)</label>
                        <input type="number" step="0.1" name="height" id="height" value="{{ old('height') }}"
                               placeholder="170" min="50" max="300"
                               class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm {{ $errors->has('height') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                        @error('height')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="field-error text-red-400 text-xs mt-1 hidden" id="height-error"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Weight (kg)</label>
                        <input type="number" step="0.1" name="weight" id="weight" value="{{ old('weight') }}"
                               placeholder="70" min="20" max="500"
                               class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm {{ $errors->has('weight') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                        @error('weight')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="field-error text-red-400 text-xs mt-1 hidden" id="weight-error"></p>
                    </div>
                </div>

                {{-- Fitness Goal (Users Only) --}}
                <div id="goal-section">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Fitness Goal</label>
                    <select name="goal" class="w-full bg-surface-2 border border-border-col text-white rounded-xl px-4 py-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30 transition-all duration-200 text-sm">
                        <option value="">Select a goal (optional)</option>
                        <option value="weight_loss" {{ old('goal') === 'weight_loss' ? 'selected' : '' }}>🔥 Lose Weight</option>
                        <option value="muscle_gain" {{ old('goal') === 'muscle_gain' ? 'selected' : '' }}>💪 Build Muscle</option>
                        <option value="maintenance" {{ old('goal') === 'maintenance' ? 'selected' : '' }}>⚖️ Maintain Fitness</option>
                    </select>
                    <p class="text-xs text-gray-600 mt-1.5">Personalises your workout &amp; diet recommendations</p>
                </div>

                {{-- Trainer Fields (Trainers Only) --}}
                <div id="trainer-section" class="hidden space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Specialization</label>
                        <input type="text" name="specialization" id="specialization" value="{{ old('specialization', 'General Fitness') }}"
                               placeholder="e.g., Strength Training, Yoga, HIIT"
                               class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm {{ $errors->has('specialization') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                        @error('specialization')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Experience (Years)</label>
                            <input type="number" name="experience" id="experience" value="{{ old('experience', 1) }}"
                                   placeholder="1" min="1" max="60"
                                   class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm {{ $errors->has('experience') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                            @error('experience')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Students</label>
                            <input type="number" name="students" id="students" value="{{ old('students', 0) }}"
                                   placeholder="0" min="0" max="999"
                                   class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm {{ $errors->has('students') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                            @error('students')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Cost per Session ($)</label>
                            <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', 50) }}"
                                   placeholder="50" min="10" max="500"
                                   class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm {{ $errors->has('hourly_rate') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                            @error('hourly_rate')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" id="password"
                               placeholder="Min. 8 characters" minlength="8"
                               class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm {{ $errors->has('password') ? 'border-red-500' : 'border-border-col focus:border-brand focus:ring-1 focus:ring-brand/30' }}">
                        @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="field-error text-red-400 text-xs mt-1 hidden" id="password-error"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Confirm</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               placeholder="Repeat password"
                               class="input-field w-full bg-surface-2 border text-white rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 placeholder-gray-600 text-sm">
                        <p class="field-error text-red-400 text-xs mt-1 hidden" id="confirm-error"></p>
                    </div>
                </div>

                <!-- Cloudflare Turnstile -->
                <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}"></div>

                <button type="submit" id="submitBtn"
                        class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-500/20 hover:shadow-green-500/30 hover:scale-[1.02] transition-all duration-200 text-sm">
                    Create My Free Account →
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-brand font-semibold hover:text-green-400 transition-colors">Sign in →</a>
            </p>
        </div>
    </div>
</div>

<script>
(function () {
    const rules = {
        name: {
            validate(v) {
                if (!v.trim()) return 'Full name is required.';
                if (v.trim().length < 2) return 'Name must be at least 2 characters.';
                if (v.trim().length > 100) return 'Name must not exceed 100 characters.';
                if (!/^[a-zA-Z\s]+$/.test(v.trim())) return 'Name can only contain letters and spaces.';
                return '';
            }
        },
        email: {
            validate(v) {
                if (!v.trim()) return 'Email address is required.';
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())) return 'Please enter a valid email address.';
                return '';
            }
        },
        age: {
            validate(v) {
                if (v === '') return 'Age is required.';
                const n = Number(v);
                if (!Number.isInteger(n)) return 'Age must be a whole number.';
                if (n < 10) return 'Age must be at least 10 years.';
                if (n > 120) return 'Age must be 120 years or less.';
                return '';
            }
        },
        role: {
            validate(v) {
                if (!v) return 'Please select an account type.';
                if (!['user', 'trainer'].includes(v)) return 'Please select either user or trainer.';
                return '';
            }
        },
        specialization: {
            validate(v) {
                const role = document.querySelector('[name="role"]:checked')?.value;
                if (role === 'trainer' && !v.trim()) return 'Specialization is required for trainers.';
                return '';
            }
        },
        experience: {
            validate(v) {
                const role = document.querySelector('[name="role"]:checked')?.value;
                if (role === 'trainer') {
                    if (v === '') return 'Experience is required for trainers.';
                    const n = Number(v);
                    if (n < 1 || n > 60) return 'Experience must be between 1 and 60 years.';
                }
                return '';
            }
        },
        students: {
            validate(v) {
                const role = document.querySelector('[name="role"]:checked')?.value;
                if (role === 'trainer') {
                    if (v === '') return 'Students count is required.';
                    const n = Number(v);
                    if (n < 0 || n > 999) return 'Students must be between 0 and 999.';
                }
                return '';
            }
        },
        hourly_rate: {
            validate(v) {
                const role = document.querySelector('[name="role"]:checked')?.value;
                if (role === 'trainer') {
                    if (v === '') return 'Cost per session is required.';
                    const n = parseFloat(v);
                    if (n < 10 || n > 500) return 'Cost must be between $10 and $500.';
                }
                return '';
            }
        },
        gender: {
            validate(v) {
                if (!v) return 'Please select your gender.';
                return '';
            }
        },
        height: {
            validate(v) {
                if (v === '') return 'Height is required.';
                const n = parseFloat(v);
                if (isNaN(n)) return 'Height must be a number.';
                if (n < 50) return 'Height must be at least 50 cm.';
                if (n > 300) return 'Height must be 300 cm or less.';
                return '';
            }
        },
        weight: {
            validate(v) {
                if (v === '') return 'Weight is required.';
                const n = parseFloat(v);
                if (isNaN(n)) return 'Weight must be a number.';
                if (n < 20) return 'Weight must be at least 20 kg.';
                if (n > 500) return 'Weight must be 500 kg or less.';
                return '';
            }
        },
        password: {
            validate(v) {
                if (!v) return 'Password is required.';
                if (v.length < 8) return 'Password must be at least 8 characters.';
                return '';
            }
        }
    };

    function showError(fieldId, msg) {
        const el = document.getElementById(fieldId + '-error');
        const input = document.getElementById(fieldId);
        if (!el) return;
        if (msg) {
            el.textContent = msg;
            el.classList.remove('hidden');
            if (input) {
                input.classList.remove('border-border-col', 'focus:border-brand', 'border-green-500/50');
                input.classList.add('border-red-500');
            }
        } else {
            el.textContent = '';
            el.classList.add('hidden');
            if (input) {
                input.classList.remove('border-red-500');
                input.classList.add('border-green-500/50');
            }
        }
    }

    // Toggle trainer/user specific fields
    function updateFormFields() {
        const role = document.querySelector('[name="role"]:checked')?.value || 'user';
        const goalSection = document.getElementById('goal-section');
        const trainerSection = document.getElementById('trainer-section');

        if (role === 'trainer') {
            goalSection.classList.add('hidden');
            trainerSection.classList.remove('hidden');
        } else {
            goalSection.classList.remove('hidden');
            trainerSection.classList.add('hidden');
        }
    }

    // Attach role change listeners
    document.querySelectorAll('[name="role"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateFormFields();
        });
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', updateFormFields);

    // Real-time validation on blur
    Object.keys(rules).forEach(function(fieldId) {
        const el = fieldId === 'role'
            ? document.querySelector('[name="role"]:checked')
            : document.getElementById(fieldId);
        if (!el) return;
        const target = fieldId === 'role' ? document.querySelectorAll('[name="role"]') : [el];
        target.forEach(function(item) {
            item.addEventListener('blur', function() {
                const value = fieldId === 'role' ? document.querySelector('[name="role"]:checked')?.value : el.value;
                const err = rules[fieldId].validate(value);
                showError(fieldId, err);
            });
            item.addEventListener('input', function() {
                const errEl = document.getElementById(fieldId + '-error');
                if (errEl && !errEl.classList.contains('hidden')) {
                    const value = fieldId === 'role' ? document.querySelector('[name="role"]:checked')?.value : el.value;
                    const err = rules[fieldId].validate(value);
                    showError(fieldId, err);
                }
            });
        });
    });

    // Password confirm real-time
    const confirmEl = document.getElementById('password_confirmation');
    if (confirmEl) {
        confirmEl.addEventListener('blur', function() {
            const pw = document.getElementById('password').value;
            showError('confirm', this.value !== pw ? 'Passwords do not match.' : '');
        });
    }

    // Full form validation on submit
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        let valid = true;
        Object.keys(rules).forEach(function(fieldId) {
            const value = fieldId === 'role' ? document.querySelector('[name="role"]:checked')?.value : document.getElementById(fieldId)?.value;
            const err = rules[fieldId].validate(value);
            showError(fieldId, err);
            if (err) valid = false;
        });

        // Check confirm password
        const pw = document.getElementById('password').value;
        const cf = document.getElementById('password_confirmation').value;
        if (cf !== pw) {
            showError('confirm', 'Passwords do not match.');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });
})();
</script>
@endsection
