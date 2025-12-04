@extends('layouts.admin')

@section('header-title', 'Dashboard Overview')
@section('header-subtitle', 'Statistik dan ringkasan data desa.')

@section('header-action')
<form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
    <label for="year" class="fw-bold text-secondary small">Tahun:</label>
    <select name="year" id="year" class="form-select form-select-sm rounded-2 shadow-sm border-0" style="width: 100px; cursor: pointer;" onchange="this.form.submit()">
        @foreach($years as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
    </select>
</form>
@endsection

@section('content')
<style>
    .stat-card {
        background-color: white;
        border-radius: 8px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(11, 47, 94, 0.1);
        border-color: var(--bs-primary);
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .chart-card {
        background-color: white;
        border-radius: 8px;
        padding: 2rem;
        height: 100%;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }

    /* Accents */
    .bg-primary-subtle-custom { background-color: rgba(11, 47, 94, 0.1); color: var(--bs-primary); }
    .bg-warning-subtle-custom { background-color: rgba(252, 163, 17, 0.1); color: #d97706; }
    .bg-success-subtle-custom { background-color: rgba(16, 185, 129, 0.1); color: #059669; }
    .bg-info-subtle-custom { background-color: rgba(6, 182, 212, 0.1); color: #0891b2; }
    .bg-danger-subtle-custom { background-color: rgba(220, 38, 38, 0.1); color: #dc2626; }

</style>

<div class="container-fluid p-0">
    <!-- Top Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-box bg-primary-subtle-custom">
                        <i data-lucide="users" style="width: 24px;"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success rounded-1">Aktif</span>
                </div>
                <h6 class="text-secondary mb-1">Total Penduduk</h6>
                <h3 class="fw-bold text-primary mb-0">{{ number_format($totalPenduduk) }}</h3>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-box bg-info-subtle-custom">
                        <i data-lucide="home" style="width: 24px;"></i>
                    </div>
                    <span class="badge bg-info-subtle text-info rounded-1">Terdaftar</span>
                </div>
                <h6 class="text-secondary mb-1">Kartu Keluarga</h6>
                <h3 class="fw-bold text-primary mb-0">{{ number_format($totalKK) }}</h3>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-box bg-warning-subtle-custom">
                        <i data-lucide="arrow-left-right" style="width: 24px;"></i>
                    </div>
                    <span class="badge bg-warning-subtle text-warning rounded-1">Bulan Ini</span>
                </div>
                <h6 class="text-secondary mb-1">Total Mutasi</h6>
                <h3 class="fw-bold text-primary mb-0">{{ number_format($totalMutasiBulanIni) }}</h3>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-box bg-success-subtle-custom">
                        <i data-lucide="file-check" style="width: 24px;"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success rounded-1">Online</span>
                </div>
                <h6 class="text-secondary mb-1">Sistem Status</h6>
                <h3 class="fw-bold text-primary mb-0">OK</h3>
            </div>
        </div>
    </div>

    <!-- Resident Summary Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card p-0 overflow-hidden border-0 shadow-sm">
                <div class="p-4 border-bottom bg-white">
                    <h5 class="fw-bold text-primary mb-1">Data Penduduk Desa Sukma</h5>
                    <p class="text-secondary small mb-0">Rekapitulasi data kependudukan per dusun tahun {{ $year }}</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th rowspan="2" class="px-4 py-3 text-white text-uppercase small fw-bold text-center align-middle bg-primary">No</th>
                                <th rowspan="2" class="px-4 py-3 text-white text-uppercase small fw-bold align-middle bg-primary">Dusun</th>
                                <th colspan="2" class="px-4 py-2 text-white text-uppercase small fw-bold text-center border-bottom border-white bg-primary">Penduduk</th>
                                <th rowspan="2" class="px-4 py-3 text-white text-uppercase small fw-bold text-center align-middle bg-primary">Total</th>
                                <th colspan="2" class="px-4 py-2 text-white text-uppercase small fw-bold text-center border-bottom border-white bg-primary">Kepala Keluarga</th>
                                <th rowspan="2" class="px-4 py-3 text-white text-uppercase small fw-bold text-center align-middle bg-primary">Total KK</th>
                                <th rowspan="2" class="px-4 py-3 text-white text-uppercase small fw-bold text-center align-middle bg-primary">Luas</th>
                                <th rowspan="2" class="px-4 py-3 text-white text-uppercase small fw-bold text-center align-middle bg-primary">Ket</th>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-white text-uppercase small fw-bold text-center bg-primary border-top-0">L</th>
                                <th class="px-4 py-2 text-white text-uppercase small fw-bold text-center bg-primary border-top-0">P</th>
                                <th class="px-4 py-2 text-white text-uppercase small fw-bold text-center bg-primary border-top-0">L</th>
                                <th class="px-4 py-2 text-white text-uppercase small fw-bold text-center bg-primary border-top-0">P</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $totalPendudukL = 0;
                                $totalPendudukP = 0;
                                $totalPendudukAll = 0;
                                $totalKKL = 0;
                                $totalKKP = 0;
                                $totalKKAll = 0;
                                $totalArea = 0;
                            @endphp
                            @foreach($dusunStats as $index => $stat)
                                @php
                                    $totalPendudukL += $stat['penduduk_l'];
                                    $totalPendudukP += $stat['penduduk_p'];
                                    $totalPendudukAll += $stat['penduduk_total'];
                                    $totalKKL += $stat['kk_l'];
                                    $totalKKP += $stat['kk_p'];
                                    $totalKKAll += $stat['kk_total'];
                                    $totalArea += $stat['area'];
                                @endphp
                                <tr>
                                    <td class="px-4 py-3 text-center text-secondary">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 fw-bold text-dark">{{ $stat['name'] }}</td>
                                    <td class="px-4 py-3 text-center text-secondary">{{ $stat['penduduk_l'] }}</td>
                                    <td class="px-4 py-3 text-center text-secondary">{{ $stat['penduduk_p'] }}</td>
                                    <td class="px-4 py-3 text-center fw-bold text-primary bg-primary-subtle-custom">{{ $stat['penduduk_total'] }}</td>
                                    <td class="px-4 py-3 text-center text-secondary">{{ $stat['kk_l'] }}</td>
                                    <td class="px-4 py-3 text-center text-secondary">{{ $stat['kk_p'] }}</td>
                                    <td class="px-4 py-3 text-center fw-bold text-info bg-info-subtle-custom">{{ $stat['kk_total'] }}</td>
                                    <td class="px-4 py-3 text-center text-secondary">{{ $stat['area'] }} Ha</td>
                                    <td class="px-4 py-3 text-center text-secondary">-</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-secondary-subtle border-top">
                            <tr>
                                <td colspan="2" class="px-4 py-3 fw-bold text-dark text-end">TOTAL</td>
                                <td class="px-4 py-3 fw-bold text-center text-dark">{{ $totalPendudukL }}</td>
                                <td class="px-4 py-3 fw-bold text-center text-dark">{{ $totalPendudukP }}</td>
                                <td class="px-4 py-3 fw-bold text-center text-primary">{{ $totalPendudukAll }}</td>
                                <td class="px-4 py-3 fw-bold text-center text-dark">{{ $totalKKL }}</td>
                                <td class="px-4 py-3 fw-bold text-center text-dark">{{ $totalKKP }}</td>
                                <td class="px-4 py-3 fw-bold text-center text-info">{{ $totalKKAll }}</td>
                                <td class="px-4 py-3 fw-bold text-center text-dark">{{ $totalArea }} Ha</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Quick Actions & Stats (Left) -->
        <div class="col-lg-8">
            <div class="row g-4">
                <!-- Quick Actions -->
                <div class="col-12">
                    <div class="chart-card">
                        <h5 class="fw-bold text-primary mb-4">Akses Cepat</h5>
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <a href="{{ route('penduduk.create') }}" class="btn btn-light w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-2 border hover-shadow text-decoration-none">
                                    <div class="bg-primary-subtle text-primary rounded-circle p-2">
                                        <i data-lucide="user-plus" style="width: 20px;"></i>
                                    </div>
                                    <span class="small fw-bold text-dark">Tambah Penduduk</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="{{ route('mutasi.create') }}" class="btn btn-light w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-2 border hover-shadow text-decoration-none" onclick="localStorage.setItem('mutasiTab', 'lahir')">
                                    <div class="bg-info-subtle text-info rounded-circle p-2">
                                        <i data-lucide="baby" style="width: 20px;"></i>
                                    </div>
                                    <span class="small fw-bold text-dark">Lapor Kelahiran</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="{{ route('mutasi.create') }}" class="btn btn-light w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-2 border hover-shadow text-decoration-none">
                                    <div class="bg-danger-subtle text-danger rounded-circle p-2">
                                        <i data-lucide="skull" style="width: 20px;"></i>
                                    </div>
                                    <span class="small fw-bold text-dark">Lapor Kematian</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="{{ route('mutasi.index') }}" class="btn btn-light w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-2 border hover-shadow text-decoration-none">
                                    <div class="bg-warning-subtle text-warning rounded-circle p-2">
                                        <i data-lucide="file-text" style="width: 20px;"></i>
                                    </div>
                                    <span class="small fw-bold text-dark">Laporan Mutasi</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mutation Stats -->
                <div class="col-12">
                    <div class="chart-card">
                        <h5 class="fw-bold text-primary mb-4">Statistik Mutasi Tahun {{ $year }}</h5>
                        <div class="row g-4">
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-info-subtle text-info rounded-1 p-2">
                                        <i data-lucide="baby" style="width: 20px;"></i>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-0">{{ $statsLahir }}</h4>
                                        <small class="text-secondary">Kelahiran</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-success-subtle text-success rounded-1 p-2">
                                        <i data-lucide="truck" style="width: 20px;"></i>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-0">{{ $statsDatang }}</h4>
                                        <small class="text-secondary">Kedatangan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-warning-subtle text-warning rounded-1 p-2">
                                        <i data-lucide="log-out" style="width: 20px;"></i>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-0">{{ $statsPindah }}</h4>
                                        <small class="text-secondary">Pindah</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-danger-subtle text-danger rounded-1 p-2">
                                        <i data-lucide="skull" style="width: 20px;"></i>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-0">{{ $statsMati }}</h4>
                                        <small class="text-secondary">Kematian</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity (Right) -->
        <div class="col-lg-4">
            <div class="chart-card h-100">
                <h6 class="text-secondary fw-bold mb-4">Aktivitas Terbaru</h6>
                <div class="d-flex flex-column gap-4">
                    @forelse($recentMutasi as $mutasi)
                    <div class="d-flex align-items-start gap-3">
                        @php
                            $iconClass = match($mutasi->jenis_mutasi) {
                                'LAHIR' => 'bg-info-subtle-custom',
                                'DATANG' => 'bg-success-subtle-custom',
                                'PINDAH' => 'bg-warning-subtle-custom',
                                'MATI' => 'bg-danger-subtle-custom',
                                default => 'bg-secondary-subtle',
                            };
                            $icon = match($mutasi->jenis_mutasi) {
                                'LAHIR' => 'baby',
                                'DATANG' => 'truck',
                                'PINDAH' => 'log-out',
                                'MATI' => 'skull',
                                default => 'circle',
                            };
                        @endphp
                        <div class="icon-box {{ $iconClass }}" style="width: 40px; height: 40px; min-width: 40px; margin-bottom: 0;">
                            <i data-lucide="{{ $icon }}" style="width: 18px;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small fw-bold text-primary">
                                {{ ucfirst(strtolower($mutasi->jenis_mutasi)) }}: {{ optional($mutasi->penduduk)->nama_lengkap ?? 'Unknown' }}
                            </h6>
                            <p class="text-secondary small mb-0" style="font-size: 0.8rem;">
                                {{ $mutasi->keterangan ?? 'Tidak ada keterangan' }}
                            </p>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $mutasi->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-secondary">
                        <small>Belum ada aktivitas terbaru.</small>
                    </div>
                    @endforelse
                </div>
                
                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('mutasi.index') }}" class="btn btn-light btn-sm w-100 fw-bold text-primary">Lihat Semua Aktivitas</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
