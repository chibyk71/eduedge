<?php

namespace App\Http\Controllers\Settings\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Inertia\Inertia;
use RuangDeveloper\LaravelSettings\Facades\Settings;

class BrandingController extends Controller
{
    public function index()
    {
        $setting = tenant()->getSetting('branding', []);
        return Inertia::render('Settings/Branding', compact('setting'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'small_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'accent_color' => 'required|string',
        ]);

        // Upload and store the logo, small_logo, and favicon files
        $logoPath = $request->file('logo')->store('branding', 'public');
        $smallLogoPath = $request->file('small_logo')->store('branding', 'public');
        $faviconPath = $request->file('favicon')->store('branding', 'public');

        // TODO check if this returns the school or just columns from the table
        $currentlyActiveSchool = tenant();

        // Upload and store the logo, small_logo, and favicon files if they are provided
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('branding', 'public');
        }

        if ($request->hasFile('small_logo')) {
            $validated['small_logo'] = $request->file('small_logo')->store('branding', 'public');
        }

        if ($request->hasFile('favicon')) {
            $validated['favicon'] = $request->file('favicon')->store('branding', 'public');
        }

        // Save the file paths to the settings
        $currentlyActiveSchool->setSetting('branding', $validated);

        return redirect()->route('settings.branding.index')->with('success', 'Branding settings updated successfully.');
    }
}
