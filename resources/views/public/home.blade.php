<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Service Booking App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-950 text-slate-50">

    {{-- NAVBAR --}}
    <nav class="border-b border-slate-800 bg-slate-950/70 backdrop-blur">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-emerald-500/10 border border-emerald-500/40 flex items-center justify-center">
                    <span class="font-bold text-emerald-400 text-sm">SB</span>
                </div>
                <span class="font-semibold tracking-tight">Service Booking App</span>
            </div>

            <div class="flex items-center gap-3 text-sm">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-1.5 rounded-lg border border-slate-700 hover:border-emerald-400 hover:text-emerald-300 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-3 py-1.5 rounded-lg border border-slate-700 hover:border-emerald-400 hover:text-emerald-300 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-medium rounded-lg transition">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO + LIST LAYANAN --}}
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <section class="grid gap-10 md:grid-cols-[1.4fr,1fr] items-start">
            {{-- HERO TEXT --}}
            <div>
                <p class="text-xs font-semibold tracking-[0.25em] text-emerald-400 uppercase mb-3">
                    Online Booking
                </p>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight mb-4">
                    Atur jadwal layananmu<br class="hidden sm:block">
                    dalam <span class="text-emerald-400">satu sistem</span>.
                </h1>
                <p class="text-slate-400 max-w-xl mb-6 text-sm sm:text-base">
                    Customer bisa memilih layanan, tanggal, dan staff langsung dari sistem.
                    Admin dan staff memantau jadwal tanpa ribet. Cocok untuk salon, barbershop,
                    klinik, atau studio.
                </p>

                <div class="flex flex-wrap gap-3 items-center mb-6">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-medium text-sm transition">
                            Pergi ke dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-medium text-sm transition">
                            Mulai sebagai customer
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-lg border border-slate-700 hover:border-emerald-400 text-sm transition">
                            Login dulu
                        </a>
                    @endauth
                </div>

                <div class="flex flex-wrap gap-4 text-xs text-slate-400">
                    <div class="flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Real case: salon / barbershop / klinik
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Role: admin, staff, customer
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Dibangun dengan Laravel + Tailwind
                    </div>
                </div>
            </div>

            {{-- KARTU LAYANAN --}}
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 shadow-xl shadow-black/40">
                <h2 class="text-sm font-semibold mb-3 flex items-center justify-between">
                    Layanan yang tersedia
                    <span class="text-[11px] font-normal text-slate-400">
                        {{ $services->count() }} layanan
                    </span>
                </h2>

                <div class="space-y-3 max-h-[380px] overflow-y-auto pr-1">
                    @forelse ($services as $service)
                        <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-4 hover:border-emerald-400/70 hover:bg-slate-900 transition group">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-semibold text-sm mb-1 group-hover:text-emerald-300">
                                        {{ $service->name }}
                                    </h3>
                                    @if ($service->description)
                                        <p class="text-xs text-slate-400 mb-2">
                                            {{ $service->description }}
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap gap-3 text-[11px] text-slate-300">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-800/80">
                                            Durasi: <span class="font-semibold">{{ $service->duration }} menit</span>
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-800/80">
                                            Harga:
                                            <span class="font-semibold">
                                                Rp {{ number_format($service->price, 0, ',', '.') }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @auth
                                <div class="mt-3">
                                    <a href="{{ route('dashboard') }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-700 text-[11px] hover:border-emerald-400 hover:text-emerald-300 transition">
                                        Pergi ke dashboard
                                    </a>
                                </div>
                            @else
                                <div class="mt-3">
                                    <a href="{{ route('login') }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-700 text-[11px] hover:border-emerald-400 hover:text-emerald-300 transition">
                                        Login untuk booking
                                    </a>
                                </div>
                            @endauth
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">
                            Belum ada layanan yang aktif.
                        </p>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-800 py-4">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-[11px] text-slate-500 flex justify-between">
            <span>Service Booking App — Laravel Portfolio</span>
            <span>Built with Laravel & Tailwind</span>
        </div>
    </footer>

</body>
</html>
