@extends('layouts.admin')

@section('header-title', 'Lapor Mutasi Penduduk')
@section('header-subtitle', 'Pilih jenis mutasi yang ingin dilaporkan.')

@section('styles')
<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(11, 47, 94, 0.1) !important;
        border-color: var(--bs-primary) !important;
        transition: all .3s ease;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .hover-bg-light:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    /* Mode Card Styles */
    .mode-card {
        border-color: #dee2e6 !important;
        transition: all 0.2s ease;
    }
    .mode-card:hover {
        border-color: #adb5bd !important;
        background-color: #f8f9fa;
    }
    .mode-card.active {
        border-color: #ffc107 !important;
        background-color: #fff9e6;
    }
    .mode-card.active.keluarga {
        border-color: #0dcaf0 !important;
        background-color: #e7fafe;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-1 shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i data-lucide="alert-triangle" class="text-danger"></i>
                <span class="fw-bold">Terdapat kesalahan pada input data:</span>
            </div>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Selection Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-2 h-100 hover-shadow cursor-pointer" onclick="showForm('lahir')">
                <div class="card-body p-4 text-center">
                    <div class="bg-info-subtle text-info rounded-circle p-3 d-inline-flex mb-3">
                        <i data-lucide="baby" style="width: 32px; height: 32px;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Kelahiran</h5>
                    <p class="text-secondary small mb-0">Lapor data kelahiran baru.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-2 h-100 hover-shadow cursor-pointer" onclick="showForm('datang')">
                <div class="card-body p-4 text-center">
                    <div class="bg-success-subtle text-success rounded-circle p-3 d-inline-flex mb-3">
                        <i data-lucide="truck" style="width: 32px; height: 32px;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Kedatangan</h5>
                    <p class="text-secondary small mb-0">Lapor warga pendatang baru.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-2 h-100 hover-shadow cursor-pointer" onclick="showForm('pindah')">
                <div class="card-body p-4 text-center">
                    <div class="bg-warning-subtle text-warning rounded-circle p-3 d-inline-flex mb-3">
                        <i data-lucide="log-out" style="width: 32px; height: 32px;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Kepindahan</h5>
                    <p class="text-secondary small mb-0">Lapor warga pindah domisili.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-2 h-100 hover-shadow cursor-pointer" onclick="showForm('mati')">
                <div class="card-body p-4 text-center">
                    <div class="bg-danger-subtle text-danger rounded-circle p-3 d-inline-flex mb-3">
                        <i data-lucide="skull" style="width: 32px; height: 32px;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Kematian</h5>
                    <p class="text-secondary small mb-0">Lapor warga meninggal dunia.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms Container -->
    <div id="formsContainer">
        
        <!-- Form Lahir -->
        <div id="form-lahir" class="mutation-form d-none">
            <form action="{{ route('mutasi.store.lahir') }}" method="POST">
                @csrf
                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-header bg-info text-white py-3 px-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                            <i data-lucide="baby" style="width: 20px;"></i> Form Lapor Kelahiran
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Nomor KK Orang Tua</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control bg-light border-0" name="no_kk" id="searchKKLahir" required maxlength="16" placeholder="Cari No KK / Nama Kepala Keluarga..." autocomplete="off">
                                    <div id="searchResultsLahir" class="position-absolute w-100 bg-white shadow-lg rounded-1 mt-1 overflow-hidden" style="z-index: 1000; display: none; border: 1px solid rgba(0,0,0,0.1);"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">NIK Bayi</label>
                                <input type="text" class="form-control bg-light border-0" name="nik" required maxlength="16" placeholder="16 digit NIK Baru">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Nama Lengkap Bayi</label>
                                <input type="text" class="form-control bg-light border-0" name="nama_lengkap" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Jenis Kelamin</label>
                                <select class="form-select bg-light border-0" name="jenis_kelamin" required>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Tempat Lahir</label>
                                <input type="text" class="form-control bg-light border-0" name="tempat_lahir" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Tanggal Lahir</label>
                                <input type="date" class="form-control bg-light border-0" name="tanggal_lahir" id="tanggal_lahir_bayi" required value="{{ date('Y-m-d') }}" onchange="calculateAge(this, 'usia_bayi')">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Usia</label>
                                <input type="text" class="form-control bg-light border-0" id="usia_bayi" readonly placeholder="0 Thn">
                            </div>

                        </div>
                    </div>
                    <div class="card-footer bg-white border-top border-light py-3 px-4 text-end">
                        <button type="submit" class="btn btn-info text-white fw-bold rounded-2 px-4">Simpan Data Kelahiran</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Form Datang -->
        <div id="form-datang" class="mutation-form d-none">
            <form action="{{ route('mutasi.store.datang') }}" method="POST" id="formDatangSubmit">
                @csrf
                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-header bg-success text-white py-3 px-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                            <i data-lucide="truck" style="width: 20px;"></i> Form Lapor Kedatangan (Satu Keluarga)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Informasi Umum -->
                        <!-- Informasi Umum -->
                        <h6 class="fw-bold text-success text-uppercase mb-3 border-bottom pb-2">Informasi Kedatangan</h6>
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Tanggal Datang</label>
                                <input type="date" class="form-control bg-light border-0" name="tanggal_datang" required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Asal Datang (Daerah Asal)</label>
                                <input type="text" class="form-control bg-light border-0" name="asal_datang" required placeholder="Contoh: Surabaya">
                            </div>
                        </div>

                        <div class="border rounded-2 p-4 mt-4">
                            <h6 class="fw-bold text-success text-uppercase mb-3 border-bottom pb-2 d-flex align-items-center gap-2">
                                <i data-lucide="home" style="width: 18px;"></i> INFORMASI KARTU KELUARGA
                            </h6>
                            <div class="row g-3 mb-5">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Nomor KK <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control bg-light border-0" name="no_kk" id="searchKK" required maxlength="16" placeholder="16 digit Nomor KK" autocomplete="off">
                                        <div id="searchResults" class="position-absolute w-100 bg-white shadow-lg rounded-1 mt-1 overflow-hidden" style="z-index: 1000; display: none; border: 1px solid rgba(0,0,0,0.1);"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Dusun Tempat Tinggal <span class="text-danger">*</span></label>
                                    <select class="form-select bg-light border-0" name="dusun" required>
                                        <option value="" selected disabled>Pilih Dusun...</option>
                                        <option value="Dusun 1">Dusun 1</option>
                                        <option value="Dusun 2">Dusun 2</option>
                                        <option value="Dusun 3">Dusun 3</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Keluarga Sejahtera</label>
                                    <select class="form-select bg-light border-0" name="status_kesejahteraan">
                                        <option value="" selected disabled>Pilih...</option>
                                        <option value="KS1">KS1</option>
                                        <option value="KS2">KS2</option>
                                        <option value="KS3">KS3</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Jenis Bangunan</label>
                                    <input type="text" class="form-control bg-light border-0" name="jenis_bangunan" placeholder="Contoh: Permanen">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Pemakaian Air</label>
                                    <input type="text" class="form-control bg-light border-0" name="pemakaian_air" placeholder="Contoh: PDAM">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Jenis Bantuan</label>
                                    <input type="text" class="form-control bg-light border-0" name="jenis_bantuan" placeholder="Contoh: PKH, BPNT">
                                </div>
                            </div>

                            <!-- Daftar Anggota -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-success text-uppercase mb-0 d-flex align-items-center gap-2">
                                    <i data-lucide="users" style="width: 18px;"></i> ANGGOTA KELUARGA
                                </h6>
                                <button type="button" class="btn btn-sm btn-success text-white rounded-2 d-flex align-items-center gap-1 px-3" onclick="addMemberRow()">
                                    <i data-lucide="plus" style="width: 16px;"></i> Tambah
                                </button>
                            </div>

                            <div id="membersContainer">
                                <!-- Rows will be added here by JS -->
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-footer bg-white border-top border-light py-3 px-4 text-end">
                        <button type="submit" class="btn btn-success text-white fw-bold rounded-2 px-4">Simpan Data Kedatangan</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Form Pindah -->
        <div id="form-pindah" class="mutation-form d-none">
            <form action="{{ route('mutasi.store.pindah') }}" method="POST" id="formPindah">
                @csrf
                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-header bg-warning text-dark py-3 px-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                            <i data-lucide="log-out" style="width: 20px;"></i> Form Lapor Kepindahan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">NIK Penduduk</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control bg-light border-0" name="nik" id="searchNIKPindah" required maxlength="16" placeholder="Cari NIK / Nama..." autocomplete="off">
                                    <div id="searchResultsPindah" class="position-absolute w-100 bg-white shadow-lg rounded-1 mt-1 overflow-hidden" style="z-index: 1000; display: none; border: 1px solid rgba(0,0,0,0.1);"></div>
                                </div>
                                <!-- Checkbox untuk Pindah Satu Keluarga -->
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="pindah_keluarga" id="pindahKeluargaCheckbox" value="1">
                                    <label class="form-check-label fw-semibold text-dark" for="pindahKeluargaCheckbox">
                                        <i data-lucide="users" style="width: 16px; height: 16px;" class="me-1 text-info"></i>
                                        Pindahkan Beserta Keluarga
                                    </label>
                                    <small class="d-block text-secondary mt-1">Centang untuk memindahkan seluruh anggota keluarga (satu KK)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Tanggal Pindah</label>
                                <input type="date" class="form-control bg-light border-0" name="tanggal_pindah" required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Tujuan Pindah</label>
                                <input type="text" class="form-control bg-light border-0" name="tujuan_pindah" required placeholder="Contoh: Jakarta">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Keterangan Tambahan</label>
                                <input type="text" class="form-control bg-light border-0" name="keterangan" placeholder="Opsional">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top border-light py-3 px-4 text-end">
                        <button type="submit" class="btn btn-warning text-dark fw-bold rounded-2 px-4">Simpan Data Kepindahan</button>
                    </div>
                </div>
            </form>
        </div>


        <!-- Form Mati -->
        <div id="form-mati" class="mutation-form d-none">
            <form action="{{ route('mutasi.store.mati') }}" method="POST">
                @csrf
                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-header bg-danger text-white py-3 px-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                            <i data-lucide="skull" style="width: 20px;"></i> Form Lapor Kematian
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">NIK Penduduk</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control bg-light border-0" name="nik" id="searchNIKMati" required maxlength="16" placeholder="Cari NIK / Nama..." autocomplete="off">
                                    <div id="searchResultsMati" class="position-absolute w-100 bg-white shadow-lg rounded-1 mt-1 overflow-hidden" style="z-index: 1000; display: none; border: 1px solid rgba(0,0,0,0.1);"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Tanggal Meninggal</label>
                                <input type="date" class="form-control bg-light border-0" name="tanggal_meninggal" required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Tempat Meninggal</label>
                                <input type="text" class="form-control bg-light border-0" name="tempat_meninggal" required placeholder="Contoh: RSUD">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Penyebab / Keterangan</label>
                                <input type="text" class="form-control bg-light border-0" name="keterangan" placeholder="Opsional">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top border-light py-3 px-4 text-end">
                        <button type="submit" class="btn btn-danger text-white fw-bold rounded-2 px-4">Simpan Data Kematian</button>
                    </div>
                </div>
            </form>
        </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Global state for KK Head
    window.currentKKHasHead = false;

    // Pindah Mode State
    window.currentPindahMode = 'perorangan';

    // Set Pindah Mode Function
    window.setPindahMode = function(mode) {
        window.currentPindahMode = mode;
        document.getElementById('modePindahInput').value = (mode === 'keluarga') ? '1' : '0';

        // Update card styles
        const peroranganCard = document.getElementById('modePeroranganCard');
        const keluargaCard = document.getElementById('modeKeluargaCard');
        
        if (mode === 'perorangan') {
            peroranganCard.classList.add('active');
            peroranganCard.classList.remove('keluarga');
            peroranganCard.querySelector('.mode-check').classList.remove('d-none');
            keluargaCard.classList.remove('active', 'keluarga');
            keluargaCard.querySelector('.mode-check').classList.add('d-none');
        } else {
            keluargaCard.classList.add('active', 'keluarga');
            keluargaCard.querySelector('.mode-check').classList.remove('d-none');
            peroranganCard.classList.remove('active');
            peroranganCard.querySelector('.mode-check').classList.add('d-none');
        }

        // Toggle input containers
        const nikContainer = document.getElementById('nikInputContainer');
        const kkContainer = document.getElementById('kkInputContainer');
        const familyPreview = document.getElementById('familyPreviewContainer');

        if (mode === 'perorangan') {
            nikContainer.classList.remove('d-none');
            kkContainer.classList.add('d-none');
            familyPreview.classList.add('d-none');
            // Clear KK input and make NIK required
            document.getElementById('searchKKPindah').value = '';
            document.getElementById('searchNIKPindah').setAttribute('required', 'required');
            document.getElementById('searchKKPindah').removeAttribute('required');
        } else {
            nikContainer.classList.add('d-none');
            kkContainer.classList.remove('d-none');
            familyPreview.classList.remove('d-none');
            // Clear NIK input and make KK required
            document.getElementById('searchNIKPindah').value = '';
            document.getElementById('searchKKPindah').setAttribute('required', 'required');
            document.getElementById('searchNIKPindah').removeAttribute('required');
        }

        // Re-init icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Define functions globally to ensure they are accessible by onclick handlers
    window.calculateAge = function(input, targetId) {
        if (!input.value) return;
        
        const birthDate = new Date(input.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        const target = document.getElementById(targetId);
        if (target) {
            target.value = age + ' Thn';
        }
    }

    window.showForm = function(type) {
        console.log('Showing form:', type);
        // Hide all forms
        document.querySelectorAll('.mutation-form').forEach(el => el.classList.add('d-none'));
        
        // Show selected form
        const selectedForm = document.getElementById('form-' + type);
        if (selectedForm) {
            selectedForm.classList.remove('d-none');
            
            // Auto-scroll on mobile only
            if (window.innerWidth < 768) {
                setTimeout(() => {
                    selectedForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100); // Slight delay to ensure DOM update
            }
            
            // Re-initialize icons if needed
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        } else {
            console.error('Form not found:', type);
        }
    }
    
    window.updateRelationshipOptions = function() {
        const selects = document.querySelectorAll('select[name="status_hubungan_dalam_keluarga[]"]');
        selects.forEach(select => {
            const currentValue = select.value;
            // Clear options
            select.innerHTML = '';
            
            // Add options based on state
            const options = [
                { value: 'KEPALA KELUARGA', label: 'KEPALA KELUARGA', disabled: window.currentKKHasHead },
                { value: 'Istri', label: 'Istri', disabled: false },
                { value: 'Anak', label: 'Anak', disabled: false },
                { value: 'Famili Lain', label: 'Famili Lain', disabled: false }
            ];
            
            options.forEach(opt => {
                if (opt.disabled) return; // Skip disabled options (remove them entirely)
                
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = opt.label;
                if (opt.value === currentValue) option.selected = true;
                select.appendChild(option);
            });
            
            // If current value was removed, select the first available
            if (window.currentKKHasHead && currentValue === 'KEPALA KELUARGA') {
                select.value = 'Istri'; // Default fallback
            }
        });
    }

    window.addMemberRow = function() {
        const container = document.getElementById('membersContainer');
        if (!container) {
            console.error('Members container not found');
            return;
        }
        
        const rowCount = container.children.length + 1;
        
        const row = document.createElement('div');
        row.className = 'member-row bg-light rounded-2 p-3 mb-3 position-relative border';
        
        // Build options HTML dynamically
        let optionsHtml = '';
        if (!window.currentKKHasHead) {
            optionsHtml += '<option value="KEPALA KELUARGA">KEPALA KELUARGA</option>';
        }
        optionsHtml += `
            <option value="Istri">Istri</option>
            <option value="Anak">Anak</option>
            <option value="Famili Lain">Famili Lain</option>
        `;

        row.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-success rounded-pill px-3 py-2">Anggota ${rowCount}</span>
                ${rowCount > 1 ? `
                <button type="button" class="btn btn-sm text-danger p-0" onclick="removeMemberRow(this)">
                    <i data-lucide="x-circle" style="width: 20px;"></i>
                </button>` : ''}
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="small fw-bold text-secondary">NIK</label>
                    <input type="text" class="form-control border-0 shadow-sm" name="nik[]" required maxlength="16" placeholder="NIK">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold text-secondary">Nama Lengkap</label>
                    <input type="text" class="form-control border-0 shadow-sm" name="nama_lengkap[]" required placeholder="Nama Lengkap">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-secondary">Jenis Kelamin</label>
                    <select class="form-select border-0 shadow-sm" name="jenis_kelamin[]" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-secondary">Tempat Lahir</label>
                    <input type="text" class="form-control border-0 shadow-sm" name="tempat_lahir[]" required>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-secondary">Tanggal Lahir</label>
                    <input type="date" class="form-control border-0 shadow-sm" name="tanggal_lahir[]" required onchange="calculateAge(this, 'usia_anggota_${rowCount}')">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-secondary">Usia</label>
                    <input type="text" class="form-control border-0 shadow-sm" id="usia_anggota_${rowCount}" readonly placeholder="0 Thn">
                </div>

                <div class="col-md-4">
                    <label class="small fw-bold text-secondary">Pendidikan</label>
                    <select class="form-select border-0 shadow-sm" name="pendidikan_terakhir[]" required>
                        <option value="SD">SD</option>
                        <option value="SLTP">SLTP</option>
                        <option value="SLTA">SLTA</option>
                        <option value="S1">S1</option>
                        <option value="TIDAK SEKOLAH">TIDAK SEKOLAH</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-secondary">Pekerjaan</label>
                    <input type="text" class="form-control border-0 shadow-sm" name="pekerjaan[]" required>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-secondary">Status Hubungan</label>
                    <select class="form-select border-0 shadow-sm" name="status_hubungan_dalam_keluarga[]" required>
                        ${optionsHtml}
                    </select>
                </div>
            </div>
        `;
        container.appendChild(row);
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    window.removeMemberRow = function(btn) {
        btn.closest('.member-row').remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        // Add initial member row
        addMemberRow();
    });

    // KK Search Logic (jQuery)
    $(document).ready(function() {
        const searchInput = $('#searchKK');
        const resultsContainer = $('#searchResults');
        let searchTimeout;

        function checkKKStatus(no_kk) {
            if (no_kk.length < 16) return;
            
            $.ajax({
                url: '{{ route("api.kk.search") }}',
                data: { q: no_kk },
                success: function(data) {
                    // Find exact match
                    const exactMatch = data.find(k => k.no_kk === no_kk);
                    if (exactMatch) {
                        // Check if it has a head
                        const hasHead = exactMatch.kepala_keluarga && exactMatch.kepala_keluarga !== '-' && exactMatch.kepala_keluarga !== 'TBD';
                        window.currentKKHasHead = !!hasHead;
                    } else {
                        // New KK, assume no head yet
                        window.currentKKHasHead = false;
                    }
                    window.updateRelationshipOptions();
                }
            });
        }

        if (searchInput.length) {
            searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                
                // Enforce numeric input
                let val = $(this).val().replace(/[^0-9]/g, '').slice(0, 16);
                $(this).val(val);

                const query = val;

                if (query.length < 3) {
                    resultsContainer.hide();
                    return;
                }

                searchTimeout = setTimeout(() => {
                    $.ajax({
                        url: '{{ route("api.kk.search") }}',
                        data: { q: query },
                        success: function(data) {
                            resultsContainer.empty();
                            if (data.length > 0) {
                                data.forEach(kk => {
                                    const item = `
                                        <div class="p-3 border-bottom cursor-pointer hover-bg-light search-item" 
                                             data-kk='${JSON.stringify(kk)}'>
                                            <div class="fw-bold text-primary">${kk.no_kk}</div>
                                            <div class="small text-secondary">Kepala: ${kk.kepala_keluarga || '-'} | Dusun: ${kk.dusun}</div>
                                        </div>
                                    `;
                                    resultsContainer.append(item);
                                });
                                resultsContainer.show();
                            } else {
                                resultsContainer.hide();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Search error:', error);
                        }
                    });
                }, 300);
            });
            
            // Handle manual input blur
            searchInput.on('blur', function() {
                setTimeout(() => {
                    checkKKStatus($(this).val());
                }, 200);
            });

            $(document).on('click', '.search-item', function() {
                const kk = $(this).data('kk');
                
                // Fill form fields
                searchInput.val(kk.no_kk);
                $('select[name="dusun"]').val(kk.dusun);
                $('select[name="status_kesejahteraan"]').val(kk.status_kesejahteraan);
                $('input[name="jenis_bangunan"]').val(kk.jenis_bangunan);
                $('input[name="pemakaian_air"]').val(kk.pemakaian_air);
                $('input[name="jenis_bantuan"]').val(kk.jenis_bantuan);
                
                // Update Head Status
                const hasHead = kk.kepala_keluarga && kk.kepala_keluarga !== '-' && kk.kepala_keluarga !== 'TBD';
                window.currentKKHasHead = !!hasHead;
                window.updateRelationshipOptions();

                // Hide results
                resultsContainer.hide();
            });

            // Close search results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.position-relative').length) {
                    resultsContainer.hide();
                    $('#searchResultsPindah').hide();
                    $('#searchResultsMati').hide();
                }
            });

            // Generic NIK Search Function
            function setupNIKSearch(inputId, resultsId) {
                const searchInput = $(inputId);
                const resultsContainer = $(resultsId);
                let searchTimeout;

                if (searchInput.length) {
                    searchInput.on('input', function() {
                        clearTimeout(searchTimeout);
                        const query = $(this).val();

                        if (query.length < 3) {
                            resultsContainer.hide();
                            return;
                        }

                        searchTimeout = setTimeout(() => {
                            $.ajax({
                                url: '{{ route("api.penduduk.search") }}',
                                data: { q: query },
                                success: function(data) {
                                    resultsContainer.empty();
                                    if (data.length > 0) {
                                        data.forEach(p => {
                                            const item = `
                                                <div class="p-3 border-bottom cursor-pointer hover-bg-light search-item-nik" 
                                                     data-nik='${JSON.stringify(p)}' data-target="${inputId}" data-results="${resultsId}">
                                                    <div class="fw-bold text-primary">${p.nama_lengkap}</div>
                                                    <div class="small text-secondary">NIK: ${p.nik} | KK: ${p.no_kk}</div>
                                                </div>
                                            `;
                                            resultsContainer.append(item);
                                        });
                                        resultsContainer.show();
                                    } else {
                                        resultsContainer.hide();
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Search error:', error);
                                }
                            });
                        }, 300);
                    });
                }
            }

            // Initialize NIK Search for Pindah and Mati
            setupNIKSearch('#searchNIKPindah', '#searchResultsPindah');
            setupNIKSearch('#searchNIKMati', '#searchResultsMati');

            // Handle NIK Selection
            $(document).on('click', '.search-item-nik', function() {
                const p = $(this).data('nik');
                const targetInput = $(this).data('target');
                const resultsContainer = $(this).data('results');
                
                $(targetInput).val(p.nik);
                $(resultsContainer).hide();
            });

            // KK Search for Pindah Family Mode
            const searchKKPindah = $('#searchKKPindah');
            const resultsKKPindah = $('#searchResultsKKPindah');
            let kkSearchTimeout;

            if (searchKKPindah.length) {
                searchKKPindah.on('input', function() {
                    clearTimeout(kkSearchTimeout);
                    
                    // Enforce numeric input
                    let val = $(this).val().replace(/[^0-9]/g, '').slice(0, 16);
                    $(this).val(val);

                    if (val.length < 3) {
                        resultsKKPindah.hide();
                        return;
                    }

                    kkSearchTimeout = setTimeout(() => {
                        $.ajax({
                            url: '{{ route("api.kk.search") }}',
                            data: { q: val },
                            success: function(data) {
                                resultsKKPindah.empty();
                                if (data.length > 0) {
                                    data.forEach(kk => {
                                        const item = `
                                            <div class="p-3 border-bottom cursor-pointer hover-bg-light search-item-kk-pindah" 
                                                 data-kk='${JSON.stringify(kk)}'>
                                                <div class="fw-bold text-primary">${kk.no_kk}</div>
                                                <div class="small text-secondary">Kepala: ${kk.kepala_keluarga || '-'} | Dusun: ${kk.dusun}</div>
                                            </div>
                                        `;
                                        resultsKKPindah.append(item);
                                    });
                                    resultsKKPindah.show();
                                } else {
                                    resultsKKPindah.hide();
                                }
                            }
                        });
                    }, 300);
                });
            }

            // Handle KK Selection for Pindah
            $(document).on('click', '.search-item-kk-pindah', function() {
                const kk = $(this).data('kk');
                searchKKPindah.val(kk.no_kk);
                resultsKKPindah.hide();

                // Load family members preview
                loadFamilyMembers(kk.no_kk);
            });

            // KK Search for Birth Form
            const searchKKLahir = $('#searchKKLahir');
            const resultsKKLahir = $('#searchResultsLahir');
            let kkLahirTimeout;

            if (searchKKLahir.length) {
                searchKKLahir.on('input', function() {
                    clearTimeout(kkLahirTimeout);
                    const query = $(this).val();

                    if (query.length < 3) {
                        resultsKKLahir.hide();
                        return;
                    }

                    kkLahirTimeout = setTimeout(() => {
                        $.ajax({
                            url: '{{ route("api.kk.search") }}',
                            data: { q: query },
                            success: function(data) {
                                resultsKKLahir.empty();
                                if (data.length > 0) {
                                    data.forEach(kk => {
                                        const item = `
                                            <div class="p-3 border-bottom cursor-pointer hover-bg-light search-item-kk-lahir" 
                                                 data-kk='${JSON.stringify(kk)}'>
                                                <div class="fw-bold text-primary">${kk.no_kk}</div>
                                                <div class="small text-secondary">Kepala: ${kk.kepala_keluarga || '-'} | Dusun: ${kk.dusun}</div>
                                            </div>
                                        `;
                                        resultsKKLahir.append(item);
                                    });
                                    resultsKKLahir.show();
                                } else {
                                    resultsKKLahir.hide();
                                }
                            }
                        });
                    }, 300);
                });
            }

            // Handle KK Selection for Birth Form
            $(document).on('click', '.search-item-kk-lahir', function() {
                const kk = $(this).data('kk');
                searchKKLahir.val(kk.no_kk);
                resultsKKLahir.hide();
            });

            // Close results when clicking outside (Added for new elements)
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.position-relative').length) {
                    resultsContainer.hide();
                    $('#searchResultsPindah').hide();
                    $('#searchResultsMati').hide();
                    resultsKKPindah.hide();
                    resultsKKLahir.hide();
                }
            });

            // Load Family Members for Preview
            function loadFamilyMembers(no_kk) {
                const familyList = $('#familyMembersList');
                familyList.html('<span class="text-secondary small">Memuat anggota keluarga...</span>');

                $.ajax({
                    url: '{{ route("api.penduduk.search") }}',
                    data: { q: no_kk, by_kk: 1 },
                    success: function(data) {
                        familyList.empty();
                        if (data.length > 0) {
                            data.forEach(p => {
                                // Format SHDK
                                let shdk = p.status_hubungan_dalam_keluarga || '-';
                                if (shdk !== '-') {
                                    const normalized = shdk.toLowerCase().trim();
                                    if (normalized === 'kepala keluarga') {
                                        shdk = 'KEPALA KELUARGA';
                                    } else {
                                        shdk = normalized.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                                    }
                                }
                                const badge = `
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                        <i data-lucide="user" style="width: 14px; height: 14px;" class="me-1"></i>
                                        ${p.nama_lengkap} (${shdk})
                                    </span>
                                `;
                                familyList.append(badge);
                            });
                            // Re-init icons
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        } else {
                            familyList.html('<span class="text-warning small">Tidak ada anggota keluarga ditemukan.</span>');
                        }
                    },
                    error: function() {
                        familyList.html('<span class="text-danger small">Gagal memuat data.</span>');
                    }
                });
            }

            // Close KK search results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#kkInputContainer').length) {
                    resultsKKPindah.hide();
                }
            });
        }
    });
</script>
@endsection
