<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-emerald-400">
                    Customer panel
                </p>
                <h2 class="font-semibold text-xl text-slate-50 leading-tight">
                    Booking Saya
                </h2>
            </div>

            <a href="{{ route('customer.bookings.create') }}"
               class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium bg-emerald-500 hover:bg-emerald-400 text-slate-900 transition">
                + Booking baru
            </a>
        </div>
    </x-slot>

    {{-- Notif sukses --}}
    @if (session('status'))
        <div class="max-w-6xl mx-auto px-6 lg:px-8 mt-4">
            <div class="flex items-start gap-3 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                <svg class="w-5 h-5 mt-0.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <div>
                    <p class="font-semibold">
                        Berhasil!
                    </p>
                    <p class="text-xs text-emerald-200/80">
                        {{ session('status') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/70 border border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-50">
                        Riwayat booking
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-1">
                        Lihat semua jadwal yang sudah kamu buat di sistem.
                    </p>
                </div>

                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 text-[11px] uppercase tracking-wide">
                                <th class="text-left py-2 pr-4">Tanggal</th>
                                <th class="text-left py-2 pr-4">Waktu</th>
                                <th class="text-left py-2 pr-4">Layanan</th>
                                <th class="text-left py-2 pr-4">Staff</th>
                                <th class="text-left py-2 pr-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-slate-900/80 transition">
                                    <td class="py-2 pr-4 align-top text-slate-100">
                                        {{ $booking->booking_date }}
                                    </td>
                                    <td class="py-2 pr-4 align-top text-slate-100">
                                        {{ substr($booking->start_time, 0, 5) }} -
                                        {{ substr($booking->end_time, 0, 5) }}
                                    </td>
                                    <td class="py-2 pr-4 align-top text-slate-100">
                                        {{ $booking->service->name ?? '-' }}
                                    </td>
                                    <td class="py-2 pr-4 align-top text-slate-100">
                                        {{ $booking->staff->name ?? '-' }}
                                    </td>
                                    <td class="py-2 pr-4 align-top">
                                        @php
                                            $status = $booking->status;
                                            $badgeClass = match ($status) {
                                                'pending'   => 'bg-amber-500/10 text-amber-300 border-amber-500/40',
                                                'confirmed' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/40',
                                                'completed' => 'bg-sky-500/10 text-sky-300 border-sky-500/40',
                                                'cancelled' => 'bg-rose-500/10 text-rose-300 border-rose-500/40',
                                                default     => 'bg-slate-700/40 text-slate-200 border-slate-600',
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
                                        Kamu belum punya booking.
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
