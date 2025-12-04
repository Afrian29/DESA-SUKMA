<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VillageProfile;
use App\Models\Official;
use App\Models\Institution;
use App\Models\Gallery;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = VillageProfile::first();
        $officials = Official::orderBy('order')->get();
        $institutions = Institution::all();
        $galleries = Gallery::latest()->get();

        return view('admin.profile.index', compact('profile', 'officials', 'institutions', 'galleries'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'kades_name' => 'required|string|max:255',
            'sambutan_title' => 'required|string|max:255',
            'sambutan_content' => 'required|string',
            'kades_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $profile = VillageProfile::firstOrNew();
        $profile->kades_name = $request->kades_name;
        $profile->sambutan_title = $request->sambutan_title;
        $profile->sambutan_content = $request->sambutan_content;
        $profile->video_url = $request->video_url;

        if ($request->hasFile('kades_photo')) {
            $path = $request->file('kades_photo')->store('public/profile');
            $profile->kades_photo = str_replace('public/', 'storage/', $path);
        }

        $profile->save();

        return redirect()->back()->with('success', 'Profil Desa berhasil diperbarui.');
    }
}
