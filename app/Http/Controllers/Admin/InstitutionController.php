namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Institution;

class InstitutionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'abbr' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
        ]);

        Institution::create($request->all());

        return redirect()->back()->with('success', 'Lembaga Desa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $institution = Institution::findOrFail($id);
        
        $request->validate([
            'abbr' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
        ]);

        $institution->update($request->all());

        return redirect()->back()->with('success', 'Lembaga Desa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $institution = Institution::findOrFail($id);
        $institution->delete();

        return redirect()->back()->with('success', 'Lembaga Desa berhasil dihapus.');
    }
}
