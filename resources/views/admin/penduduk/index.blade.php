@extends('layouts.admin')

@section('header-title', 'Data Penduduk')
@section('header-subtitle', 'Kelola data kependudukan desa.')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column gap-3 mb-4" id="toolbar-container">
        <!-- Statistics Panel (Collapsible) -->
        <div class="collapse {{ request('show_stats') ? 'show' : '' }}" id="statsPanel">
            <div class="card border-0 shadow-sm rounded-2 bg-white mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-primary m-0"><i data-lucide="bar-chart-2" style="width: 18px;" class="me-2"></i> Statistik Penduduk (Berdasarkan Filter)</h6>
                        <button type="button" class="btn-close small" data-bs-toggle="collapse" data-bs-target="#statsPanel"></button>
                    </div>
                    <div class="row g-4">
                        <!-- Gender Stats -->
                        <div class="col-md-3 border-end">
                            <small class="text-uppercase fw-bold text-secondary d-block mb-2" style="font-size: 0.7rem;">Jenis Kelamin</small>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="text-dark small">Laki-laki</span>
                                <span class="badge bg-info-subtle text-info rounded-1">{{ $totalLaki }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-dark small">Perempuan</span>
                                <span class="badge bg-danger-subtle text-danger rounded-1">{{ $totalPerempuan }}</span>
                            </div>
                            <div class="mt-2 pt-2 border-top d-flex justify-content-between">
                                <span class="fw-bold small text-dark">Total</span>
                                <span class="fw-bold text-primary">{{ $totalPenduduk }}</span>
                            </div>
                        </div>
                        <!-- Age Stats -->
                        <div class="col-md-5 border-end">
                            <small class="text-uppercase fw-bold text-secondary d-block mb-2" style="font-size: 0.7rem;">Usia (Tahun)</small>
                            <div class="row g-2 overflow-auto" style="max-height: 150px;">
                                @foreach($ageStats as $age => $count)
                                <div class="col-4">
                                    <div class="d-flex align-items-center justify-content-between border rounded px-2 py-1">
                                        <span class="text-dark small">{{ $age }} Thn</span>
                                        <span class="badge bg-light text-dark border">{{ $count }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Job Stats -->
                        <div class="col-md-4">
                            <small class="text-uppercase fw-bold text-secondary d-block mb-2" style="font-size: 0.7rem;">Pekerjaan Terbanyak</small>
                            <div class="d-flex flex-column gap-1">
                                @foreach($jobStats->take(5) as $stat)
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-dark small text-truncate" style="max-width: 180px;" title="{{ $stat->pekerjaan }}">{{ $stat->pekerjaan }}</span>
                                    <span class="badge bg-white text-dark border">{{ $stat->total }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <!-- Left Side: Summary & Stats Toggle -->
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-2 shadow-sm bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#statsPanel">
                    <i data-lucide="bar-chart-2" style="width: 18px;"></i> Statistik
                </button>
                <div class="vr opacity-25 mx-2"></div>
                <div class="d-flex align-items-center gap-3 text-secondary bg-white px-3 py-2 rounded-2 shadow-sm border">
                    <div class="d-flex align-items-center gap-2" title="Total Penduduk">
                        <i data-lucide="users" style="width: 16px;" class="text-primary"></i>
                        <span class="text-dark fw-bold">{{ $totalPenduduk }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Filters & Actions -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="position-relative">
                    <input type="text" name="search" id="search-input" class="form-control rounded-2 shadow-sm border-0 ps-5" placeholder="Cari Nama/NIK..." value="{{ request('search') }}" style="width: 200px;">
                    <i data-lucide="search" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="width: 18px;"></i>
                </div>
                
                <select name="dusun" id="dusun-filter" class="form-select rounded-2 shadow-sm border-0" style="width: 130px; cursor: pointer;">
                    <option value="">Semua Dusun</option>
                    <option value="Dusun 1" {{ request('dusun') == 'Dusun 1' ? 'selected' : '' }}>Dusun 1</option>
                    <option value="Dusun 2" {{ request('dusun') == 'Dusun 2' ? 'selected' : '' }}>Dusun 2</option>
                    <option value="Dusun 3" {{ request('dusun') == 'Dusun 3' ? 'selected' : '' }}>Dusun 3</option>
                </select>

                <select name="usia" id="usia-filter" class="form-select rounded-2 shadow-sm border-0" style="width: 130px; cursor: pointer;">
                    <option value="">Pilih Usia</option>
                    @foreach($ageList as $age)
                        <option value="{{ $age }}" {{ (string)request('usia') === (string)$age ? 'selected' : '' }}>{{ $age }} Tahun</option>
                    @endforeach
                </select>

                <select name="pekerjaan" id="pekerjaan-filter" class="form-select rounded-2 shadow-sm border-0" style="width: 140px; cursor: pointer;">
                    <option value="">Semua Pekerjaan</option>
                    @foreach($pekerjaanList as $job)
                        <option value="{{ $job }}" {{ request('pekerjaan') == $job ? 'selected' : '' }}>{{ $job }}</option>
                    @endforeach
                </select>

                <a href="{{ route('penduduk.create') }}" class="btn btn-primary d-flex align-items-center gap-2 rounded-2 px-3 shadow-sm">
                    <i data-lucide="plus" style="width: 18px;"></i> <span class="d-none d-md-inline">Tambah</span>
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-2 overflow-hidden" id="penduduk-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-danger text-white">
                        <tr>
                            <th class="py-3 text-center text-uppercase small fw-bold border-white">No. KK</th>
                            <th class="py-3 text-center text-uppercase small fw-bold border-white">NIK</th>
                            <th class="py-3 text-uppercase small fw-bold border-white">Nama Lengkap</th>
                            <th class="py-3 text-uppercase small fw-bold border-white">Tempat tanggal lahir</th>
                            <th class="py-3 text-uppercase small fw-bold border-white">Usia</th>
                            <th class="py-3 text-center text-uppercase small fw-bold border-white">Kelamin</th>
                            <!-- <th class="py-3 text-center text-uppercase small fw-bold border-white">Dusun</th> -->
                            <th class="py-3 text-uppercase small fw-bold border-white">Pekerjaan</th>
                            <th class="py-3 text-end pe-4 text-uppercase small fw-bold border-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($penduduks->groupBy('no_kk') as $no_kk => $family)
                            @foreach($family as $penduduk)
                            <tr>
                                @if($loop->first)
                                    <td rowspan="{{ $family->count() }}" class="text-center fw-bold text-dark align-middle bg-white">{{ $penduduk->no_kk }}</td>
                                @endif
                                <td class="text-primary text-center">{{ $penduduk->nik }}</td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $penduduk->nama_lengkap }}</span>
                                </td>
                                <td class="text-secondary">{{ $penduduk->tempat_lahir }}, {{ $penduduk->tanggal_lahir }}</td>
                                <td class="text-center">
                                    <span class="fw-bold text-dark">
                                        {{ \Carbon\Carbon::parse($penduduk->tanggal_lahir)->age }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $penduduk->jenis_kelamin == 'L' ? 'bg-info-subtle text-info' : 'bg-danger-subtle text-danger' }} rounded-1 px-3">
                                        {{ $penduduk->jenis_kelamin }}
                                    </span>
                                </td>
                                <td class="text-secondary">{{ $penduduk->pekerjaan }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-info rounded-circle d-flex align-items-center justify-content-center btn-detail" 
                                                style="width: 32px; height: 32px;" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailModal"
                                                data-json="{{ json_encode($penduduk) }}">
                                            <i data-lucide="eye" style="width: 14px;"></i>
                                        </button>
                                        <a href="{{ route('penduduk.edit', $penduduk->nik) }}" class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i data-lucide="edit-2" style="width: 14px;"></i>
                                        </a>
                                        <form action="{{ route('penduduk.destroy', $penduduk->nik) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i data-lucide="trash-2" style="width: 14px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                    <div class="bg-light rounded-circle p-3 mb-3">
                                        <i data-lucide="folder-open" style="width: 32px; height: 32px;" class="text-secondary"></i>
                                    </div>
                                    <p class="mb-0 text-secondary fw-bold">Belum ada data penduduk.</p>
                                    <small class="text-muted">Silakan tambahkan data baru.</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-0 bg-white py-3 px-4">
            {{ $penduduks->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-2">
            <div class="modal-header bg-primary text-white border-0 px-4 py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i data-lucide="user" style="width: 20px;"></i> Detail Penduduk <span id="modal-title-nama" class="fw-bold"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-2 h-100">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-primary text-uppercase mb-3 border-bottom pb-2 d-flex align-items-center gap-2">
                                    <i data-lucide="user-check" style="width: 16px;"></i> Data Pribadi
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">NIK</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-nik">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Nama Lengkap</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-nama">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Tempat, Tanggal Lahir</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-ttl">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Usia</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-usia">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Jenis Kelamin</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-jk">-</p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Pendidikan Terakhir</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-pendidikan">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Pekerjaan</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-pekerjaan">-</p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Hubungan Keluarga</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-hubungan">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-2 h-100">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-primary text-uppercase mb-3 border-bottom pb-2 d-flex align-items-center gap-2">
                                    <i data-lucide="home" style="width: 16px;"></i> Data Kartu Keluarga
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Nomor KK</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-kk">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Kepala Keluarga</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-kepala">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Dusun</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-dusun">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Keluarga Sejahtera</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-ks">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Jenis Bangunan</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-bangunan">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Pemakaian Air</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-air">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary text-uppercase fw-bold">Jenis Bantuan</label>
                                        <p class="fw-bold text-dark mb-0" id="modal-bantuan">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-white px-4 py-3">
                <button type="button" class="btn btn-light rounded-2 px-4 fw-bold text-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event Delegation for Detail Buttons
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.btn-detail');
            if (button) {
                const data = JSON.parse(button.getAttribute('data-json'));
                const kk = data.kartu_keluarga || {};

                // Populate Data Pribadi
                document.getElementById('modal-title-nama').textContent = ' - ' + (data.nama_lengkap || '');
                document.getElementById('modal-nik').textContent = data.nik || '-';
                document.getElementById('modal-nama').textContent = data.nama_lengkap || '-';
                document.getElementById('modal-ttl').textContent = (data.tempat_lahir || '-') + ', ' + (data.tanggal_lahir || '-');
                
                // Calculate Age
                if (data.tanggal_lahir) {
                    const dob = new Date(data.tanggal_lahir);
                    const today = new Date();
                    let age = today.getFullYear() - dob.getFullYear();
                    const m = today.getMonth() - dob.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                        age--;
                    }
                    document.getElementById('modal-usia').textContent = age;
                } else {
                    document.getElementById('modal-usia').textContent = '-';
                }

                document.getElementById('modal-jk').textContent = data.jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';

                document.getElementById('modal-pendidikan').textContent = data.pendidikan_terakhir || '-';
                document.getElementById('modal-pekerjaan').textContent = data.pekerjaan || '-';

                document.getElementById('modal-hubungan').textContent = data.status_hubungan_dalam_keluarga || '-';

                // Populate Data KK
                document.getElementById('modal-kk').textContent = data.no_kk || '-';
                document.getElementById('modal-kepala').textContent = kk.kepala_keluarga || '-';
                document.getElementById('modal-dusun').textContent = (kk.dusun || '-').replace('Dusun ', '');
                document.getElementById('modal-ks').textContent = kk.status_kesejahteraan || '-';
                document.getElementById('modal-bangunan').textContent = kk.jenis_bangunan || '-';
                document.getElementById('modal-air').textContent = kk.pemakaian_air || '-';
                document.getElementById('modal-bantuan').textContent = kk.jenis_bantuan || '-';
            }
        });

        // AJAX Filter & Search Logic
        let searchTimeout;

        function fetchData() {
            const dusun = document.getElementById('dusun-filter') ? document.getElementById('dusun-filter').value : '';
            const search = document.getElementById('search-input') ? document.getElementById('search-input').value : '';
            const usia = document.getElementById('usia-filter') ? document.getElementById('usia-filter').value : '';
            const pekerjaan = document.getElementById('pekerjaan-filter') ? document.getElementById('pekerjaan-filter').value : '';
            
            const url = new URL(window.location.href);
            
            if (dusun) url.searchParams.set('dusun', dusun); else url.searchParams.delete('dusun');
            if (search) url.searchParams.set('search', search); else url.searchParams.delete('search');
            if (usia !== '') url.searchParams.set('usia', usia); else url.searchParams.delete('usia');
            if (pekerjaan) url.searchParams.set('pekerjaan', pekerjaan); else url.searchParams.delete('pekerjaan');
            
            // Check if stats panel is open
            const statsPanel = document.getElementById('statsPanel');
            const isStatsOpen = statsPanel && statsPanel.classList.contains('show');
            if (isStatsOpen) {
                url.searchParams.set('show_stats', '1');
            } else {
                url.searchParams.delete('show_stats');
            }

            // Reset to page 1 when filtering
            url.searchParams.delete('page');

            const card = document.getElementById('penduduk-card');

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContentElement = doc.getElementById('penduduk-card');
                    const newToolbarElement = doc.getElementById('toolbar-container');
                    const toolbar = document.getElementById('toolbar-container');
                    
                    if (newContentElement && card) {
                        card.innerHTML = newContentElement.innerHTML;
                        
                        // Update Toolbar (Summary & Stats)
                        if (newToolbarElement && toolbar) {
                            toolbar.innerHTML = newToolbarElement.innerHTML;
                            
                            // Restore Stats Panel State
                            const newStatsPanel = document.getElementById('statsPanel');
                            if (isStatsOpen && newStatsPanel) {
                                newStatsPanel.classList.add('show');
                            }
                        }

                        // Re-initialize icons
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                        
                        // Update URL without reload
                        window.history.pushState({}, '', url);
                        
                        // Re-attach event listeners
                        attachListeners();
                        
                        // Restore focus to search input
                        const newSearchInput = document.getElementById('search-input');
                        if (newSearchInput) {
                            newSearchInput.focus();
                            newSearchInput.setSelectionRange(newSearchInput.value.length, newSearchInput.value.length);
                        }

                    } else {
                        console.error('Failed to parse response or find element');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function attachListeners() {
            const dusunFilter = document.getElementById('dusun-filter');
            const usiaFilter = document.getElementById('usia-filter');
            const pekerjaanFilter = document.getElementById('pekerjaan-filter');
            const searchInput = document.getElementById('search-input');

            if (dusunFilter) dusunFilter.addEventListener('change', fetchData);
            if (usiaFilter) usiaFilter.addEventListener('change', fetchData);
            if (pekerjaanFilter) pekerjaanFilter.addEventListener('change', fetchData);

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(fetchData, 300);
                });
            }
        }

        // Initial attachment
        attachListeners();
    });
</script>
@endsection
