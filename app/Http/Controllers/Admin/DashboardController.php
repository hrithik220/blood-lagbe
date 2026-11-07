<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\ContactMessage;
use App\Models\DonorProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'total_donors' => DonorProfile::count(),
            'total_requests' => BloodRequest::count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'approved_requests' => BloodRequest::where('status', 'approved')->count(),
            'fulfilled_requests' => BloodRequest::where('status', 'fulfilled')->count(),
            'urgent_requests' => BloodRequest::whereIn('emergency_level', ['urgent', 'critical'])->whereIn('status', ['pending', 'approved'])->count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
        ];

        $bloodGroupStats = DonorProfile::selectRaw('blood_group, count(*) as count')
            ->groupBy('blood_group')
            ->orderBy('blood_group')
            ->pluck('count', 'blood_group')
            ->toArray();

        if (DB::getDriverName() === 'sqlite') {
            $monthlyRequestStats = BloodRequest::selectRaw(
                    "strftime('%m', created_at) as month_num, strftime('%m', created_at) as month, count(*) as count"
                )
                ->whereRaw("strftime('%Y', created_at) = ?", [strval(now()->year)])
                ->groupByRaw("strftime('%m', created_at)")
                ->orderBy('month_num')
                ->get();
        } else {
            $monthlyRequestStats = BloodRequest::selectRaw(
                    "TO_CHAR(created_at, 'Mon') as month, EXTRACT(MONTH FROM created_at) as month_num, count(*) as count"
                )
                ->whereYear('created_at', now()->year)
                ->groupByRaw("TO_CHAR(created_at, 'Mon'), EXTRACT(MONTH FROM created_at)")
                ->orderBy('month_num')
                ->get();
        }

        $recentRequests = BloodRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'bloodGroupStats',
            'monthlyRequestStats',
            'recentRequests',
            'recentUsers'
        ));
    }
}
