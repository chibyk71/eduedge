<?php

namespace App\Http\Controllers\Settings\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GeneralController extends Controller
{
    public function index()
    {
        // Fetch General settings
        $settings = tenant()->getSetting('general', []);

        return Inertia::render('Settings.School.General', compact('settings'));
    }

    public function store(Request $request)
    {
        // Save General settings
        $validatedData = $request->validate([
            'school_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'motor' => 'sometimes|string',
            'about' => 'sometimes|string',
            'pledge' => 'nullable|string',
            'anthem' => 'nullable|string',
        ]);


        // Update or create the general settings for the current school
        tenant()->setSetting('general', $validatedData);

        return redirect()->route('settings.school.general.index')->with('success', 'General settings updated successfully.');
    }
}
