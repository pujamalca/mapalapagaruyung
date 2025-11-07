<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $contactInfo = [
            'address' => 'Kampus Universitas, Jl. Pendidikan No. 123, Padang, Sumatera Barat',
            'email' => 'info@mapalapagaruyung.org',
            'phone' => '+62 812 3456 7890',
            'social_media' => [
                'facebook' => 'https://facebook.com/mapalapagaruyung',
                'instagram' => 'https://instagram.com/mapalapagaruyung',
                'twitter' => 'https://twitter.com/mapalapagaruyung',
                'youtube' => 'https://youtube.com/@mapalapagaruyung',
            ],
            'office_hours' => [
                [
                    'day' => 'Senin - Jumat',
                    'time' => '09:00 - 17:00 WIB',
                ],
                [
                    'day' => 'Sabtu',
                    'time' => '09:00 - 14:00 WIB',
                ],
                [
                    'day' => 'Minggu',
                    'time' => 'Tutup',
                ],
            ],
            'map_coordinates' => [
                'lat' => -0.9471,
                'lng' => 100.4172,
            ],
        ];

        return view('pages.modern-contact', compact('contactInfo'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // TODO: Send email notification or save to database
        // For now, just return success

        return back()->with('success', 'Pesan Anda telah terkirim! Kami akan segera menghubungi Anda.');
    }
}
