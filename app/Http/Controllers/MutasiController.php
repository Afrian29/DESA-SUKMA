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

    public function exportReport(Request $request)
    {
        $request->validate([
            'export_type' => 'required|in:monthly,yearly',
            'format' => 'required|in:pdf,docx',
            'months' => 'required_if:export_type,monthly|array',
            'monthly_year' => 'required_if:export_type,monthly',
            'years' => 'required_if:export_type,yearly|array',
        ]);

        $exportType = $request->export_type;
        $format = $request->format;
        $files = [];

        try {
            if ($exportType === 'monthly') {
                $year = $request->monthly_year;
                $months = $request->months;
                
                foreach ($months as $month) {
                    try {
                        \Log::info('Generating monthly report', ['month' => $month, 'year' => $year, 'format' => $format]);
                        $file = $this->generateMonthlyReport($month, $year, $format);
                        if ($file && file_exists($file)) {
                            $files[] = $file;
                        } else {
                            \Log::error('File not generated for monthly report', ['month' => $month, 'year' => $year, 'format' => $format, 'file_path' => $file]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error generating monthly report', [
                            'month' => $month, 
                            'year' => $year, 
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e;
                    }
                }
            } else {
                $years = $request->years;
                
                foreach ($years as $year) {
                    try {
                        \Log::info('Generating yearly report', ['year' => $year, 'format' => $format]);
                        $file = $this->generateYearlyReport($year, $format);
                        if ($file && file_exists($file)) {
                            $files[] = $file;
                        } else {
                            \Log::error('File not generated for yearly report', ['year' => $year, 'format' => $format, 'file_path' => $file]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error generating yearly report', [
                            'year' => $year, 
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e;
                    }
                }
            }

            if (empty($files)) {
                return back()->with('error', 'Tidak ada file yang berhasil dibuat. Silakan cek log untuk detail.');
            }

            // If single file, download directly
            if (count($files) === 1) {
                return response()->download($files[0])->deleteFileAfterSend(true);
            }

            // Multiple files: create ZIP
            $zipPath = storage_path('app/temp/Laporan_Mutasi_' . time() . '.zip');
            $zip = new \ZipArchive();
            
            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                foreach ($files as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();

                // Delete individual files
                foreach ($files as $file) {
                    @unlink($file);
                }

                return response()->download($zipPath)->deleteFileAfterSend(true);
            }

            throw new \Exception('Failed to create ZIP file');

        } catch (\Exception $e) {
            \Log::error('Export failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Export gagal: ' . $e->getMessage() . '. Silakan cek storage/logs/laravel.log untuk detail.');
        }
    }

    private function generateMonthlyReport($month, $year, $format)
    {
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $monthName = $monthNames[(int)$month];
        
        // Query data
        $mutasis = Mutasi::with('penduduk.kartuKeluarga')
            ->whereYear('tanggal_mutasi', $year)
            ->whereMonth('tanggal_mutasi', $month)
            ->orderBy('tanggal_mutasi')
            ->get();

        // Load template
        $templatePath = storage_path('app/templates/Laporan_Mutasi_Per-bulan.docx');
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Set basic variables
        $templateProcessor->setValue('nama_bulan', $monthName);
        $templateProcessor->setValue('tahun', $year);

        // Group by type for tables - use filter() to avoid mutating original collection
        $lahir = $mutasis->filter(function($m) { return $m->jenis_mutasi === 'LAHIR'; });
        $mati = $mutasis->filter(function($m) { return $m->jenis_mutasi === 'MATI'; });
        $datang = $mutasis->filter(function($m) { return $m->jenis_mutasi === 'DATANG'; });
        $pindah = $mutasis->filter(function($m) { return $m->jenis_mutasi === 'PINDAH'; });

        // Set totals
        $templateProcessor->setValue('total_lahir', $lahir->count());
        $templateProcessor->setValue('total_meninggal', $mati->count());
        $templateProcessor->setValue('total_datang', $datang->count());
        $templateProcessor->setValue('total_pindah', $pindah->count());

        // Clone rows for each table (pass table number for unique placeholders)
        $this->fillMutasiTable($templateProcessor, 'nomor_dusun', $lahir, '');
        $this->fillMutasiTable($templateProcessor, 'nomor_dusun2', $mati, '2');
        $this->fillMutasiTable($templateProcessor, 'nomor_dusun3', $datang, '3');
        $this->fillMutasiTable($templateProcessor, 'nomor_dusun4', $pindah, '4');

        // Save to temp directory
        $fileName = $monthName . '_' . $year . '_Laporan_Mutasi';
        $tempWordPath = storage_path('app/temp/' . $fileName . '.docx');
        
        // Create temp directory if not exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        $templateProcessor->saveAs($tempWordPath);

        // Convert to PDF if needed
        if ($format === 'pdf') {
            return $this->convertToPDF($tempWordPath, $fileName);
        }

        return $tempWordPath;
    }

    private function generateYearlyReport($year, $format)
    {
        // Query data grouped by month
        $mutasis = Mutasi::selectRaw('MONTH(tanggal_mutasi) as bulan, jenis_mutasi, COUNT(*) as jumlah')
            ->whereYear('tanggal_mutasi', $year)
            ->groupBy('bulan', 'jenis_mutasi')
            ->get();

        // Organize data by month
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = [
                'LAHIR' => 0,
                'MATI' => 0,
                'DATANG' => 0,
                'PINDAH' => 0,
            ];
        }

        foreach ($mutasis as $item) {
            $monthlyData[$item->bulan][$item->jenis_mutasi] = $item->jumlah;
        }

        // Load template
        $templatePath = storage_path('app/templates/Laporan_Mutasi_Per-tahun.docx');
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Set year
        $templateProcessor->setValue('tahun', $year);

        // Set totals
        $totalLahir = array_sum(array_column($monthlyData, 'LAHIR'));
        $totalMati = array_sum(array_column($monthlyData, 'MATI'));
        $totalDatang = array_sum(array_column($monthlyData, 'DATANG'));
        $totalPindah = array_sum(array_column($monthlyData, 'PINDAH'));

        $templateProcessor->setValue('total_lahir', $totalLahir);
        $templateProcessor->setValue('total_meninggal', $totalMati);
        $templateProcessor->setValue('total_datang', $totalDatang);
        $templateProcessor->setValue('total_pindah', $totalPindah);

        // Fill monthly table
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $templateProcessor->cloneRow('nama_bulan', 12);
        
        foreach ($monthlyData as $month => $data) {
            $i = $month;
            $templateProcessor->setValue('nama_bulan#' . $i, $monthNames[$month]);
            $templateProcessor->setValue('jumlah_lahir#' . $i, $data['LAHIR']);
            $templateProcessor->setValue('jumlah_meninggal#' . $i, $data['MATI']);
            $templateProcessor->setValue('jumlah_datang#' . $i, $data['DATANG']);
            $templateProcessor->setValue('jumlah_pindah#' . $i, $data['PINDAH']);
        }

        // Save to temp directory
        $fileName = $year . '_Laporan_Mutasi';
        $tempWordPath = storage_path('app/temp/' . $fileName . '.docx');

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        $templateProcessor->saveAs($tempWordPath);

        // Convert to PDF if needed
        if ($format === 'pdf') {
            return $this->convertToPDF($tempWordPath, $fileName);
        }

        return $tempWordPath;
    }

    private function fillMutasiTable($templateProcessor, $rowKey, $mutasis, $tableNumber = '')
    {
        // Use table number suffix to differentiate placeholders between tables
        $suffix = $tableNumber ? $tableNumber : '';
        
        if ($mutasis->count() > 0) {
            $templateProcessor->cloneRow($rowKey, $mutasis->count());
            
            $i = 1;
            foreach ($mutasis as $mutasi) {
                $dusun = optional(optional($mutasi->penduduk)->kartuKeluarga)->dusun ?? '-';
                $nama = optional($mutasi->penduduk)->nama_lengkap ?? 'Data Terhapus';
                $jk = optional($mutasi->penduduk)->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
                $tanggal = $mutasi->tanggal_mutasi->format('d-m-Y');
                $lokasi = $mutasi->keterangan ?? '-';

                $templateProcessor->setValue($rowKey . '#' . $i, $dusun);
                $templateProcessor->setValue('nama_penduduk' . $suffix . '#' . $i, $nama);
                $templateProcessor->setValue('jenis_kelamin' . $suffix . '#' . $i, $jk);
                $templateProcessor->setValue('tanggal' . $suffix . '#' . $i, $tanggal);
                $templateProcessor->setValue('lokasi' . $suffix . '#' . $i, $lokasi);
                
                $i++;
            }
        } else {
            // Clone 1 row and fill with dash for empty data
            try {
                $templateProcessor->cloneRow($rowKey, 1);
                $templateProcessor->setValue($rowKey . '#1', '-');
                $templateProcessor->setValue('nama_penduduk' . $suffix . '#1', '-');
                $templateProcessor->setValue('jenis_kelamin' . $suffix . '#1', '-');
                $templateProcessor->setValue('tanggal' . $suffix . '#1', '-');
                $templateProcessor->setValue('lokasi' . $suffix . '#1', '-');
            } catch (\Exception $e) {
                // If cloneRow fails (placeholder doesn't exist), just log warning
                \Log::warning('Failed to clone row for empty data', ['rowKey' => $rowKey, 'error' => $e->getMessage()]);
            }
        }
    }

    private function convertToPDF($wordPath, $fileName)
    {
        $pdfPath = storage_path('app/temp/' . $fileName . '.pdf');

        // Load Word document
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($wordPath);

        // Configure PDF renderer
        \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
        \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);

        // Save as PDF
        $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
        $pdfWriter->save($pdfPath);

        // Delete Word file

        @unlink($wordPath);

        return $pdfPath;
    }
}
