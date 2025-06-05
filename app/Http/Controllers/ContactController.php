<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index(): View
    {
        return view('pages.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        // Here you would typically:
        // 1. Save the contact form submission to database
        // 2. Send email notification to admin
        // 3. Send auto-reply to customer
        
        // For now, we'll just redirect with success message
        return redirect()->route('contact.index')
            ->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
