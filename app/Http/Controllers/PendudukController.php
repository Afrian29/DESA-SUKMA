<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendudukController extends Controller
{
    public function index()
    {
        $query = \App\Models\Penduduk::with('kartuKeluarga')->where('status_dasar', 'HIDUP');

        // Filter by Dusun
        if (request('dusun')) {
            $query->whereHas('kartuKeluarga', function ($q) {
                $q->where('dusun', request('dusun'));
            });
        }

        // Filter by Pekerjaan
        if (request('pekerjaan')) {
            $query->where('pekerjaan', request('pekerjaan'));
        }

        // Filter by Specific Age
        if (request()->has('usia') && request('usia') !== null && request('usia') !== '') {
            $age = request('usia');
            $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) = ?', [$age]);
        }

        // Search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }

        // Get Data for Table
        $penduduks = $query->orderBy('no_kk')
            ->orderByRaw("CASE status_hubungan_dalam_keluarga 
                WHEN 'KEPALA KELUARGA' THEN 1 
                WHEN 'ISTRI' THEN 2 
                WHEN 'ANAK' THEN 3 
                WHEN 'MENANTU' THEN 4 
                WHEN 'ORANG TUA' THEN 5 
                WHEN 'MERTUA' THEN 6 
                WHEN 'FAMILI LAIN' THEN 7 
                ELSE 8 END")
            ->paginate(10);

        // --- STATISTICS CALCULATION (Based on current filters) ---
        $statsQuery = clone $query;

        // 1. Gender Stats
        $totalLaki = (clone $statsQuery)->where('jenis_kelamin', 'L')->count();
        $totalPerempuan = (clone $statsQuery)->where('jenis_kelamin', 'P')->count();
        $totalPenduduk = $totalLaki + $totalPerempuan;

        // 2. Age Stats (Specific Ages)
        // Group by calculated age
        $ageStats = (clone $statsQuery)
            ->select(\Illuminate\Support\Facades\DB::raw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) as age'), \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('age')
            ->orderBy('age')
            ->get()
            ->pluck('total', 'age');

        // 3. Job Stats (Top 5 + Others)
        $jobStats = (clone $statsQuery)
            ->select('pekerjaan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('pekerjaan')
            ->orderByDesc('total')
            ->get();

        // Get List of Jobs for Dropdown (Global)
        $pekerjaanList = \App\Models\Penduduk::distinct()->pluck('pekerjaan')->sort()->values();

        // Get List of Ages for Dropdown (Global)
        $ageList = \App\Models\Penduduk::select(\Illuminate\Support\Facades\DB::raw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) as age'))
            ->distinct()
            ->orderBy('age')
            ->pluck('age');

        return view('admin.penduduk.index', compact(
            'penduduks', 
            'totalLaki', 
            'totalPerempuan', 
            'totalPenduduk',
            'ageStats',
            'jobStats',
            'pekerjaanList',
            'ageList'
        ));
    }

    public function create()
    {
        $kartuKeluargas = \App\Models\KartuKeluarga::select('no_kk', 'kepala_keluarga')->get();
        return view('admin.penduduk.create', compact('kartuKeluargas'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'no_kk' => 'required|numeric|digits:16', // Removed unique validation
            'dusun' => 'required|string',
            'anggota' => 'required|array|min:1',
            'anggota.*.nik' => 'required|numeric|digits:16|unique:penduduks,nik',
            'anggota.*.nama_lengkap' => 'required|string',
            'anggota.*.jenis_kelamin' => 'required|in:L,P',
            'anggota.*.status_hubungan_dalam_keluarga' => 'required|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // 1. Find or Create Kartu Keluarga
            $kk = \App\Models\KartuKeluarga::firstOrCreate(
                ['no_kk' => $request->no_kk],
                [
                    'kepala_keluarga' => 'TBD', // Will update later if new
                    'dusun' => $request->dusun,
                    'status_kesejahteraan' => $request->status_kesejahteraan,
                    'jenis_bangunan' => $request->jenis_bangunan,
                    'pemakaian_air' => $request->pemakaian_air,
                    'jenis_bantuan' => $request->jenis_bantuan,
                ]
            );

            $namaKepalaKeluarga = '-';

            // 2. Create Anggota Keluarga
            foreach ($request->anggota as $anggotaData) {
                $anggotaData['no_kk'] = $kk->no_kk;
                \App\Models\Penduduk::create($anggotaData);

                if ($anggotaData['status_hubungan_dalam_keluarga'] === 'KEPALA KELUARGA') {
                    $namaKepalaKeluarga = $anggotaData['nama_lengkap'];
                }
            }

            // 3. Update Kepala Keluarga Name
            $kk->update(['kepala_keluarga' => $namaKepalaKeluarga]);
        });

        return redirect()->route('penduduk.index')->with('success', 'Data keluarga berhasil ditambahkan.');
    }
    public function searchKK(Request $request)
    {
        $query = $request->get('q');
        $kks = \App\Models\KartuKeluarga::where('no_kk', 'like', "%{$query}%")
            ->limit(10)
            ->get(['no_kk', 'kepala_keluarga', 'dusun', 'status_kesejahteraan', 'jenis_bangunan', 'pemakaian_air', 'jenis_bantuan']);
            
        return response()->json($kks);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $penduduks = \App\Models\Penduduk::where('nik', 'like', "%{$query}%")
            ->orWhere('nama_lengkap', 'like', "%{$query}%")
            ->limit(10)
            ->get(['nik', 'nama_lengkap', 'no_kk', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir']);
            
        return response()->json($penduduks);
    }

    public function edit($nik)
    {
        $penduduk = \App\Models\Penduduk::with('kartuKeluarga')->findOrFail($nik);
        return view('admin.penduduk.edit', compact('penduduk'));
    }

    public function update(Request $request, $nik)
    {
        $penduduk = \App\Models\Penduduk::findOrFail($nik);
        
        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:penduduks,nik,' . $nik . ',nik',
            'nama_lengkap' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tanggal_lahir' => 'required|date',
            'pendidikan_terakhir' => 'required|string',
            'pekerjaan' => 'required|string',
            'status_hubungan_dalam_keluarga' => 'required|string',
            // KK fields validation if we allow editing KK from here
            'no_kk' => 'required|numeric|digits:16',
            'dusun' => 'required|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $penduduk) {
            // Update KK info if needed (optional, depending on requirements. 
            // For now, we assume we might update the KK details linked to this person 
            // OR move them to a different KK. 
            // Given the UI, it's safer to just update the Penduduk and potentially the KK *if* it's the same KK)
            
            // Logic: If NO_KK changed, we might need to find/create new KK.
            // For simplicity in this step, let's assume we update the current KK details 
            // OR just update the Penduduk's link to a KK.
            
            // Let's stick to the pattern: Find/Create KK based on input No KK
            $kk = \App\Models\KartuKeluarga::firstOrCreate(
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

            // Update KK details if it exists (optional, but good for consistency)
            if ($kk->wasRecentlyCreated === false) {
                $kk->update([
                    'dusun' => $request->dusun,
                    'status_kesejahteraan' => $request->status_kesejahteraan,
                    'jenis_bangunan' => $request->jenis_bangunan,
                    'pemakaian_air' => $request->pemakaian_air,
                    'jenis_bantuan' => $request->jenis_bantuan,
                ]);
            }

            $penduduk->update([
                'nik' => $request->nik,
                'no_kk' => $kk->no_kk,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'pekerjaan' => $request->pekerjaan,
                'status_hubungan_dalam_keluarga' => $request->status_hubungan_dalam_keluarga,
            ]);
            
            // Update Kepala Keluarga if this person is now KK
            if ($request->status_hubungan_dalam_keluarga === 'KEPALA KELUARGA') {
                $kk->update(['kepala_keluarga' => $request->nama_lengkap]);
            }
        });

        return redirect()->route('penduduk.index')->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy($nik)
    {
        $penduduk = \App\Models\Penduduk::findOrFail($nik);
        $penduduk->delete();

        return redirect()->route('penduduk.index')->with('success', 'Data penduduk berhasil dihapus.');
    }
}
