<?php

namespace App\Http\Controllers;

use App\Models\DonorProfile;
use App\Models\User;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $query = DonorProfile::with('user')
            ->where('is_public', true)
            ->where('is_available', true);

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->filled('district')) {
            $query->where('district', 'like', '%' . $request->district . '%');
        }

        if ($request->filled('area')) {
            $query->where('area', 'like', '%' . $request->area . '%');
        }

        $donors = $query->latest()->paginate(12)->withQueryString();

        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $districts = DonorProfile::where('is_public', true)
            ->distinct()
            ->pluck('district')
            ->sort()
            ->values();

        return view('donors.index', compact('donors', 'bloodGroups', 'districts'));
    }

    public function register()
    {
        if (auth()->check() && auth()->user()->isDonor() && auth()->user()->donorProfile) {
            return redirect()->route('dashboard.donor-profile')->with('info', 'You already have a donor profile.');
        }

        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $districts = $this->getDistricts();

        return view('donors.register', compact('bloodGroups', 'districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . (auth()->check() ? ',' . auth()->id() : ''),
            'phone' => 'required|string|max:20',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'district' => 'required|string|max:100',
            'area' => 'nullable|string|max:100',
            'last_donation_date' => 'nullable|date|before:today',
            'is_available' => 'boolean',
            'bio' => 'nullable|string|max:500',
            'consent' => 'required|accepted',
        ]);

        if (auth()->check()) {
            $user = auth()->user();
            $user->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'district' => $validated['district'],
                'area' => $validated['area'] ?? null,
                'role' => 'donor',
            ]);

            DonorProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'blood_group' => $validated['blood_group'],
                    'district' => $validated['district'],
                    'area' => $validated['area'] ?? null,
                    'phone' => $validated['phone'],
                    'last_donation_date' => $validated['last_donation_date'] ?? null,
                    'is_available' => $request->boolean('is_available', true),
                    'bio' => $validated['bio'] ?? null,
                ]
            );

            return redirect()->route('dashboard.donor-profile')
                ->with('success', 'Your donor profile has been created successfully! Thank you for being a lifesaver.');
        }

        // Not logged in — redirect to register page with flash
        session(['donor_register_intent' => $validated]);
        return redirect()->route('register')
            ->with('info', 'Please create an account first, then complete your donor profile.');
    }

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
}
