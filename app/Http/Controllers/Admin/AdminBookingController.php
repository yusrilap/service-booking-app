<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

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
}
