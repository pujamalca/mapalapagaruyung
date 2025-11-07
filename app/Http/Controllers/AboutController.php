<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Division;
use App\Models\User;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        // Get current leadership (users with role Admin or specific positions)
        $leadership = User::role(['Admin', 'Super Admin'])
            ->where('is_active', true)
            ->take(6)
            ->get();

        // Get all divisions
        $divisions = Division::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get latest cohort
        $latestCohort = Cohort::where('is_active', true)
            ->latest('year')
            ->first();

        // Statistics
        $stats = [
            'total_members' => User::role(['Member', 'Alumni', 'Senior Member'])->count(),
            'active_members' => User::role(['Member', 'Senior Member'])->where('is_active', true)->count(),
            'alumni' => User::role('Alumni')->count(),
            'cohorts' => Cohort::count(),
            'divisions' => Division::where('is_active', true)->count(),
        ];

        // History timeline data
        $timeline = [
            [
                'year' => '2010',
                'title' => 'Berdirinya Mapala Pagaruyung',
                'description' => 'Mapala Pagaruyung resmi didirikan oleh sekelompok mahasiswa pecinta alam yang memiliki visi untuk mengembangkan kegiatan alam di kampus.',
            ],
            [
                'year' => '2012',
                'title' => 'Ekspedisi Pertama',
                'description' => 'Melakukan ekspedisi besar pertama ke Gunung Kerinci, menandai tonggak sejarah penting dalam perjalanan organisasi.',
            ],
            [
                'year' => '2015',
                'title' => 'Juara Nasional',
                'description' => 'Meraih juara pertama dalam kompetisi pendakian tingkat nasional, mengharumkan nama kampus dan daerah.',
            ],
            [
                'year' => '2018',
                'title' => 'Pengembangan Divisi',
                'description' => 'Membentuk struktur divisi yang lebih terorganisir untuk meningkatkan efektivitas kegiatan organisasi.',
            ],
            [
                'year' => '2020',
                'title' => 'Era Digital',
                'description' => 'Transformasi digital dengan sistem manajemen anggota dan dokumentasi kegiatan berbasis teknologi.',
            ],
            [
                'year' => '2024',
                'title' => 'Inovasi & Pertumbuhan',
                'description' => 'Terus berinovasi dalam kegiatan kepecintaalaman dengan menggabungkan teknologi dan pelestarian lingkungan.',
            ],
        ];

        return view('pages.modern-about', compact('leadership', 'divisions', 'latestCohort', 'stats', 'timeline'));
    }
}
