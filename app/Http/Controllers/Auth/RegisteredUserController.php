<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DonorProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($intent = session()->pull('donor_register_intent')) {
            $user->update([
                'phone' => $intent['phone'] ?? null,
                'district' => $intent['district'] ?? null,
                'area' => $intent['area'] ?? null,
                'role' => 'donor',
            ]);

            DonorProfile::create([
                'user_id' => $user->id,
                'blood_group' => $intent['blood_group'],
                'district' => $intent['district'],
                'area' => $intent['area'] ?? null,
                'phone' => $intent['phone'],
                'last_donation_date' => $intent['last_donation_date'] ?? null,
                'is_available' => true,
                'is_public' => true,
                'bio' => $intent['bio'] ?? null,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
