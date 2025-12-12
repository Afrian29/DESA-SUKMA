<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\KartuKeluarga;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

class ExportController extends Controller
{
    /**
     * Export data penduduk to Word document using PHPWord template
     */
    public function exportPendudukWord()
    {
        // Get all penduduk with status_dasar = 'HIDUP'
        $penduduks = Penduduk::where('status_dasar', 'HIDUP')
            ->orderBy('no_kk')
            ->orderBy('status_hubungan_dalam_keluarga')
            ->get();

        // Calculate statistics
        $totalPenduduk = $penduduks->count();
        $totalKK = $penduduks->pluck('no_kk')->unique()->count();
        $jumlahLaki = $penduduks->where('jenis_kelamin', 'L')->count();
        $jumlahPerempuan = $penduduks->where('jenis_kelamin', 'P')->count();

        // Load template
        $templatePath = storage_path('app/templates/laporan_data_penduduk_sukma.docx');
        
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template file tidak ditemukan.');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Replace statistics placeholders
        $templateProcessor->setValue('total_penduduk', $totalPenduduk);
        $templateProcessor->setValue('kartu_keluarga', $totalKK);
        $templateProcessor->setValue('jumlah_laki', $jumlahLaki);
        $templateProcessor->setValue('jumlah_perempuan', $jumlahPerempuan);

        // Prepare table data - group by KK and only show NO.KK on first row
        $tableData = [];
        $previousKK = null;
        
        foreach ($penduduks as $penduduk) {
            $usia = Carbon::parse($penduduk->tanggal_lahir)->age;
            $tempatTanggalLahir = $penduduk->tempat_lahir . ', ' . Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y');
            $kelamin = $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';

            // Only show NO.KK on first row of each family
            $displayKK = ($penduduk->no_kk !== $previousKK) ? $penduduk->no_kk : '';
            $previousKK = $penduduk->no_kk;

            $tableData[] = [
                'no_kk' => $displayKK,
                'nik' => $penduduk->nik,
                'nama_lengkap' => $penduduk->nama_lengkap,
                'tempat_tanggal_lahir' => $tempatTanggalLahir,
                'usia' => $usia . ' Tahun',
                'kelamin' => $kelamin,
                'hubungan_keluarga' => $penduduk->status_hubungan_dalam_keluarga,
                'pekerjaan' => $penduduk->pekerjaan,
            ];
        }

        // Clone rows and set values for each penduduk
        $templateProcessor->cloneRowAndSetValues('no_kk', $tableData);

        // Generate filename with timestamp
        $filename = 'Laporan_Data_Penduduk_Desa_Sukma_' . Carbon::now()->format('Y-m-d_H-i-s') . '.docx';

        // Save to temp file and download
        $tempFile = tempnam(sys_get_temp_dir(), 'word_');
        $templateProcessor->saveAs($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }
}
