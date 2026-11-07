<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DonorProfile;
use Illuminate\Http\Request;

class DonorProfileController extends Controller
{
    private function getDistricts(): array
    {
        return [
            'Bagerhat', 'Bandarban', 'Barguna', 'Barishal', 'Bhola',
            'Bogura', 'Brahmanbaria', 'Chandpur', 'Chattogram', 'Chuadanga',
            'Cox\'s Bazar', 'Cumilla', 'Dhaka', 'Dinajpur', 'Faridpur',
            'Feni', 'Gaibandha', 'Gazipur', 'Gopalganj', 'Habiganj',
            'Jamalpur', 'Jessore (Jashore)', 'Jhalokathi', 'Jhenaidah', 'Joypurhat',
            'Khagrachari', 'Khulna', 'Kishoreganj', 'Kurigram', 'Kushtia',
            'Lakshmipur', 'Lalmonirhat', 'Madaripur', 'Magura', 'Manikganj',
            'Meherpur', 'Moulvibazar', 'Munshiganj', 'Mymensingh', 'Naogaon',
            'Narail', 'Narayanganj', 'Narsingdi', 'Natore', 'Nawabganj',
            'Netrokona', 'Nilphamari', 'Noakhali', 'Pabna', 'Panchagarh',
            'Patuakhali', 'Pirojpur', 'Rajbari', 'Rajshahi', 'Rangamati',
            'Rangpur', 'Satkhira', 'Shariatpur', 'Sherpur', 'Sirajganj',
            'Sunamganj', 'Sylhet', 'Tangail', 'Thakurgaon',
        ];
    }

    public function show()
    {
        $user = auth()->user();
        $donorProfile = $user->donorProfile;
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $districts = $this->getDistricts();

        return view('dashboard.donor-profile', compact('user', 'donorProfile', 'bloodGroups', 'districts'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'district' => 'required|string|max:100',
            'area' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20',
            'last_donation_date' => 'nullable|date|before:today',
            'is_available' => 'boolean',
            'is_public' => 'boolean',
            'bio' => 'nullable|string|max:500',
        ]);

        DonorProfile::updateOrCreate(
            ['user_id' => $user->id],
            array_merge($validated, [
                'is_available' => $request->boolean('is_available'),
                'is_public' => $request->boolean('is_public'),
            ])
        );

        if ($user->role !== 'donor') {
            $user->update(['role' => 'donor']);
        }

        return back()->with('success', 'Donor profile updated successfully!');
    }

    public function toggleAvailability()
    {
        $user = auth()->user();
        $profile = $user->donorProfile;

        if (!$profile) {
            return back()->with('error', 'No donor profile found.');
        }

        $profile->update(['is_available' => !$profile->is_available]);

        $status = $profile->is_available ? 'available' : 'unavailable';
        return back()->with('success', "You are now marked as {$status} for blood donation.");
    }
}
