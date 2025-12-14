<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mission;
use Illuminate\Support\Facades\Validator;

class MissionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'visimisi');
        }

        Mission::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Misi berhasil ditambahkan.')->with('active_tab', 'visimisi');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'visimisi');
        }

        $mission = Mission::findOrFail($id);
        $mission->update([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Misi berhasil diperbarui.')->with('active_tab', 'visimisi');
    }

    public function destroy($id)
    {
        $mission = Mission::findOrFail($id);
        $mission->delete();

        return redirect()->back()->with('success', 'Misi berhasil dihapus.')->with('active_tab', 'visimisi');
    }
}
