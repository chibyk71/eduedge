<?php

namespace App\Http\Controllers\Settings\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $setting = tenant()->getSetting('contact', []);
        // Display Contact settings
        return Inertia::render('Settings.School.Contact', compact('setting'));
    }

    public function store(Request $request)
    {
        // Save Contact settings
        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'map_embed_code' => 'nullable|string',
            // Add more validation rules as needed
        ]);

        // get the ccurrent school/tenent to scope the settings to
        $currentlyActiveSchool = tenant();

        // Save the settings to the database or perform other actions
       $currentlyActiveSchool->setSetting('contact', $validated);


        return redirect()->route('settings.school.contact.index')->with('success', 'Contact settings saved successfully.');
    }
}
