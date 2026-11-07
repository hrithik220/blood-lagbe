@extends('layouts.admin')

@section('title', 'Edit FAQ #' . $faq->id)

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">Dashboard</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <a href="{{ route('admin.faqs.index') }}" class="hover:text-red-600">FAQs</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <span class="text-red-600 font-medium">Edit #{{ $faq->id }}</span>
@endsection

@section('content')

<div class="max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit FAQ Entry</h1>
        <p class="text-sm text-gray-500 mt-1">Update question, answer content, or display order.</p>
    </div>

    <div class="card p-6 md:p-8">
        <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label for="question" class="label">Question <span class="text-red-500">*</span></label>
                <input type="text" id="question" name="question" value="{{ old('question', $faq->question) }}"
                       class="input @error('question') input-error @enderror" required>
                @error('question')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="label">Category <span class="text-red-500">*</span></label>
                <select id="category" name="category" class="select @error('category') input-error @enderror" required>
                    <option value="general" {{ old('category', $faq->category) === 'general' ? 'selected' : '' }}>General</option>
                    <option value="eligibility" {{ old('category', $faq->category) === 'eligibility' ? 'selected' : '' }}>Eligibility</option>
                    <option value="process" {{ old('category', $faq->category) === 'process' ? 'selected' : '' }}>Donation Process</option>
                    <option value="privacy" {{ old('category', $faq->category) === 'privacy' ? 'selected' : '' }}>Safety & Privacy</option>
                </select>
                @error('category')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="answer" class="label">Answer <span class="text-red-500">*</span></label>
                <textarea id="answer" name="answer" rows="5"
                          class="input @error('answer') input-error @enderror" required>{{ old('answer', $faq->answer) }}</textarea>
                @error('answer')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <div>
                    <label for="sort_order" class="label">Display Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" min="0" class="input">
                    <p class="text-xs text-gray-400 mt-1">Lower numbers appear first.</p>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $faq->is_active) ? 'checked' : '' }}
                               class="rounded text-red-600 focus:ring-red-500">
                        <span class="text-sm font-semibold text-gray-800">Published (Active)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.faqs.index') }}" class="btn-outline text-sm">Cancel</a>
                <button type="submit" class="btn-primary text-sm">
                    <i data-lucide="check" class="w-4 h-4"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
