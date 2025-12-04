namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Official;
use Illuminate\Support\Facades\Storage;

class OfficialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/officials');
            $data['photo'] = str_replace('public/', 'storage/', $path);
        } else {
            // Default avatar if no photo uploaded
            $data['photo'] = 'https://ui-avatars.com/api/?name=' . urlencode($request->name) . '&background=0B2F5E&color=fff&size=200';
        }

        Official::create($data);

        return redirect()->back()->with('success', 'Aparat Desa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $official = Official::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            // Delete old photo if it's not a UI Avatar
            if ($official->photo && !str_contains($official->photo, 'ui-avatars.com')) {
                $oldPath = str_replace('storage/', 'public/', $official->photo);
                Storage::delete($oldPath);
            }
            
            $path = $request->file('photo')->store('public/officials');
            $data['photo'] = str_replace('public/', 'storage/', $path);
        }

        $official->update($data);

        return redirect()->back()->with('success', 'Data Aparat Desa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $official = Official::findOrFail($id);
        
        if ($official->photo && !str_contains($official->photo, 'ui-avatars.com')) {
            $oldPath = str_replace('storage/', 'public/', $official->photo);
            Storage::delete($oldPath);
        }

        $official->delete();

        return redirect()->back()->with('success', 'Aparat Desa berhasil dihapus.');
    }
}
