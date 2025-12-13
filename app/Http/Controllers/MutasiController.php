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
        // Get filter values (null means "all")
        $month = $request->input('month');
        $year = $request->input('year');
        $jenisMutasi = $request->input('jenis_mutasi');
        
        $query = Mutasi::with('penduduk.kartuKeluarga');

        // Apply year filter only if specific year is selected
        if ($year) {
            $query->whereYear('tanggal_mutasi', $year);
        }

        // Apply month filter only if specific month is selected
        if ($month) {
            $query->whereMonth('tanggal_mutasi', $month);
        }

        if ($jenisMutasi) {
            $query->where('jenis_mutasi', $jenisMutasi);
        }

        $mutasis = $query->latest('tanggal_mutasi')
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

        // Month names for dropdown
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
            
        // Calculate Summary Stats for the selected period
        $statsQuery = Mutasi::query();
        if ($year) {
            $statsQuery->whereYear('tanggal_mutasi', $year);
        }
        if ($month) {
            $statsQuery->whereMonth('tanggal_mutasi', $month);
        }
        
        $totalMutasi = (clone $statsQuery)->count();
        $totalLahir = (clone $statsQuery)->where('jenis_mutasi', 'LAHIR')->count();
        $totalMati = (clone $statsQuery)->where('jenis_mutasi', 'MATI')->count();
        $totalDatang = (clone $statsQuery)->where('jenis_mutasi', 'DATANG')->count();
        $totalPindah = (clone $statsQuery)->where('jenis_mutasi', 'PINDAH')->count();
            
        return view('admin.mutasi.index', compact(
            'mutasis', 
            'years',
            'months',
            'month',
            'year',
            'totalMutasi',
            'totalLahir',
            'totalMati',
            'totalDatang',
            'totalPindah',
            'jenisMutasi'
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

        // VALIDATION: Single Head of Family Check
        $headsInInput = 0;
        if ($request->status_hubungan_dalam_keluarga) {
            foreach ($request->status_hubungan_dalam_keluarga as $status) {
                if ($status === 'KEPALA KELUARGA') {
                    $headsInInput++;
                }
            }
        }
        
        if ($headsInInput > 1) {
            return back()->withInput()->withErrors(['status_hubungan_dalam_keluarga' => 'Dalam satu penambahan data, hanya boleh ada 1 Kepala Keluarga.']);
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
        // Always validate NIK first
        $request->validate([
            'nik' => 'required|exists:penduduks,nik',
            'tanggal_pindah' => 'required|date',
            'tujuan_pindah' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // Check if "Pindahkan Beserta Keluarga" checkbox is checked
        $isFamilyMode = $request->has('pindah_keluarga') && $request->pindah_keluarga == '1';

        if ($isFamilyMode) {
            // FAMILY MODE: Get no_kk from the NIK, then bulk update all family members
            $movedCount = 0;

            DB::transaction(function () use ($request, &$movedCount) {
                // First, get the penduduk to find their no_kk
                $sourcePenduduk = Penduduk::findOrFail($request->nik);
                $no_kk = $sourcePenduduk->no_kk;

                // Get all living family members with the same no_kk
                $members = Penduduk::where('no_kk', $no_kk)
                    ->where('status_dasar', 'HIDUP')
                    ->get();

                foreach ($members as $penduduk) {
                    $penduduk->update(['status_dasar' => 'PINDAH']);

                    Mutasi::create([
                        'nik' => $penduduk->nik,
                        'jenis_mutasi' => 'PINDAH',
                        'tanggal_mutasi' => $request->tanggal_pindah,
                        'keterangan' => $request->tujuan_pindah . ($request->keterangan ? ' - ' . $request->keterangan : '') . ' (Pindah Satu Keluarga)',
                    ]);

                    $movedCount++;
                }
            });

            return redirect()->route('mutasi.index')->with('success', "Data kepindahan satu keluarga berhasil dicatat ({$movedCount} anggota).");
        } else {
            // INDIVIDUAL MODE: Process only the selected penduduk
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
