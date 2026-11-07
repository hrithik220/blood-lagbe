<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Faq;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        $faqs = Faq::active()->take(5)->get();
        return view('contact', compact('faqs'));
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:20|max:2000',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Your message has been sent! We will get back to you within 24-48 hours.');
    }
}
