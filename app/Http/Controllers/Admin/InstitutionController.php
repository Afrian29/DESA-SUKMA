<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Institution;
use Illuminate\Support\Facades\Validator;

class InstitutionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'abbr' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'lembaga');
        }

        $data = $request->all();
        $data['icon'] = 'default'; // Default value since column is required

        Institution::create($data);

        return redirect()->back()->with('success', 'Lembaga Desa berhasil ditambahkan.')->with('active_tab', 'lembaga');
    }

    public function update(Request $request, $id)
    {
        $institution = Institution::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'abbr' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'lembaga');
        }

        $institution->update($request->all());

        return redirect()->back()->with('success', 'Lembaga Desa berhasil diperbarui.')->with('active_tab', 'lembaga');
    }

    public function destroy($id)
    {
        $institution = Institution::findOrFail($id);
        $institution->delete();

        return redirect()->back()->with('success', 'Lembaga Desa berhasil dihapus.')->with('active_tab', 'lembaga');
    }
}
