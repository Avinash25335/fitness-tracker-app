@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-4xl">
    <div class="glass p-8 md:p-12 rounded-[2rem] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 blur-[100px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary/10 blur-[100px] rounded-full"></div>
        
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-8">{{ $title }}</h1>
            
            <div class="prose prose-invert max-w-none text-gray-300">
                <p class="text-lg mb-6">
                    This is a placeholder page for <strong>{{ $title }}</strong>. In a real application, this page would contain the detailed legal or contact information required for your fitness platform.
                </p>
                <p class="mb-4">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
                <h2 class="text-2xl font-bold text-white mt-8 mb-4">Section 1: General Information</h2>
                <p class="mb-4">
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
                <h2 class="text-2xl font-bold text-white mt-8 mb-4">Section 2: User Responsibilities</h2>
                <ul class="list-disc pl-6 mb-4 space-y-2">
                    <li>Maintain accurate profile information.</li>
                    <li>Consult a physician before starting new workout plans.</li>
                    <li>Respect the community guidelines.</li>
                </ul>
                <p>
                    For more information or inquiries, please contact our support team.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
