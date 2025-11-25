<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class AdminBookingController extends Controller
{
    public function index(Request $request)
{
    $query = Booking::with(['customer', 'staff', 'service']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('staff', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 📅 Filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        $bookings = $query
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->paginate(10)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking->status = $request->status;
        $booking->save();

        return back()->with(
            'status',
            'Status booking #' . $booking->id . ' berhasil diubah menjadi ' . ucfirst($request->status) . '.'
        );
    }

    public function reportDashboard(Request $request)
    {
        // Periode filter (default: 7 hari terakhir)
        $dateFrom = $request->input('from', now()->subDays(6)->toDateString());
        $dateTo   = $request->input('to', now()->toDateString());

        // Pastikan from <= to
        if ($dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        // Query dasar untuk periode
        $baseQuery = Booking::whereBetween('booking_date', [$dateFrom, $dateTo]);

        // KPI
        $totalAllTime   = Booking::count();
        $totalInRange   = (clone $baseQuery)->count();
        $completedInRange = (clone $baseQuery)->where('status', 'completed')->count();
        $completionRate = $totalInRange > 0 ? round(($completedInRange / $totalInRange) * 100, 1) : 0;

        // Layanan teratas di periode
        $topService = (clone $baseQuery)
            ->select('services.name', DB::raw('COUNT(bookings.id) as total'))
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->first();

        // Grafik: booking per hari di periode
        $bookingsPerDay = (clone $baseQuery)
            ->select(
                DB::raw('DATE(booking_date) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Grafik: distribusi status (periode)
        $statusStats = (clone $baseQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Grafik: layanan terlaris (periode)
        $serviceStats = (clone $baseQuery)
            ->select('services.name', DB::raw('COUNT(bookings.id) as total'))
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.reports.dashboard', [
            'dateFrom'        => $dateFrom,
            'dateTo'          => $dateTo,
            'totalAllTime'    => $totalAllTime,
            'totalInRange'    => $totalInRange,
            'completionRate'  => $completionRate,
            'topService'      => $topService,
            'bookingsPerDay'  => $bookingsPerDay,
            'statusStats'     => $statusStats,
            'serviceStats'    => $serviceStats,
        ]);
    }

}
