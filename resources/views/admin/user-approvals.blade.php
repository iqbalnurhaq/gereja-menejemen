@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Persetujuan Pengguna</h1>
            <p class="text-gray-600 mt-2">Kelola registrasi pengguna yang menunggu persetujuan</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabs -->
        <div class="mb-6">
            <div class="flex border-b">
                <button class="px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600" onclick="showTab('pending')">
                    Menunggu Persetujuan ({{ $pendingUsers->count() }})
                </button>
                <button class="px-4 py-2 font-semibold text-gray-600 hover:text-gray-900" onclick="showTab('approved')">
                    Sudah Disetujui ({{ $approvedUsers->count() }})
                </button>
            </div>
        </div>

        <!-- Pending Users Table -->
        <div id="pending" class="tab-content">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($pendingUsers->count() > 0)
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Nama</th>
                                <th class="px-6 py-3 text-left font-semibold">Email</th>
                                <th class="px-6 py-3 text-left font-semibold">Role</th>
                                <th class="px-6 py-3 text-left font-semibold">Terdaftar</th>
                                <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingUsers as $user)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $user->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="approveUser({{ $user->id }})" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                                            Setujui
                                        </button>
                                        <button onclick="openRejectModal({{ $user->id }}, '{{ $user->name }}')" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm ml-2">
                                            Tolak
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-center text-gray-600">
                        Tidak ada pengguna yang menunggu persetujuan
                    </div>
                @endif
            </div>
        </div>

        <!-- Approved Users Table -->
        <div id="approved" class="tab-content hidden">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($approvedUsers->count() > 0)
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Nama</th>
                                <th class="px-6 py-3 text-left font-semibold">Email</th>
                                <th class="px-6 py-3 text-left font-semibold">Role</th>
                                <th class="px-6 py-3 text-left font-semibold">Disetujui</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($approvedUsers as $user)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $user->approved_at?->format('d M Y H:i') ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-center text-gray-600">
                        Tidak ada pengguna yang disetujui
                    </div>
                @endif
            </div>
        </div>

        <!-- Reject Modal -->
        <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h3 class="text-lg font-semibold mb-4">Tolak Registrasi</h3>
                <form id="rejectForm" method="POST">
                    @csrf
                    <input type="hidden" id="rejectUserId" name="user_id">

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Alasan Penolakan</label>
                        <textarea name="rejection_reason" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });

    // Show selected tab
    document.getElementById(tabName).classList.remove('hidden');

    // Update button styles
    document.querySelectorAll('button').forEach(btn => {
        btn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        btn.classList.add('text-gray-600', 'hover:text-gray-900');
    });
    event.target.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
}

function approveUser(userId) {
    if (confirm('Setujui registrasi pengguna ini?')) {
        window.location.href = `/admin/users/${userId}/approve`;
    }
}

function openRejectModal(userId, userName) {
    document.getElementById('rejectUserId').value = userId;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectForm').action = `/admin/users/${userId}/reject`;
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>

<style>
.hidden {
    display: none;
}
</style>
@endsection
