<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorProfile;
use App\Models\User;
use App\Notifications\RequestApprovedNotification;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodRequest::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->filled('emergency_level')) {
            $query->where('emergency_level', $request->emergency_level);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('patient_name', 'like', '%' . $request->search . '%')
                  ->orWhere('hospital_name', 'like', '%' . $request->search . '%')
                  ->orWhere('district', 'like', '%' . $request->search . '%');
            });
        }

        $query->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'approved' THEN 2 ELSE 3 END")
              ->orderByRaw("CASE emergency_level WHEN 'critical' THEN 1 WHEN 'urgent' THEN 2 ELSE 3 END")
              ->latest();

        $requests = $query->paginate(20)->withQueryString();
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        return view('admin.requests.index', compact('requests', 'bloodGroups'));
    }

    public function show(BloodRequest $bloodRequest)
    {
        $bloodRequest->load('user');

        // Find matching donors
        $matchingDonors = DonorProfile::with('user')
            ->where('blood_group', $bloodRequest->blood_group)
            ->where('is_available', true)
            ->where('is_public', true)
            ->where('district', $bloodRequest->district)
            ->get();

        return view('admin.requests.show', compact('bloodRequest', 'matchingDonors'));
    }

    public function approve(BloodRequest $bloodRequest)
    {
        if ($bloodRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $bloodRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        // Notify the requester
        try {
            $bloodRequest->user->notify(new RequestApprovedNotification($bloodRequest));
        } catch (\Exception $e) {
            // Log but don't fail
            logger()->error('Notification failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Blood request approved and requester has been notified.');
    }

    public function reject(Request $request, BloodRequest $bloodRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        if (!in_array($bloodRequest->status, ['pending', 'approved'])) {
            return back()->with('error', 'This request cannot be rejected.');
        }

        $bloodRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Blood request rejected.');
    }

    public function updateStatus(Request $request, BloodRequest $bloodRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,in_progress,fulfilled,cancelled,rejected',
        ]);

        $updateData = ['status' => $request->status];

        if ($request->status === 'fulfilled') {
            $updateData['fulfilled_at'] = now();
        }

        $bloodRequest->update($updateData);

        return back()->with('success', 'Request status updated to: ' . ucfirst($request->status));
    }

    public function destroy(BloodRequest $bloodRequest)
    {
        $bloodRequest->forceDelete();
        return redirect()->route('admin.requests.index')->with('success', 'Blood request permanently deleted.');
    }
}
