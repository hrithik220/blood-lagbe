<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonationRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $myRequests = BloodRequest::where('user_id', $user->id)->latest()->take(5)->get();
        $pendingRequests = BloodRequest::where('user_id', $user->id)->where('status', 'pending')->count();
        $approvedRequests = BloodRequest::where('user_id', $user->id)->where('status', 'approved')->count();
        $fulfilledRequests = BloodRequest::where('user_id', $user->id)->where('status', 'fulfilled')->count();

        $donationHistory = null;
        if ($user->isDonor()) {
            $donationHistory = DonationRecord::where('donor_id', $user->id)->latest()->take(5)->get();
        }

        $notifications = $user->unreadNotifications()->take(5)->get();
        $unreadCount = $user->unreadNotifications()->count();

        return view('dashboard.index', compact(
            'user',
            'myRequests',
            'pendingRequests',
            'approvedRequests',
            'fulfilledRequests',
            'donationHistory',
            'notifications',
            'unreadCount'
        ));
    }
}
