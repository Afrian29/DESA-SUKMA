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

        // Filter by Age Range (Real-time)
        // If only usia_min is provided: filter exact age
        // If both usia_min and usia_max are provided: filter age range
        $usiaMin = request('usia_min');
        $usiaMax = request('usia_max');
        
        if ($usiaMin !== null && $usiaMin !== '') {
            if ($usiaMax !== null && $usiaMax !== '') {
                // Range filter: between min and max
                $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= ?', [(int)$usiaMin])
                      ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= ?', [(int)$usiaMax]);
            } else {
                // Exact age filter: only min provided
                $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) = ?', [(int)$usiaMin]);
            }
        }

        // Search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%')
                  ->orWhere('no_kk', 'like', '%' . $search . '%');
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

        // VALIDATION: Single Head of Family Check
        $headsInInput = collect($request->anggota)->where('status_hubungan_dalam_keluarga', 'KEPALA KELUARGA')->count();
        
        if ($headsInInput > 1) {
            return back()->withInput()->withErrors(['anggota' => 'Dalam satu penambahan data, hanya boleh ada 1 Kepala Keluarga.']);
        }

        if ($headsInInput === 1) {
            // Check if KK already has a head (only if KK exists)
            $existingHead = \App\Models\Penduduk::where('no_kk', $request->no_kk)
                ->where('status_hubungan_dalam_keluarga', 'KEPALA KELUARGA')
                ->where('status_dasar', 'HIDUP')
                ->exists();

            if ($existingHead) {
                return back()->withInput()->withErrors(['no_kk' => 'Nomor KK ini sudah memiliki Kepala Keluarga. Tidak bisa menambahkan Kepala Keluarga baru.']);
            }
        }

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
            $nikKepalaKeluarga = null;

            foreach ($request->anggota as $anggotaData) {
                $anggotaData['no_kk'] = $kk->no_kk;
                \App\Models\Penduduk::create($anggotaData);

                if ($anggotaData['status_hubungan_dalam_keluarga'] === 'KEPALA KELUARGA') {
                    $nikKepalaKeluarga = $anggotaData['nik'];
                    $namaKepalaKeluarga = $anggotaData['nama_lengkap'];
                }
            }

            // 3. Update Kepala Keluarga (Link NIK & Legacy Name)
            $kk->update([
                'kepala_keluarga' => $namaKepalaKeluarga,
                'kepala_keluarga_nik' => $nikKepalaKeluarga
            ]);
        });

        return redirect()->route('penduduk.index')->with('success', 'Data keluarga berhasil ditambahkan.');
    }
    public function searchKK(Request $request)
    {
        $query = $request->get('q');
        $kks = \App\Models\KartuKeluarga::with('kepalaKeluarga')
            ->where('no_kk', 'like', "%{$query}%")
            ->orWhereHas('kepalaKeluarga', function($q) use ($query) {
                $q->where('nama_lengkap', 'like', "%{$query}%");
            })
            // Fallback search for unlinked records (legacy)
            ->orWhere(function($q) use ($query) {
                 $q->whereNull('kepala_keluarga_nik')
                   ->where('kepala_keluarga', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();
            
        // Transform for frontend
        $kks = $kks->map(function($kk) {
            return [
                'no_kk' => $kk->no_kk,
                'kepala_keluarga' => $kk->nama_kepala, // Uses accessor
                'dusun' => $kk->dusun,
                'status_kesejahteraan' => $kk->status_kesejahteraan,
                'jenis_bangunan' => $kk->jenis_bangunan,
                'pemakaian_air' => $kk->pemakaian_air,
                'jenis_bantuan' => $kk->jenis_bantuan
            ];
        });
            
        return response()->json($kks);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $byKK = $request->get('by_kk');

        if ($byKK) {
            // Search by KK number - return all family members who are still alive
            $penduduks = \App\Models\Penduduk::where('no_kk', $query)
                ->where('status_dasar', 'HIDUP')
                ->orderByRaw("
                    CASE status_hubungan_dalam_keluarga
                        WHEN 'KEPALA KELUARGA' THEN 1
                        WHEN 'ISTRI' THEN 2
                        WHEN 'ANAK' THEN 3
                        ELSE 4
                    END
                ")
                ->get(['nik', 'nama_lengkap', 'no_kk', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'status_hubungan_dalam_keluarga']);
        } else {
            // Normal search by NIK or Name
            $penduduks = \App\Models\Penduduk::where('nik', 'like', "%{$query}%")
                ->orWhere('nama_lengkap', 'like', "%{$query}%")
                ->limit(10)
                ->get(['nik', 'nama_lengkap', 'no_kk', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'status_hubungan_dalam_keluarga']);
        }
            
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
            'pendidikan_terakhir' => 'required|string',
            'pekerjaan' => 'required|string',
            'status_hubungan_dalam_keluarga' => 'required|string',
            'no_kk' => 'required|numeric|digits:16',
            'dusun' => 'required|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $penduduk, $nik) {
            // Find/Create KK based on input No KK
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

            // Update KK details if it exists
            if ($kk->wasRecentlyCreated === false) {
                $kk->update([
                    'dusun' => $request->dusun,
                    'status_kesejahteraan' => $request->status_kesejahteraan,
                    'jenis_bangunan' => $request->jenis_bangunan,
                    'pemakaian_air' => $request->pemakaian_air,
                    'jenis_bantuan' => $request->jenis_bantuan,
                ]);
            }

            // HEAD OF FAMILY SWAP LOGIC
            if ($request->status_hubungan_dalam_keluarga === 'KEPALA KELUARGA') {
                // Find existing head of family in this KK (excluding self)
                $existingHead = \App\Models\Penduduk::where('no_kk', $request->no_kk)
                    ->where('status_hubungan_dalam_keluarga', 'KEPALA KELUARGA')
                    ->where('status_dasar', 'HIDUP')
                    ->where('nik', '!=', $nik)
                    ->first();

                // If there's an existing head, demote them to 'ANGGOTA'
                if ($existingHead) {
                    $existingHead->update([
                        'status_hubungan_dalam_keluarga' => 'ANGGOTA'
                    ]);
                }

                // Update Kartu Keluarga's kepala_keluarga name
                $kk->update(['kepala_keluarga' => $request->nama_lengkap]);
            }

            // Update the penduduk data - ensure no_kk is always set
            $penduduk->update([
                'nik' => $request->nik,
                'no_kk' => $request->no_kk, // Explicitly set no_kk from request
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'pekerjaan' => $request->pekerjaan,
                'status_hubungan_dalam_keluarga' => $request->status_hubungan_dalam_keluarga,
            ]);
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
