<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VillageProfile;
use App\Models\Official;
use App\Models\Institution;
use App\Models\Gallery;
use App\Models\Mission;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = VillageProfile::first();
        $officials = Official::orderBy('order')->get();
        $institutions = Institution::all();
        $galleries = Gallery::latest()->get();
        $missions = Mission::all();

        return view('admin.profile.index', compact('profile', 'officials', 'institutions', 'galleries', 'missions'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kades_name' => 'sometimes|required|string|max:255',
            'sambutan_title' => 'sometimes|required|string|max:255',
            'sambutan_content' => 'sometimes|required|string',
            'kades_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'kades');
        }

        $profile = VillageProfile::firstOrNew();

        // Only update fields that are present in the request
        if ($request->has('kades_name')) $profile->kades_name = $request->kades_name;
        if ($request->has('sambutan_title')) $profile->sambutan_title = $request->sambutan_title;
        if ($request->has('sambutan_content')) $profile->sambutan_content = $request->sambutan_content;
        if ($request->has('video_url')) $profile->video_url = $request->video_url;
        if ($request->has('visi')) $profile->visi = $request->visi;
        if ($request->has('misi')) $profile->misi = $request->misi;

        if ($request->hasFile('kades_photo')) {
            // Hapus foto lama jika ada
            if ($profile->kades_photo && file_exists(public_path($profile->kades_photo))) {
                unlink(public_path($profile->kades_photo));
            }

            // Simpan foto baru ke disk public
            $file = $request->file('kades_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('profile', $fileName, 'public');
            $profile->kades_photo = 'storage/' . $path;
        }

        $profile->save();

        // Determine active tab for redirect
        $activeTab = $request->has('visi') ? 'visimisi' : 'kades';
        return redirect()->back()->with('success', 'Profil Desa berhasil diperbarui.')->with('active_tab', $activeTab);
    }
}
