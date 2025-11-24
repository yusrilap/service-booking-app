<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-emerald-400">
                    Customer panel
                </p>
                <h2 class="font-semibold text-xl text-slate-50 leading-tight">
                    Buat Booking Baru
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Pilih layanan, staff, dan jadwal yang kamu inginkan.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/70 border border-slate-800 rounded-2xl shadow-sm p-6">
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-300">
                        <p class="font-semibold mb-1">Gagal membuat booking</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form method="POST" action="{{ route('customer.bookings.store') }}" class="space-y-5">
                    @csrf

                    {{-- Layanan --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-200">
                            Layanan
                        </label>
                        <select name="service_id"
                                class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">-- Pilih layanan --</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                    {{ $service->name }} ({{ $service->duration }} menit, Rp {{ number_format($service->price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Staff --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-200">
                            Staff
                        </label>
                        <select name="staff_id"
                                class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">-- Pilih staff --</option>
                            @foreach ($staff as $s)
                                <option value="{{ $s->id }}" @selected(old('staff_id') == $s->id)>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">
                            (Nanti bisa disaring per layanan, untuk sementara pilih manual.)
                        </p>
                    </div>

                    {{-- Tanggal --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-200">
                            Tanggal
                        </label>
                        <input type="date" name="booking_date"
                               value="{{ old('booking_date', now()->toDateString()) }}"
                               class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    {{-- Jam mulai --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-200">
                            Slot waktu tersedia
                        </label>

                        <select name="start_time" id="slotSelect"
                            class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">-- Pilih layanan, staff & tanggal dulu --</option>
                        </select>

                        <p class="text-[11px] text-slate-500 mt-1">
                            Slot otomatis disesuaikan dengan jadwal dan booking staff.
                        </p>
                    </div>

                    {{-- Catatan --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-200">
                            Catatan (opsional)
                        </label>
                        <textarea name="notes" rows="3"
                                  class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring-emerald-500">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('customer.bookings.index') }}"
                           class="text-xs text-slate-400 hover:text-slate-200">
                            ← Kembali ke daftar booking
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-medium bg-emerald-500 hover:bg-emerald-400 text-slate-900 transition">
                            Simpan booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const serviceSelect = document.querySelector('[name="service_id"]');
    const staffSelect   = document.querySelector('[name="staff_id"]');
    const dateInput     = document.querySelector('[name="booking_date"]');
    const slotSelect    = document.getElementById('slotSelect');

    async function loadSlots() {
        const serviceId = serviceSelect.value;
        const staffId   = staffSelect.value;
        const date      = dateInput.value;

        if (!serviceId || !staffId || !date) {
            slotSelect.innerHTML = `<option>Lengkapi pilihan dulu</option>`;
            return;
        }

        slotSelect.innerHTML = `<option>Loading slot...</option>`;

        const response = await fetch(
            `/customer/slots?service_id=${serviceId}&staff_id=${staffId}&booking_date=${date}`
        );

        const slots = await response.json();

        slotSelect.innerHTML = '';

        if (slots.length === 0) {
            slotSelect.innerHTML = `<option>Tidak ada slot tersedia</option>`;
            return;
        }

        slots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot.start;
            option.textContent = `${slot.start} - ${slot.end}`;
            slotSelect.appendChild(option);
        });
    }

    serviceSelect.addEventListener('change', loadSlots);
    staffSelect.addEventListener('change', loadSlots);
    dateInput.addEventListener('change', loadSlots);
});
</script>
