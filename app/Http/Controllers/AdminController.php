<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $year = $request->input('year', now()->year);
        $years = \App\Models\Mutasi::selectRaw('YEAR(tanggal_mutasi) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Ensure current year is in the list if no data exists yet
        if (!$years->contains(now()->year)) {
            $years->push(now()->year);
            $years = $years->sortDesc();
        }

        $totalPenduduk = \App\Models\Penduduk::where('status_dasar', 'HIDUP')->count();
        $totalKK = \App\Models\KartuKeluarga::count();
        
        // Total Mutasi for the selected year (not just this month, to match the context of the year filter)
        // OR keep "Bulan Ini" as strictly current month regardless of year filter? 
        // The label says "Bulan Ini", so it should probably stay as current month of current year for "Realtime" feel,
        // OR it should be "Bulan Ini" of the selected year? Let's stick to "Bulan Ini" = Current Real Month for the top stats,
        // as top stats are usually "Current Status".
        // HOWEVER, the user wants to see data for previous years.
        // Let's make the "Total Mutasi" card reflect the TOTAL for the SELECTED YEAR to be more useful in this context.
        // But the label says "Bulan Ini". Let's change the label in the view to "Tahun Ini" or "Total Mutasi [Year]" if we change the logic.
        // For now, let's keep the top cards as "Current State" (Active, Terdaftar, Bulan Ini, System).
        // And only filter the "Statistik Mutasi" section and the Table title.
        
        $totalMutasiBulanIni = \App\Models\Mutasi::whereMonth('tanggal_mutasi', now()->month)
            ->whereYear('tanggal_mutasi', now()->year)
            ->count();
            
        $recentMutasi = \App\Models\Mutasi::with('penduduk')
            ->latest('created_at')
            ->take(5)
            ->get();

        $statsLahir = \App\Models\Mutasi::where('jenis_mutasi', 'LAHIR')->whereYear('tanggal_mutasi', $year)->count();
        $statsMati = \App\Models\Mutasi::where('jenis_mutasi', 'MATI')->whereYear('tanggal_mutasi', $year)->count();
        $statsDatang = \App\Models\Mutasi::where('jenis_mutasi', 'DATANG')->whereYear('tanggal_mutasi', $year)->count();
        $statsPindah = \App\Models\Mutasi::where('jenis_mutasi', 'PINDAH')->whereYear('tanggal_mutasi', $year)->count();

        // Resident Stats per Dusun
        $dusuns = ['Dusun 1', 'Dusun 2', 'Dusun 3'];
        $dusunStats = [];
        $areas = ['Dusun 1' => 13, 'Dusun 2' => 22, 'Dusun 3' => 15]; // Hardcoded areas

        foreach ($dusuns as $dusun) {
            // Penduduk Stats
            $pendudukL = \App\Models\Penduduk::where('status_dasar', 'HIDUP')
                ->where('jenis_kelamin', 'L')
                ->whereHas('kartuKeluarga', function($q) use ($dusun) {
                    $q->where('dusun', $dusun);
                })->count();
                
            $pendudukP = \App\Models\Penduduk::where('status_dasar', 'HIDUP')
                ->where('jenis_kelamin', 'P')
                ->whereHas('kartuKeluarga', function($q) use ($dusun) {
                    $q->where('dusun', $dusun);
                })->count();

            // KK Stats (Based on Kepala Keluarga Gender)
            // Assuming Kepala Keluarga is identified by status_hubungan_dalam_keluarga = 'KEPALA KELUARGA'
            // AND linked to a KK in this Dusun
            $kkL = \App\Models\Penduduk::where('status_dasar', 'HIDUP')
                ->where('status_hubungan_dalam_keluarga', 'KEPALA KELUARGA')
                ->where('jenis_kelamin', 'L')
                ->whereHas('kartuKeluarga', function($q) use ($dusun) {
                    $q->where('dusun', $dusun);
                })->count();

            $kkP = \App\Models\Penduduk::where('status_dasar', 'HIDUP')
                ->where('status_hubungan_dalam_keluarga', 'KEPALA KELUARGA')
                ->where('jenis_kelamin', 'P')
                ->whereHas('kartuKeluarga', function($q) use ($dusun) {
                    $q->where('dusun', $dusun);
                })->count();

            $dusunStats[] = [
                'name' => $dusun,
                'penduduk_l' => $pendudukL,
                'penduduk_p' => $pendudukP,
                'penduduk_total' => $pendudukL + $pendudukP,
                'kk_l' => $kkL,
                'kk_p' => $kkP,
                'kk_total' => $kkL + $kkP,
                'area' => $areas[$dusun]
            ];
        }

        return view('admin.dashboard', compact(
            'totalPenduduk', 
            'totalKK', 
            'totalMutasiBulanIni', 
            'recentMutasi',
            'statsLahir',
            'statsMati',
            'statsDatang',
            'statsPindah',
            'dusunStats',
            'year',
            'years'
        ));
    }
}
