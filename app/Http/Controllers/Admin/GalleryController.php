namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/gallery');
            $data['image'] = str_replace('public/', 'storage/', $path);
        }

        Gallery::create($data);

        return redirect()->back()->with('success', 'Foto Galeri berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $oldPath = str_replace('storage/', 'public/', $gallery->image);
            Storage::delete($oldPath);
            
            $path = $request->file('image')->store('public/gallery');
            $data['image'] = str_replace('public/', 'storage/', $path);
        }

        $gallery->update($data);

        return redirect()->back()->with('success', 'Foto Galeri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        
        $oldPath = str_replace('storage/', 'public/', $gallery->image);
        Storage::delete($oldPath);

        $gallery->delete();

        return redirect()->back()->with('success', 'Foto Galeri berhasil dihapus.');
    }
}
