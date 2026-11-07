<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use Illuminate\Http\Request;

class BloodRequestPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodRequest::with('user')
            ->where('status', 'approved');

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->filled('district')) {
            $query->where('district', 'like', '%' . $request->district . '%');
        }

        if ($request->filled('emergency_level')) {
            $query->where('emergency_level', $request->emergency_level);
        }

        // Sort urgent/critical first
        $query->orderByRaw("CASE emergency_level WHEN 'critical' THEN 1 WHEN 'urgent' THEN 2 ELSE 3 END")
              ->latest();

        $requests = $query->paginate(12)->withQueryString();

        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $districts = BloodRequest::where('status', 'approved')
            ->distinct()
            ->pluck('district')
            ->sort()
            ->values();

        return view('requests.index', compact('requests', 'bloodGroups', 'districts'));
    }

    public function show(BloodRequest $bloodRequest)
    {
        if ($bloodRequest->status !== 'approved') {
            abort(404);
        }

        return view('requests.show', compact('bloodRequest'));
    }
}
