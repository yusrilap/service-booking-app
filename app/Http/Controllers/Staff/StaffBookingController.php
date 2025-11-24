<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class StaffBookingController extends Controller
{
    public function index()
    {
        $staff = auth()->user();

        $bookings = Booking::with(['customer', 'service'])
            ->where('staff_id', $staff->id)
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->paginate(10);

        return view('staff.bookings.index', compact('bookings'));
    }
}
