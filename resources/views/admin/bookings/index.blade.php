<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-emerald-400">
                    Admin panel
                </p>
                <h2 class="font-semibold text-xl text-slate-50 leading-tight">
                    Manajemen Booking
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Pantau dan perbarui status semua jadwal booking.
                </p>
            </div>
        </div>
    </x-slot>

    {{-- Notifikasi sukses --}}
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
                        Perubahan tersimpan
                    </p>
                    <p class="text-xs text-emerald-200/80">
                        {{ session('status') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/70 border border-slate-800 rounded-2xl shadow-sm">
                <div class="p-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-50">
                        Daftar booking
                    </h3>
                    <span class="text-[11px] text-slate-400">
                        {{ $bookings->total() }} total booking
                    </span>
                </div>

                <div class="p-4 overflow-x-auto">
                    <form method="GET" class="mb-4 space-y-3 sm:space-y-0 sm:flex sm:items-end sm:gap-3">
    
                        {{-- Search --}}
                        <div class="w-full sm:w-1/3">
                            <label class="block text-xs text-slate-400 mb-1">Cari customer / staff</label>
                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Nama customer / staff..."
                                class="w-full rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20">
                        </div>

                        {{-- Filter Status --}}
                        <div class="w-full sm:w-1/4">
                            <label class="block text-xs text-slate-400 mb-1">Status</label>
                            <select name="status"
                                class="w-full rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20">
                                <option value="">Semua</option>
                                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                                <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                            </select>
                        </div>

                        {{-- Filter Tanggal --}}
                        <div class="w-full sm:w-1/4">
                            <label class="block text-xs text-slate-400 mb-1">Pilih Tanggal</label>
                            <input type="text"
                                id="filterDate"
                                name="date"
                                placeholder="Klik untuk pilih"
                                value="{{ request('date') }}"
                                class="w-full rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20">
                        </div>




                        {{-- Button --}}
                        <div class="flex gap-2">
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-sm hover:bg-emerald-500/30 transition">
                                Filter
                            </button>

                            <a href="{{ route('admin.bookings.index') }}"
                                class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 border border-slate-700 text-sm hover:bg-slate-700 transition">
                                Reset
                            </a>
                        </div>

                    </form>

                    <table class="min-w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 text-[11px] uppercase tracking-wide">
                                <th class="text-left py-2 pr-3">Tanggal</th>
                                <th class="text-left py-2 pr-3">Waktu</th>
                                <th class="text-left py-2 pr-3">Layanan</th>
                                <th class="text-left py-2 pr-3">Customer</th>
                                <th class="text-left py-2 pr-3">Staff</th>
                                <th class="text-left py-2 pr-3">Status</th>
                                <th class="text-left py-2 pr-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse ($bookings as $booking)
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
                                <tr class="hover:bg-slate-900/70 transition">
                                    <td class="py-2 pr-3 align-top text-slate-100">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                    </td>
                                    <td class="py-2 pr-3 align-top text-slate-100">
                                        {{ substr($booking->start_time, 0, 5) }} -
                                        {{ substr($booking->end_time, 0, 5) }}
                                    </td>
                                    <td class="py-2 pr-3 align-top text-slate-100">
                                        {{ $booking->service->name ?? '-' }}
                                    </td>
                                    <td class="py-2 pr-3 align-top text-slate-200">
                                        {{ $booking->customer->name ?? '-' }}
                                    </td>
                                    <td class="py-2 pr-3 align-top text-slate-200">
                                        {{ $booking->staff->name ?? '-' }}
                                    </td>
                                    <td class="py-2 pr-3 align-top">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] border {{ $badgeClass }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-3 align-top">
                                        <div class="flex flex-wrap gap-1">
                                            @if ($booking->status === 'pending')
                                                {{-- Tombol Confirm --}}
                                                <form method="POST"
                                                      action="{{ route('admin.bookings.update-status', $booking) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit"
                                                        class="px-2 py-1 text-[11px] rounded-lg bg-emerald-500/15 text-emerald-300 border border-emerald-500/40 hover:bg-emerald-500/25 transition">
                                                        Confirm
                                                    </button>
                                                </form>

                                                {{-- Tombol Cancel --}}
                                                <form method="POST"
                                                      action="{{ route('admin.bookings.update-status', $booking) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit"
                                                        class="px-2 py-1 text-[11px] rounded-lg bg-rose-500/15 text-rose-300 border border-rose-500/40 hover:bg-rose-500/25 transition">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @elseif ($booking->status === 'confirmed')
                                                {{-- Tombol Complete --}}
                                                <form method="POST"
                                                      action="{{ route('admin.bookings.update-status', $booking) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit"
                                                        class="px-2 py-1 text-[11px] rounded-lg bg-sky-500/15 text-sky-300 border border-sky-500/40 hover:bg-sky-500/25 transition">
                                                        Mark as done
                                                    </button>
                                                </form>

                                                {{-- Tombol Cancel --}}
                                                <form method="POST"
                                                      action="{{ route('admin.bookings.update-status', $booking) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit"
                                                        class="px-2 py-1 text-[11px] rounded-lg bg-rose-500/15 text-rose-300 border border-rose-500/40 hover:bg-rose-500/25 transition">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[11px] text-slate-500">
                                                    Tidak ada aksi
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-xs text-slate-400">
                                        Belum ada booking.
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#filterDate", {
        dateFormat: "Y-m-d",
        defaultDate: "{{ request('date') ?? '' }}",
        allowInput: true,
    });
});
</script>
