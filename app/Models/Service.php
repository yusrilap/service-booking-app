<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'price',
        'is_active',
    ];

    public function staff()
    {
        return $this->belongsToMany(User::class, 'service_staff', 'service_id', 'staff_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
