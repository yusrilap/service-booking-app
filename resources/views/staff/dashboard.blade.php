<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-emerald-400">
                    Staff panel
                </p>
                <h2 class="font-semibold text-xl text-slate-50 leading-tight">
                    Dashboard Staff
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Lihat ringkasan jadwal dan booking yang kamu handle.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-5">
                    <p class="text-xs text-slate-400 mb-1">
                        Booking mendatang (pending & confirmed)
                    </p>
                    <p class="text-3xl font-semibold text-emerald-400">
                        {{ $totalUpcoming }}
                    </p>
                    <p class="text-[11px] text-slate-500 mt-2">
                        Termasuk semua jadwal hari ini dan seterusnya.
                    </p>
                </div>

                <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-5">
                    <p class="text-xs text-slate-400 mb-1">
                        Hari ini
                    </p>
                    <p class="text-sm text-slate-100 mb-2">
                        {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}
                    </p>
                    <p class="text-[11px] text-slate-400">
                        Pastikan kamu hadir sesuai jadwal yang tertera di daftar booking hari ini.
                    </p>
                </div>
            </div>

            <div class="bg-slate-900/70 border border-slate-800 rounded-2xl shadow-sm">
                <div class="p-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-50">
                        Booking hari ini
                    </h3>
                    <a href="{{ route('staff.bookings.index') }}"
                       class="text-[11px] text-emerald-300 hover:text-emerald-200">
                        Lihat semua booking →
                    </a>
                </div>

                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 text-[11px] uppercase tracking-wide">
                                <th class="text-left py-2 pr-3">Waktu</th>
                                <th class="text-left py-2 pr-3">Layanan</th>
                                <th class="text-left py-2 pr-3">Customer</th>
                                <th class="text-left py-2 pr-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse ($bookingsToday as $booking)
                                <tr class="hover:bg-slate-900/70 transition">
                                    <td class="py-2 pr-3 align-top">
                                        <span class="text-xs text-slate-100">
                                            {{ substr($booking->start_time, 0, 5) }} -
                                            {{ substr($booking->end_time, 0, 5) }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-3 align-top">
                                        <span class="text-xs text-slate-50">
                                            {{ $booking->service->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-3 align-top">
                                        <span class="text-xs text-slate-200">
                                            {{ $booking->customer->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-3 align-top">
                                        @php
                                            $status = $booking->status;
                                            $badgeClass = match ($status) {
                                                'pending'   => 'bg-amber-500/15 text-amber-300 border-amber-500/50',
                                                'confirmed' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/50',
                                                'completed' => 'bg-sky-500/15 text-sky-300 border-sky-500/50',
                                                'cancelled' => 'bg-rose-500/15 text-rose-300 border-rose-500/50',
                                                default     => 'bg-slate-700/60 text-slate-200 border-slate-500',
                                            };
                                        @endphp

                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] border {{ $badgeClass }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-xs text-slate-400">
                                        Tidak ada booking hari ini.
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
