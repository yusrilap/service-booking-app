<?php

namespace App\Http\Controllers;

use App\Models\Service;

class PublicController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('public.home', compact('services'));
    }
}
