<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-emerald-400">
                    Staff panel
                </p>
                <h2 class="font-semibold text-xl text-slate-50 leading-tight">
                    Booking yang kamu handle
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Riwayat dan jadwal booking berdasarkan akun staff ini.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/70 border border-slate-800 rounded-2xl shadow-sm">
                <div class="p-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-50">
                        Daftar booking
                    </h3>
                    <span class="text-[11px] text-slate-400">
                        {{ $bookings->total() }} booking ditemukan
                    </span>
                </div>

                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 text-[11px] uppercase tracking-wide">
                                <th class="text-left py-2 pr-3">Tanggal</th>
                                <th class="text-left py-2 pr-3">Waktu</th>
                                <th class="text-left py-2 pr-3">Layanan</th>
                                <th class="text-left py-2 pr-3">Customer</th>
                                <th class="text-left py-2 pr-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-slate-900/70 transition">
                                    <td class="py-2 pr-3 align-top">
                                        <span class="text-xs text-slate-100">
                                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                        </span>
                                    </td>
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
                                                'pending' => 'bg-amber-500/10 text-amber-300 border-amber-500/40',
                                                'confirmed' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/40',
                                                'completed' => 'bg-sky-500/10 text-sky-300 border-sky-500/40',
                                                'cancelled' => 'bg-rose-500/10 text-rose-300 border-rose-500/40',
                                                default => 'bg-slate-700/40 text-slate-200 border-slate-600',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] border {{ $badgeClass }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-xs text-slate-400">
                                        Belum ada booking yang kamu handle.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
