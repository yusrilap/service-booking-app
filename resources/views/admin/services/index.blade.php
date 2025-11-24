<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-emerald-400">
                    Admin panel
                </p>
                <h2 class="font-semibold text-xl text-slate-50 leading-tight">
                    Manajemen Layanan
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Lihat dan kelola layanan yang tersedia untuk booking.
                </p>
            </div>

            {{-- Tombol ini nanti bisa diarahkan ke route create saat CRUD sudah dibuat --}}
            <button
                class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium bg-emerald-500 hover:bg-emerald-400 text-slate-900 transition">
                + Tambah layanan
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Ringkasan kecil --}}
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-4">
                    <p class="text-xs text-slate-400 mb-1">Total layanan</p>
                    <p class="text-2xl font-semibold text-slate-50">
                        {{ $services->count() }}
                    </p>
                </div>
                <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-4">
                    <p class="text-xs text-slate-400 mb-1">Layanan aktif</p>
                    <p class="text-2xl font-semibold text-emerald-400">
                        {{ $services->where('is_active', true)->count() }}
                    </p>
                </div>
                <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-4">
                    <p class="text-xs text-slate-400 mb-1">Layanan non-aktif</p>
                    <p class="text-2xl font-semibold text-amber-400">
                        {{ $services->where('is_active', false)->count() }}
                    </p>
                </div>
            </div>

            {{-- Tabel layanan --}}
            <div class="bg-slate-900/70 border border-slate-800 rounded-2xl shadow-sm">
                <div class="p-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-50">
                        Daftar layanan
                    </h3>
                    <span class="text-[11px] text-slate-400">
                        Klik nama layanan untuk melihat detail (bisa diisi nanti).
                    </span>
                </div>

                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 text-[11px] uppercase tracking-wide">
                                <th class="text-left py-2 pr-4">Layanan</th>
                                <th class="text-left py-2 pr-4">Durasi</th>
                                <th class="text-left py-2 pr-4">Harga</th>
                                <th class="text-left py-2 pr-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse ($services as $service)
                                <tr class="hover:bg-slate-900/70 transition">
                                    <td class="py-2 pr-4">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-slate-50 text-sm">
                                                {{ $service->name }}
                                            </span>
                                            @if ($service->description)
                                                <span class="text-[11px] text-slate-400 line-clamp-2">
                                                    {{ $service->description }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-2 pr-4 align-top">
                                        <span class="text-xs text-slate-100">
                                            {{ $service->duration }} menit
                                        </span>
                                    </td>
                                    <td class="py-2 pr-4 align-top">
                                        <span class="text-xs font-semibold text-emerald-300">
                                            Rp {{ number_format($service->price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-4 align-top">
                                        @if ($service->is_active)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-emerald-500/10 text-[11px] text-emerald-300 border border-emerald-500/40">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-slate-700/40 text-[11px] text-slate-200 border border-slate-600">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-xs text-slate-400">
                                        Belum ada layanan yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
