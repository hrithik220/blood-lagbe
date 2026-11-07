@extends('layouts.app')

@section('title', 'Contact Us — Blood Lagbe')
@section('description', 'Get in touch with the Blood Lagbe team for assistance, inquiries, partnership opportunities, or feedback.')

@section('content')
{{-- Page Header --}}
<div class="hero-gradient hero-pattern text-white py-14 md:py-18">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm text-white mb-4">
            <i data-lucide="message-square" class="w-3.5 h-3.5"></i> We Are Here For You
        </span>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-3">Contact Blood Lagbe</h1>
        <p class="text-lg text-red-100 max-w-xl mx-auto">
            Have questions, feedback, or need help with a donation request? Drop us a line.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

        {{-- Contact Form (7 cols) --}}
        <div class="lg:col-span-7">
            <div class="card p-8 md:p-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Send us a Message</h2>
                <p class="text-gray-600 text-sm mb-6">Fill in the form below and our team will get back to you within 24 to 48 hours.</p>

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="label">Your Name <span class="text-blood-600">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()?->name ?? '') }}"
                               class="input @error('name') input-error @enderror" placeholder="e.g. Tanvir Ahmed" required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="label">Email Address <span class="text-blood-600">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()?->email ?? '') }}"
                               class="input @error('email') input-error @enderror" placeholder="you@example.com" required>
                        @error('email')
                            <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="label">Subject <span class="text-blood-600">*</span></label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                               class="input @error('subject') input-error @enderror" placeholder="Brief subject of your inquiry" required>
                        @error('subject')
                            <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="label">Message <span class="text-blood-600">*</span></label>
                        <textarea id="message" name="message" rows="5"
                                  class="input @error('message') input-error @enderror"
                                  placeholder="How can we assist you? Please provide relevant details..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full justify-center text-base py-3.5">
                        <i data-lucide="send" class="w-4 h-4"></i> Send Message
                    </button>
                </form>
            </div>
        </div>

        {{-- Side Information & FAQ preview (5 cols) --}}
        <div class="lg:col-span-5 space-y-8">
            <div class="card p-8 bg-gradient-to-br from-white to-blood-50/40">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Contact Information</h3>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blood-100 text-blood-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-semibold text-gray-400 tracking-wider">Email Us</p>
                            <a href="mailto:support@bloodlagbe.com" class="text-base font-semibold text-blood-700 hover:underline">
                                support@bloodlagbe.com
                            </a>
                            <p class="text-xs text-gray-500 mt-0.5">Average response: under 24 hours</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blood-100 text-blood-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-semibold text-gray-400 tracking-wider">Headquarters</p>
                            <p class="text-sm font-semibold text-gray-800">Dhaka, Bangladesh</p>
                            <p class="text-xs text-gray-500 mt-0.5">Nationwide network coverage in 64 districts</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blood-100 text-blood-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-semibold text-gray-400 tracking-wider">Emergency Blood Needs</p>
                            <p class="text-sm font-semibold text-gray-800">24/7 Platform Availability</p>
                            <p class="text-xs text-gray-500 mt-0.5">Search donors and submit requests at any time</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($faqs) && $faqs->count() > 0)
                <div class="card p-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Frequently Asked Questions</h3>
                        <a href="{{ route('home') }}#faq" class="text-xs font-semibold text-blood-600 hover:underline">View All</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($faqs as $faq)
                            <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                <h4 class="text-sm font-semibold text-gray-800 mb-1">{{ $faq->question }}</h4>
                                <p class="text-xs text-gray-600 line-clamp-2">{{ Str::limit($faq->answer, 120) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
