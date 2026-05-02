<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Faq;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function faq()
    {
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get();
        return view('pages.faq', compact('faqs'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Contact::create($validated);

        return back()->with('success', 'Pesan berhasil dikirim! Kami akan menghubungi Anda segera.');
    }
}
