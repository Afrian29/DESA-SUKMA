@extends('layouts.admin')

@section('header-title', 'Data Mutasi Penduduk')
@section('header-subtitle', 'Laporan dinamika kependudukan desa.')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <!-- Summary Section -->
        <div class="d-flex flex-wrap align-items-center gap-3 gap-md-4 text-secondary bg-white px-3 py-3 px-md-4 rounded-2 shadow-sm border w-100 w-md-auto">
            <div class="d-flex align-items-center gap-2" title="Total Mutasi">
                <i data-lucide="clipboard-list" style="width: 20px;" class="text-primary"></i>
                <span class="text-dark fw-bold fs-5">{{ $totalMutasi }}</span>
            </div>
            <div class="vr opacity-25 d-none d-md-block"></div>
            <div class="d-flex align-items-center gap-2" title="Kelahiran">
                <div class="d-flex align-items-center justify-content-center bg-info-subtle text-info rounded-circle" style="width: 28px; height: 28px;">
                    <i data-lucide="baby" style="width: 16px;"></i>
                </div>
                <span class="text-dark fw-bold">{{ $totalLahir }}</span>
            </div>
            <div class="d-flex align-items-center gap-2" title="Kematian">
                <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle" style="width: 28px; height: 28px;">
                    <i data-lucide="heart-crack" style="width: 16px;"></i>
                </div>
                <span class="text-dark fw-bold">{{ $totalMati }}</span>
            </div>
            <div class="d-flex align-items-center gap-2" title="Kedatangan">
                <div class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle" style="width: 28px; height: 28px;">
                    <i data-lucide="log-in" style="width: 16px;"></i>
                </div>
                <span class="text-dark fw-bold">{{ $totalDatang }}</span>
            </div>
            <div class="d-flex align-items-center gap-2" title="Kepindahan">
                <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle" style="width: 28px; height: 28px;">
                    <i data-lucide="log-out" style="width: 16px;"></i>
                </div>
                <span class="text-dark fw-bold">{{ $totalPindah }}</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 w-100 w-md-auto justify-content-end">
            <form action="{{ route('mutasi.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <select name="year" class="form-select rounded-2 shadow-sm border-0" style="width: 100px; cursor: pointer;" onchange="this.form.submit()">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('mutasi.create') }}" class="btn btn-primary d-flex align-items-center gap-2 rounded-2 px-4 shadow-sm text-nowrap">
                <i data-lucide="plus" style="width: 18px;"></i> <span class="d-none d-sm-inline">Catat Mutasi</span><span class="d-inline d-sm-none">Catat</span>
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-2 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Penduduk</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Dusun</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Jenis Mutasi</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Tanggal</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Lokasi</th>
                            <th class="px-4 py-3 text-end pe-4 text-secondary text-uppercase small fw-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mutasis as $mutasi)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-secondary fw-bold small">{{ ($mutasis->currentPage() - 1) * $mutasis->perPage() + $loop->iteration }}.</span>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ optional($mutasi->penduduk)->nama_lengkap ?? 'Data Terhapus' }}</h6>
                                        <span class="text-secondary small">NIK: {{ $mutasi->nik }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-secondary">
                                {{ optional(optional($mutasi->penduduk)->kartuKeluarga)->dusun ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $badgeClass = match($mutasi->jenis_mutasi) {
                                        'LAHIR' => 'bg-info-subtle text-info',
                                        'DATANG' => 'bg-success-subtle text-success',
                                        'PINDAH' => 'bg-warning-subtle text-warning',
                                        'MATI' => 'bg-danger-subtle text-danger',
                                        default => 'bg-secondary-subtle text-secondary',
                                    };
                                    $icon = match($mutasi->jenis_mutasi) {
                                        'LAHIR' => 'baby',
                                        'DATANG' => 'truck',
                                        'PINDAH' => 'log-out',
                                        'MATI' => 'skull',
                                        default => 'circle',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-1 d-inline-flex align-items-center gap-1 border border-0">
                                    <i data-lucide="{{ $icon }}" style="width: 14px;"></i>
                                    {{ $mutasi->jenis_mutasi }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-medium text-dark">{{ $mutasi->tanggal_mutasi->format('d M Y') }}</span>
                                    <span class="text-secondary small">{{ $mutasi->tanggal_mutasi->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-secondary">
                                {{ $mutasi->keterangan ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($mutasi->penduduk)
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-circle d-flex align-items-center justify-content-center btn-detail" 
                                            style="width: 32px; height: 32px;" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal"
                                            data-json="{{ json_encode($mutasi->penduduk) }}">
                                        <i data-lucide="eye" style="width: 14px;"></i>
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center btn-edit" 
                                            style="width: 32px; height: 32px;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-id="{{ $mutasi->id }}"
                                            data-tanggal="{{ $mutasi->tanggal_mutasi->format('Y-m-d') }}"
                                            data-keterangan="{{ $mutasi->keterangan }}"
                                            data-nama="{{ optional($mutasi->penduduk)->nama_lengkap ?? 'Data Terhapus' }}"
                                            data-jenis="{{ $mutasi->jenis_mutasi }}">
                                        <i data-lucide="edit-2" style="width: 14px;"></i>
                                    </button>
                                    <form action="{{ route('mutasi.destroy', $mutasi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan mutasi ini? \n\nPERINGATAN: Data mutasi akan dihapus dan status penduduk akan dikembalikan seperti semula (jika berlaku).');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Batalkan Mutasi">
                                            <i data-lucide="rotate-ccw" style="width: 14px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center text-secondary">
                                    <i data-lucide="clipboard-x" style="width: 48px; height: 48px;" class="mb-3 opacity-50"></i>
                                    <p class="mb-0">Belum ada data mutasi yang tercatat pada tahun {{ $year }}.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($mutasis->hasPages())
        <div class="card-footer bg-white border-top border-light py-3 px-4">
            {{ $mutasis->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Simplified Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-2">
            <div class="modal-header bg-primary text-white border-0 px-4 py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i data-lucide="user" style="width: 20px;"></i> Detail Penduduk
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="small text-secondary text-uppercase fw-bold">Nama Lengkap</label>
                                <p class="fw-bold text-dark mb-0 fs-5" id="modal-nama">-</p>
                            </div>
                            <div class="col-12">
                                <label class="small text-secondary text-uppercase fw-bold">Tempat, Tanggal Lahir</label>
                                <p class="fw-bold text-dark mb-0" id="modal-ttl">-</p>
                            </div>
                            <div class="col-6">
                                <label class="small text-secondary text-uppercase fw-bold">Jenis Kelamin</label>
                                <p class="fw-bold text-dark mb-0" id="modal-jk">-</p>
                            </div>
                            <div class="col-6">
                                <label class="small text-secondary text-uppercase fw-bold">Kewarganegaraan</label>
                                <p class="fw-bold text-dark mb-0" id="modal-kewarganegaraan">-</p>
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-2">
            <div class="modal-header bg-primary text-white border-0 px-4 py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i data-lucide="edit-2" style="width: 20px;"></i> Edit Mutasi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-light">
                    <div class="card border-0 shadow-sm rounded-2">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="small text-secondary text-uppercase fw-bold mb-1">Penduduk</label>
                                <input type="text" class="form-control bg-light" id="edit-nama" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="small text-secondary text-uppercase fw-bold mb-1">Jenis Mutasi</label>
                                <input type="text" class="form-control bg-light" id="edit-jenis" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="edit-tanggal" class="small text-secondary text-uppercase fw-bold mb-1">Tanggal Mutasi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit-tanggal" name="tanggal_mutasi" required>
                            </div>
                            <div class="mb-0">
                                <label for="edit-keterangan" class="small text-secondary text-uppercase fw-bold mb-1">Lokasi / Keterangan</label>
                                <textarea class="form-control" id="edit-keterangan" name="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-white px-4 py-3">
                    <button type="button" class="btn btn-light rounded-2 px-4 fw-bold text-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-2 px-4 fw-bold">Simpan Perubahan</button>
                </div>
            </form>
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

                document.getElementById('modal-nama').textContent = data.nama_lengkap || '-';
                document.getElementById('modal-ttl').textContent = (data.tempat_lahir || '-') + ', ' + (data.tanggal_lahir || '-');
                document.getElementById('modal-jk').textContent = data.jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                document.getElementById('modal-kewarganegaraan').textContent = data.kewarganegaraan || 'WNI';
            }
        });

        // Event Delegation for Edit Buttons
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.btn-edit');
            if (button) {
                const id = button.getAttribute('data-id');
                const tanggal = button.getAttribute('data-tanggal');
                const keterangan = button.getAttribute('data-keterangan');
                const nama = button.getAttribute('data-nama');
                const jenis = button.getAttribute('data-jenis');

                // Set form action
                const form = document.getElementById('editForm');
                form.action = `/admin/mutasi/${id}`;

                // Populate fields
                document.getElementById('edit-nama').value = nama;
                document.getElementById('edit-jenis').value = jenis;
                document.getElementById('edit-tanggal').value = tanggal;
                document.getElementById('edit-keterangan').value = keterangan;
            }
        });
    });
</script>
@endsection
