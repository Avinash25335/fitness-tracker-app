@extends('layouts.dashboard')

@section('page-title', 'All Users')
@section('page-subtitle', 'Manage registered members')

@section('content')
<div class="space-y-6 fade-up">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-extrabold text-white">Registered Users</h2>
        <span class="badge-green">{{ $users->total() }} total</span>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border-col text-xs text-gray-500 uppercase tracking-wider">
                    <th class="text-left py-4 px-6 font-semibold">User</th>
                    <th class="text-left py-4 px-6 font-semibold hidden md:table-cell">Role</th>
                    <th class="text-left py-4 px-6 font-semibold hidden lg:table-cell">Joined</th>
                    <th class="text-right py-4 px-6 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-col/50">
                @forelse($users as $user)
                <tr class="hover:bg-surface-3/50 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand/40 to-brand-dark/40 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-white">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 hidden md:table-cell">
                        <span class="{{ $user->role === 'admin' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'bg-green-500/10 text-green-400 border border-green-500/20' }} text-xs font-semibold px-2.5 py-1 rounded-full inline-block">
                            {{ ucfirst($user->role ?? 'user') }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-gray-500 text-xs hidden lg:table-cell">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-gray-500 hover:text-red-400 font-medium transition-colors px-3 py-1.5 rounded-lg hover:bg-red-500/10">
                                Delete
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-700">You</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-16 text-center text-gray-500">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="text-sm text-gray-500">
        {{ $users->links() }}
    </div>
</div>
@endsection
