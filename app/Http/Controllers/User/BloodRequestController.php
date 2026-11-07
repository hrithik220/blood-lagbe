<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Notifications\RequestApprovedNotification;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    private array $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

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

    public function index()
    {
        $requests = BloodRequest::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('dashboard.requests.index', compact('requests'));
    }

    public function create()
    {
        $bloodGroups = $this->bloodGroups;
        $districts = $this->getDistricts();
        return view('dashboard.requests.create', compact('bloodGroups', 'districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'hospital_name' => 'required|string|max:255',
            'hospital_address' => 'required|string|max:500',
            'district' => 'required|string|max:100',
            'area' => 'nullable|string|max:100',
            'required_date' => 'required|date|after_or_equal:today',
            'bags_needed' => 'required|integer|min:1|max:10',
            'contact_number' => 'required|string|max:20',
            'emergency_level' => 'required|in:normal,urgent,critical',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        $bloodRequest = BloodRequest::create($validated);

        return redirect()->route('dashboard.requests.show', $bloodRequest)
            ->with('success', 'Blood request submitted successfully! It will be reviewed by our team shortly.');
    }

    public function show(BloodRequest $bloodRequest)
    {
        $this->authorize('view', $bloodRequest);
        return view('dashboard.requests.show', compact('bloodRequest'));
    }

    public function edit(BloodRequest $bloodRequest)
    {
        $this->authorize('update', $bloodRequest);

        if (!in_array($bloodRequest->status, ['pending', 'rejected'])) {
            return back()->with('error', 'You can only edit pending or rejected requests.');
        }

        $bloodGroups = $this->bloodGroups;
        $districts = $this->getDistricts();
        return view('dashboard.requests.edit', compact('bloodRequest', 'bloodGroups', 'districts'));
    }

    public function update(Request $request, BloodRequest $bloodRequest)
    {
        $this->authorize('update', $bloodRequest);

        if (!in_array($bloodRequest->status, ['pending', 'rejected'])) {
            return back()->with('error', 'You can only edit pending or rejected requests.');
        }

        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'hospital_name' => 'required|string|max:255',
            'hospital_address' => 'required|string|max:500',
            'district' => 'required|string|max:100',
            'area' => 'nullable|string|max:100',
            'required_date' => 'required|date|after_or_equal:today',
            'bags_needed' => 'required|integer|min:1|max:10',
            'contact_number' => 'required|string|max:20',
            'emergency_level' => 'required|in:normal,urgent,critical',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['status'] = 'pending'; // Reset to pending after edit
        $bloodRequest->update($validated);

        return redirect()->route('dashboard.requests.show', $bloodRequest)
            ->with('success', 'Blood request updated and resubmitted for review.');
    }

    public function destroy(BloodRequest $bloodRequest)
    {
        $this->authorize('delete', $bloodRequest);

        if (!in_array($bloodRequest->status, ['pending', 'rejected', 'cancelled'])) {
            return back()->with('error', 'You cannot delete an active request.');
        }

        $bloodRequest->delete();

        return redirect()->route('dashboard.requests.index')
            ->with('success', 'Blood request deleted.');
    }

    public function cancel(BloodRequest $bloodRequest)
    {
        $this->authorize('update', $bloodRequest);

        if (in_array($bloodRequest->status, ['fulfilled', 'cancelled', 'rejected'])) {
            return back()->with('error', 'This request cannot be cancelled.');
        }

        $bloodRequest->update(['status' => 'cancelled']);

        return back()->with('success', 'Blood request cancelled.');
    }
}
