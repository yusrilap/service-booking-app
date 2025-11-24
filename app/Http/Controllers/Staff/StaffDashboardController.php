<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Carbon;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $staff = auth()->user();
        $today = Carbon::today()->toDateString();

        $bookingsToday = Booking::with(['customer', 'service'])
            ->where('staff_id', $staff->id)
            ->where('booking_date', $today)
            ->orderBy('start_time')
            ->get();

        $totalUpcoming = Booking::where('staff_id', $staff->id)
            ->where('booking_date', '>=', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        return view('staff.dashboard', compact('bookingsToday', 'totalUpcoming'));
    }
}
