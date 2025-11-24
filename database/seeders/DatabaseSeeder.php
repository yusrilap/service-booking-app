<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\StaffSchedule;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin
        $admin = User::create([
            'name' => 'Admin Booking',
            'email' => 'admin@example.com',
            'phone' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // 2. Staff
        $staff1 = User::create([
            'name' => 'Staff One',
            'email' => 'staff1@example.com',
            'phone' => '081111111111',
            'role' => 'staff',
            'password' => Hash::make('password'),
        ]);

        $staff2 = User::create([
            'name' => 'Staff Two',
            'email' => 'staff2@example.com',
            'phone' => '082222222222',
            'role' => 'staff',
            'password' => Hash::make('password'),
        ]);

        // Profile staff
        StaffProfile::create([
            'user_id' => $staff1->id,
            'specialty' => 'Hair Stylist',
            'bio' => 'Spesialis potong rambut dan styling.',
        ]);

        StaffProfile::create([
            'user_id' => $staff2->id,
            'specialty' => 'Massage Therapist',
            'bio' => 'Berpengalaman dalam full body massage.',
        ]);

        // 3. Customer
        $customers = User::factory()->count(5)->create([
            'role' => 'customer',
        ]);

        // 4. Services
        $serviceHair = Service::create([
            'name' => 'Basic Haircut',
            'description' => 'Potong rambut standar untuk pria/wanita.',
            'duration' => 30,
            'price' => 50000,
            'is_active' => true,
        ]);

        $serviceMassage = Service::create([
            'name' => 'Full Body Massage',
            'description' => 'Pijat seluruh tubuh selama 60 menit.',
            'duration' => 60,
            'price' => 150000,
            'is_active' => true,
        ]);

        $serviceFacial = Service::create([
            'name' => 'Facial Treatment',
            'description' => 'Perawatan wajah lengkap.',
            'duration' => 45,
            'price' => 120000,
            'is_active' => true,
        ]);

        // 5. Relasi service-staff
        $serviceHair->staff()->attach([$staff1->id]);
        $serviceMassage->staff()->attach([$staff2->id]);
        $serviceFacial->staff()->attach([$staff1->id, $staff2->id]);

        // 6. Jadwal staff (Senin–Jumat, 09:00–17:00)
        foreach ([$staff1, $staff2] as $staff) {
            for ($day = 1; $day <= 5; $day++) {
                StaffSchedule::create([
                    'staff_id' => $staff->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                ]);
            }
        }

        // 7. Booking dummy
        $today = now()->toDateString();

        foreach ($customers as $customer) {
            Booking::create([
                'customer_id' => $customer->id,
                'service_id' => $serviceHair->id,
                'staff_id' => $staff1->id,
                'booking_date' => $today,
                'start_time' => '10:00:00',
                'end_time' => '10:30:00',
                'status' => 'confirmed',
                'notes' => 'Booking test dummy.',
            ]);

            Booking::create([
                'customer_id' => $customer->id,
                'service_id' => $serviceMassage->id,
                'staff_id' => $staff2->id,
                'booking_date' => now()->addDay()->toDateString(),
                'start_time' => '14:00:00',
                'end_time' => '15:00:00',
                'status' => 'pending',
                'notes' => null,
            ]);
        }
    }
}
