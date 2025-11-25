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
                        <select name="staff_id" id="staffSelect"
                                class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Pilih layanan dulu</option>
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Staff yang tersedia akan menyesuaikan layanan yang dipilih.
                        </p>
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

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const serviceSelect = document.querySelector('[name="service_id"]');
                        const staffSelect   = document.getElementById('staffSelect');
                        const dateInput     = document.querySelector('[name="booking_date"]');
                        const slotSelect    = document.getElementById('slotSelect');

                        const oldStaffId  = @json(old('staff_id'));
                        const oldServiceId = @json(old('service_id'));
                        const oldStartTime = @json(old('start_time'));

                        async function loadStaff() {
                            const serviceId = serviceSelect.value;

                            // reset slot
                            slotSelect.innerHTML = `<option value="">Lengkapi pilihan dulu</option>`;

                            if (!serviceId) {
                                staffSelect.innerHTML = `<option value="">Pilih layanan dulu</option>`;
                                staffSelect.disabled = true;
                                return;
                            }

                            staffSelect.disabled = true;
                            staffSelect.innerHTML = `<option value="">Loading staff...</option>`;

                            try {
                                const response = await fetch(`/customer/services/${serviceId}/staff`);
                                const staff = await response.json();

                                staffSelect.innerHTML = `<option value="">-- Pilih staff --</option>`;

                                staff.forEach(s => {
                                    const option = document.createElement('option');
                                    option.value = s.id;
                                    option.textContent = s.name;

                                    if (oldStaffId && String(oldStaffId) === String(s.id)) {
                                        option.selected = true;
                                    }

                                    staffSelect.appendChild(option);
                                });

                                staffSelect.disabled = false;

                                // kalau ada oldStartTime & oldStaffId, nanti bisa auto load slot juga
                                if (oldStaffId) {
                                    loadSlots();
                                }
                            } catch (e) {
                                staffSelect.innerHTML = `<option value="">Gagal load staff</option>`;
                                staffSelect.disabled = false;
                            }
                        }

                        async function loadSlots() {
                            const serviceId = serviceSelect.value;
                            const staffId   = staffSelect.value;
                            const date      = dateInput.value;

                            if (!serviceId || !staffId || !date) {
                                slotSelect.innerHTML = `<option value="">Lengkapi layanan, staff & tanggal</option>`;
                                return;
                            }

                            slotSelect.innerHTML = `<option value="">Loading slot...</option>`;

                            try {
                                const response = await fetch(
                                    `/customer/slots?service_id=${serviceId}&staff_id=${staffId}&booking_date=${date}`
                                );

                                const slots = await response.json();

                                slotSelect.innerHTML = '';

                                if (slots.length === 0) {
                                    slotSelect.innerHTML = `<option value="">Tidak ada slot tersedia</option>`;
                                    return;
                                }

                                slots.forEach(slot => {
                                    const option = document.createElement('option');
                                    option.value = slot.start;
                                    option.textContent = `${slot.start} - ${slot.end}`;

                                    if (oldStartTime && oldStartTime === slot.start) {
                                        option.selected = true;
                                    }

                                    slotSelect.appendChild(option);
                                });
                            } catch (e) {
                                slotSelect.innerHTML = `<option value="">Gagal load slot</option>`;
                            }
                        }

                        // event listeners
                        serviceSelect.addEventListener('change', function () {
                            // reset old state
                            staffSelect.value = '';
                            loadStaff();
                        });

                        staffSelect.addEventListener('change', loadSlots);
                        dateInput.addEventListener('change', loadSlots);

                        // initial state (kalau form balik karena error)
                        if (oldServiceId) {
                            serviceSelect.value = oldServiceId;
                            loadStaff();
                        }
                    });
                    </script>

            </div>
        </div>
    </div>
</x-app-layout>


