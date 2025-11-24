<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'staff', 'service'])
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->paginate(10);

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
