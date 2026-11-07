<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\DonorProfile;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total_donors' => DonorProfile::where('is_public', true)->count(),
            'total_requests' => BloodRequest::count(),
            'fulfilled_requests' => BloodRequest::where('status', 'fulfilled')->count(),
            'total_users' => User::count(),
        ];

        $bloodGroupStats = DonorProfile::selectRaw('blood_group, count(*) as count')
            ->groupBy('blood_group')
            ->pluck('count', 'blood_group')
            ->toArray();

        $urgentRequests = BloodRequest::with('user')
            ->where('status', 'approved')
            ->whereIn('emergency_level', ['urgent', 'critical'])
            ->latest()
            ->take(6)
            ->get();

        $testimonials = Testimonial::approved()->latest()->take(6)->get();

        $faqs = Faq::active()->take(8)->get();

        return view('home', compact(
            'stats',
            'bloodGroupStats',
            'urgentRequests',
            'testimonials',
            'faqs'
        ));
    }
}
