<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Penduduk;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $mutasis = Mutasi::with('penduduk.kartuKeluarga')
            ->whereYear('tanggal_mutasi', $year)
            ->latest('tanggal_mutasi')
            ->paginate(20);

        // Get distinct years from database
        $years = Mutasi::selectRaw('YEAR(tanggal_mutasi) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Ensure current year is always available if no data exists
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }
            
        // Calculate Summary Stats for the selected year
        $totalMutasi = Mutasi::whereYear('tanggal_mutasi', $year)->count();
        $totalLahir = Mutasi::whereYear('tanggal_mutasi', $year)->where('jenis_mutasi', 'LAHIR')->count();
        $totalMati = Mutasi::whereYear('tanggal_mutasi', $year)->where('jenis_mutasi', 'MATI')->count();
        $totalDatang = Mutasi::whereYear('tanggal_mutasi', $year)->where('jenis_mutasi', 'DATANG')->count();
        $totalPindah = Mutasi::whereYear('tanggal_mutasi', $year)->where('jenis_mutasi', 'PINDAH')->count();
            
        return view('admin.mutasi.index', compact(
            'mutasis', 
            'years', 
            'year',
            'totalMutasi',
            'totalLahir',
            'totalMati',
            'totalDatang',
            'totalPindah'
        ));
    }

    public function create()
    {
        return view('admin.mutasi.create');
    }

    public function storeLahir(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:penduduks,nik',
            'nama_lengkap' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'no_kk' => 'required|exists:kartu_keluargas,no_kk',
            'no_kk' => 'required|exists:kartu_keluargas,no_kk',
        ]);

        DB::transaction(function () use ($request) {
            $penduduk = Penduduk::create([
                'nik' => $request->nik,
                'no_kk' => $request->no_kk,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => 'Islam', // Default, can be adjusted if needed
                'pendidikan_terakhir' => 'Tidak Sekolah',
                'pekerjaan' => 'Belum Bekerja',
                'status_hubungan_dalam_keluarga' => 'ANAK',
                'status_dasar' => 'HIDUP',
            ]);

            Mutasi::create([
                'nik' => $penduduk->nik,
                'jenis_mutasi' => 'LAHIR',
                'tanggal_mutasi' => $request->tanggal_lahir,
                'keterangan' => $request->tempat_lahir,
            ]);
        });

        return redirect()->route('mutasi.index')->with('success', 'Data kelahiran berhasil dicatat.');
    }

    public function storeDatang(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|numeric|digits:16',
            'dusun' => 'required|string',
            'status_kesejahteraan' => 'nullable|string',
            'jenis_bangunan' => 'nullable|string',
            'pemakaian_air' => 'nullable|string',
            'jenis_bantuan' => 'nullable|string',
            'tanggal_datang' => 'required|date',
            'asal_datang' => 'required|string',
            'nik.*' => 'required|numeric|digits:16|unique:penduduks,nik',
            'nama_lengkap.*' => 'required|string',
            'jenis_kelamin.*' => 'required|in:L,P',
            'tempat_lahir.*' => 'required|string',
            'tanggal_lahir.*' => 'required|date',

            'pendidikan_terakhir.*' => 'required|string',
            'pekerjaan.*' => 'required|string',
            'status_hubungan_dalam_keluarga.*' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            $kk = KartuKeluarga::firstOrCreate(
                ['no_kk' => $request->no_kk],
                [
                    'kepala_keluarga' => 'TBD',
                    'dusun' => $request->dusun,
                    'status_kesejahteraan' => $request->status_kesejahteraan,
                    'jenis_bangunan' => $request->jenis_bangunan,
                    'pemakaian_air' => $request->pemakaian_air,
                    'jenis_bantuan' => $request->jenis_bantuan,
                ]
            );
            
            // Loop through each member
            foreach ($request->nik as $index => $nik) {
                $penduduk = Penduduk::create([
                    'nik' => $nik,
                    'no_kk' => $kk->no_kk,
                    'nama_lengkap' => $request->nama_lengkap[$index],
                    'jenis_kelamin' => $request->jenis_kelamin[$index],
                    'tempat_lahir' => $request->tempat_lahir[$index],
                    'tanggal_lahir' => $request->tanggal_lahir[$index],

                    'pendidikan_terakhir' => $request->pendidikan_terakhir[$index],
                    'pekerjaan' => $request->pekerjaan[$index],
                    'status_hubungan_dalam_keluarga' => $request->status_hubungan_dalam_keluarga[$index],
                    'status_dasar' => 'HIDUP',
                ]);
                
                if ($request->status_hubungan_dalam_keluarga[$index] === 'KEPALA KELUARGA') {
                    $kk->update(['kepala_keluarga' => $request->nama_lengkap[$index]]);
                }

                Mutasi::create([
                    'nik' => $penduduk->nik,
                    'jenis_mutasi' => 'DATANG',
                    'tanggal_mutasi' => $request->tanggal_datang,
                    'keterangan' => $request->asal_datang,
                ]);
            }
        });

        return redirect()->route('mutasi.index')->with('success', 'Data pendatang berhasil dicatat.');
    }

    public function storeMati(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:penduduks,nik',
            'tanggal_meninggal' => 'required|date',
            'tempat_meninggal' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $penduduk = Penduduk::findOrFail($request->nik);

            // AUTOMATED HEAD OF HOUSEHOLD REPLACEMENT
            if ($penduduk->status_hubungan_dalam_keluarga === 'KEPALA KELUARGA') {
                // 1. Try to find Wife (ISTRI)
                $replacement = Penduduk::where('no_kk', $penduduk->no_kk)
                    ->where('nik', '!=', $penduduk->nik)
                    ->where('status_dasar', 'HIDUP')
                    ->where('status_hubungan_dalam_keluarga', 'ISTRI')
                    ->first();

                // 2. If no Wife, find Oldest Child (ANAK)
                if (!$replacement) {
                    $replacement = Penduduk::where('no_kk', $penduduk->no_kk)
                        ->where('nik', '!=', $penduduk->nik)
                        ->where('status_dasar', 'HIDUP')
                        ->where('status_hubungan_dalam_keluarga', 'ANAK')
                        ->orderBy('tanggal_lahir', 'asc') // Oldest first
                        ->first();
                }

                // 3. Promote Replacement
                if ($replacement) {
                    $replacement->update(['status_hubungan_dalam_keluarga' => 'KEPALA KELUARGA']);
                    
                    // Update Kartu Keluarga Record
                    $kk = KartuKeluarga::where('no_kk', $penduduk->no_kk)->first();
                    if ($kk) {
                        $kk->update(['kepala_keluarga' => $replacement->nama_lengkap]);
                    }
                }
            }

            $penduduk->update(['status_dasar' => 'MATI']);

            Mutasi::create([
                'nik' => $penduduk->nik,
                'jenis_mutasi' => 'MATI',
                'tanggal_mutasi' => $request->tanggal_meninggal,
                'keterangan' => $request->tempat_meninggal . ($request->keterangan ? ' - ' . $request->keterangan : ''),
            ]);
        });

        return redirect()->route('mutasi.index')->with('success', 'Data kematian berhasil dicatat.');
    }

    public function storePindah(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:penduduks,nik',
            'tanggal_pindah' => 'required|date',
            'tujuan_pindah' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $penduduk = Penduduk::findOrFail($request->nik);
            $penduduk->update(['status_dasar' => 'PINDAH']);

            Mutasi::create([
                'nik' => $penduduk->nik,
                'jenis_mutasi' => 'PINDAH',
                'tanggal_mutasi' => $request->tanggal_pindah,
                'keterangan' => $request->tujuan_pindah . ($request->keterangan ? ' - ' . $request->keterangan : ''),
            ]);
        });

        return redirect()->route('mutasi.index')->with('success', 'Data kepindahan berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_mutasi' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $mutasi = Mutasi::findOrFail($id);
        $mutasi->update([
            'tanggal_mutasi' => $request->tanggal_mutasi,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('mutasi.index')->with('success', 'Data mutasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $mutasi = Mutasi::findOrFail($id);
            $penduduk = Penduduk::find($mutasi->nik);

            // Restore Penduduk status if Mutasi was MATI or PINDAH
            if (in_array($mutasi->jenis_mutasi, ['MATI', 'PINDAH']) && $penduduk) {
                $penduduk->update(['status_dasar' => 'HIDUP']);
            }

            $mutasi->delete();
        });

        return redirect()->route('mutasi.index')->with('success', 'Data mutasi berhasil dihapus.');
    }
}
