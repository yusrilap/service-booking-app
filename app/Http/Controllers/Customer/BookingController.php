<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\StaffSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    // LIST BOOKING CUSTOMER
    public function index()
    {
        $bookings = Booking::with(['service', 'staff'])
            ->where('customer_id', auth()->id())
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    // FORM BUAT BOOKING
    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();

        $staff = User::where('role', 'staff')
            ->orderBy('name')
            ->get();

        return view('customer.bookings.create', compact('services', 'staff'));
    }

    // SIMPAN BOOKING BARU
    public function store(Request $request)
    {
        $request->validate([
            'service_id'   => ['required', 'exists:services,id'],
            'staff_id'     => ['required', 'exists:users,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time'   => ['required', 'date_format:H:i'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ]);

        $service = Service::findOrFail($request->service_id);
        $staff   = User::where('role', 'staff')->findOrFail($request->staff_id);

        $bookingDate = Carbon::parse($request->booking_date);
        $startTime   = $request->start_time; // "HH:MM"
        $endTime     = Carbon::parse($startTime)->addMinutes($service->duration)->format('H:i');

        // 1) CEK STAFF PUNYA JADWAL HARI ITU
        $dayOfWeek = $bookingDate->dayOfWeekIso; // 1=Senin ... 7=Minggu

        $schedule = StaffSchedule::where('staff_id', $staff->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $startTime)
            ->where('end_time', '>=', $endTime)
            ->first();

        if (!$schedule) {
            return back()
                ->withErrors(['start_time' => 'Staff tidak tersedia pada jam tersebut.'])
                ->withInput();
        }

        // 2) CEK BENTROK DENGAN BOOKING LAIN
        $conflict = Booking::where('staff_id', $staff->id)
            ->where('booking_date', $bookingDate->toDateString())
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function ($q2) use ($startTime, $endTime) {
                      $q2->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                  });
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['start_time' => 'Slot waktu ini sudah terisi. Silakan pilih jam lain.'])
                ->withInput();
        }

        // 3) SIMPAN BOOKING
        Booking::create([
            'customer_id'  => auth()->id(),
            'service_id'   => $service->id,
            'staff_id'     => $staff->id,
            'booking_date' => $bookingDate->toDateString(),
            'start_time'   => $startTime,
            'end_time'     => $endTime,
            'status'       => 'pending',
            'notes'        => $request->notes,
        ]);

        return redirect()
            ->route('customer.bookings.index')
            ->with('status', 'Booking berhasil dibuat dan menunggu konfirmasi.');
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'service_id'   => 'required|exists:services,id',
            'staff_id'     => 'required|exists:users,id',
            'booking_date' => 'required|date',
        ]);

        $service = Service::findOrFail($request->service_id);
        $staff   = User::findOrFail($request->staff_id);

        $bookingDate = Carbon::parse($request->booking_date);
        $dayOfWeek   = $bookingDate->dayOfWeekIso;

        // Ambil jadwal staff hari itu
        $schedule = StaffSchedule::where('staff_id', $staff->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule) {
            return response()->json([]);
        }

        $duration = $service->duration;
        $slots = [];

        $start = Carbon::parse($schedule->start_time);
        $end   = Carbon::parse($schedule->end_time);

        while ($start->copy()->addMinutes($duration) <= $end) {

            $slotStart = $start->format('H:i');
            $slotEnd   = $start->copy()->addMinutes($duration)->format('H:i');

            // Cek konflik booking
            $conflict = Booking::where('staff_id', $staff->id)
                ->where('booking_date', $bookingDate->toDateString())
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function ($q) use ($slotStart, $slotEnd) {
                    $q->whereBetween('start_time', [$slotStart, $slotEnd])
                    ->orWhereBetween('end_time', [$slotStart, $slotEnd])
                    ->orWhere(function ($q2) use ($slotStart, $slotEnd) {
                        $q2->where('start_time', '<=', $slotStart)
                            ->where('end_time', '>=', $slotEnd);
                    });
                })
                ->exists();

            if (!$conflict) {
                $slots[] = [
                    'start' => $slotStart,
                    'end'   => $slotEnd,
                ];
            }

            $start->addMinutes(15); // interval slot setiap 15 menit
        }

        return response()->json($slots);
    }

}
