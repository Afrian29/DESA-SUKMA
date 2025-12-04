@extends('layouts.admin')

@section('header-title', 'Tambah Data Keluarga')
@section('header-subtitle', 'Input data Kartu Keluarga dan anggotanya.')

@section('styles')
    <style>
        /* Hide number input spinners */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
        .anggota-row {
            transition: all 0.3s ease;
            border-left: 4px solid #0B2F5E; /* Accent border for each member */
        }
        .form-control, .form-select {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
        .form-label {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }
        .hover-bg-light:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12">
            
            <!-- Session Flash Messages -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-1 shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="check-circle" class="text-success"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

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

            <form action="{{ route('penduduk.store') }}" method="POST" id="bulkForm">
                @csrf

                <div class="card border-0 shadow-sm rounded-2">
                    <!-- Unified Header -->
                    <div class="card-header bg-primary text-white py-3 px-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                            <i data-lucide="file-text" style="width: 20px;"></i> Formulir Data Keluarga
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        


                        <!-- SECTION 1: Data Kartu Keluarga -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-primary text-uppercase mb-3 border-bottom pb-2 d-flex align-items-center gap-2">
                                <i data-lucide="home" style="width: 16px;"></i> Informasi Kartu Keluarga
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Nomor KK <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control bg-light border-0" name="no_kk" id="searchKK" value="{{ old('no_kk') }}" required maxlength="16" placeholder="16 digit Nomor KK" autocomplete="off">
                                        <div id="searchResults" class="position-absolute w-100 bg-white shadow-lg rounded-1 mt-1 overflow-hidden" style="z-index: 1000; display: none; border: 1px solid rgba(0,0,0,0.1);"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Dusun <span class="text-danger">*</span></label>
                                    <select class="form-select bg-light border-0" name="dusun" required>
                                        <option value="" selected disabled>Pilih Dusun...</option>
                                        <option value="Dusun 1">Dusun 1</option>
                                        <option value="Dusun 2">Dusun 2</option>
                                        <option value="Dusun 3">Dusun 3</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Keluarga Sejahtera</label>
                                    <select class="form-select bg-light border-0" name="status_kesejahteraan">
                                        <option value="" selected disabled>Pilih...</option>
                                        <option value="KS1">KS1</option>
                                        <option value="KS2">KS2</option>
                                        <option value="KS3">KS3</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Jenis Bangunan</label>
                                    <input type="text" class="form-control bg-light border-0" name="jenis_bangunan" placeholder="Contoh: Permanen">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Pemakaian Air</label>
                                    <input type="text" class="form-control bg-light border-0" name="pemakaian_air" placeholder="Contoh: PDAM">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Jenis Bantuan</label>
                                    <input type="text" class="form-control bg-light border-0" name="jenis_bantuan" placeholder="Contoh: PKH, BPNT">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Data Anggota Keluarga -->
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                <h6 class="fw-bold text-primary text-uppercase mb-0 d-flex align-items-center gap-2">
                                    <i data-lucide="users" style="width: 16px;"></i> Anggota Keluarga
                                </h6>
                                <button type="button" class="btn btn-sm btn-success rounded-2 px-3 shadow-sm d-flex align-items-center gap-1" id="tambahAnggota">
                                    <i data-lucide="plus" style="width: 14px;"></i> Tambah
                                </button>
                            </div>

                            <div id="anggotaContainer">
                                <!-- Default Row (Anggota 1) -->
                                <div class="card border-0 shadow-sm bg-light mb-3 anggota-row" data-index="0">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge bg-primary rounded-1 px-3">Anggota 1</span>
                                            <button type="button" class="btn btn-link text-danger p-0 hapus-anggota" disabled>
                                                <i data-lucide="x-circle" style="width: 20px;"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="row g-2">
                                            <!-- Baris 1 -->
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-secondary">NIK <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control border-0 bg-white" name="anggota[0][nik]" required maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control border-0 bg-white" name="anggota[0][nama_lengkap]" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-secondary">Jenis Kelamin <span class="text-danger">*</span></label>
                                                <select class="form-select border-0 bg-white" name="anggota[0][jenis_kelamin]" required>
                                                    <option value="">Pilih...</option>
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-secondary">Hubungan <span class="text-danger">*</span></label>
                                                <select class="form-select border-0 bg-white" name="anggota[0][status_hubungan_dalam_keluarga]" required>
                                                    <option value="">Pilih...</option>
                                                    <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                                                    <option value="ISTRI">ISTRI</option>
                                                    <option value="ANAK">ANAK</option>
                                                    <option value="MENANTU">MENANTU</option>
                                                    <option value="ORANG TUA">ORANG TUA</option>
                                                    <option value="MERTUA">MERTUA</option>
                                                    <option value="FAMILI LAIN">FAMILI LAIN</option>
                                                </select>
                                            </div>

                                            <!-- Baris 2 -->
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-secondary">Tempat Lahir <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control border-0 bg-white" name="anggota[0][tempat_lahir]" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-secondary">Tanggal Lahir <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control border-0 bg-white tgl-lahir-input" name="anggota[0][tanggal_lahir]" required>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label fw-bold text-secondary">Usia</label>
                                                <input type="text" class="form-control border-0 bg-light usia-input" readonly placeholder="0 Thn">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold text-secondary">Pendidikan <span class="text-danger">*</span></label>
                                                <select class="form-select border-0 bg-white" name="anggota[0][pendidikan_terakhir]" required>
                                                    <option value="">Pilih...</option>
                                                    <option value="SD">SD</option>
                                                    <option value="SLTP">SLTP</option>
                                                    <option value="SLTA">SLTA</option>
                                                    <option value="S1">S1</option>
                                                    <option value="TIDAK SEKOLAH">TIDAK SEKOLAH</option>
                                                </select>
                                            </div>

                                            <!-- Baris 3 -->
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-secondary">Pekerjaan <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control border-0 bg-white" name="anggota[0][pekerjaan]" required>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="card-footer bg-white border-top border-light py-3 px-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('penduduk.index') }}" class="btn btn-light px-4 fw-bold text-secondary rounded-2">Batal</a>
                        <button type="reset" class="btn btn-warning px-4 fw-bold text-white rounded-2 shadow-sm">
                            <i data-lucide="rotate-ccw" style="width: 18px;" class="me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-2 shadow-sm">
                            <i data-lucide="save" style="width: 18px;" class="me-1"></i> Simpan Data
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let anggotaIndex = 1;

        // KK Search Logic
        const searchInput = $('#searchKK');
        const resultsContainer = $('#searchResults');
        let searchTimeout;

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

            console.log('Searching for:', query); // Debug log

            searchTimeout = setTimeout(() => {
                $.ajax({
                    url: '{{ route("api.kk.search") }}',
                    data: { q: query },
                    success: function(data) {
                        console.log('Search results:', data); // Debug log
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
                    }
                });
            }, 300);
        });

        $(document).on('click', '.search-item', function() {
            const kk = $(this).data('kk');
            
            // Fill form fields
            searchInput.val(kk.no_kk); // Set the value of the search input itself
            $('select[name="dusun"]').val(kk.dusun);
            $('select[name="status_kesejahteraan"]').val(kk.status_kesejahteraan);
            $('input[name="jenis_bangunan"]').val(kk.jenis_bangunan);
            $('input[name="pemakaian_air"]').val(kk.pemakaian_air);
            $('input[name="jenis_bantuan"]').val(kk.jenis_bantuan);

            // Hide results
            resultsContainer.hide();
            // searchInput.val(''); // Removed clearing of input
            
            // Visual feedback
            // alert('Data Kartu Keluarga ditemukan dan formulir telah diisi.');
        });

        // Close search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.position-relative').length) {
                resultsContainer.hide();
            }
        });

        function initIcons() {
            lucide.createIcons();
        }

        $('#tambahAnggota').click(function() {
            const newRow = `
                <div class="card border-0 shadow-sm bg-light mb-3 anggota-row" data-index="${anggotaIndex}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary rounded-1 px-3">Anggota ${anggotaIndex + 1}</span>
                            <button type="button" class="btn btn-link text-danger p-0 hapus-anggota">
                                <i data-lucide="x-circle" style="width: 20px;"></i>
                            </button>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-0 bg-white" name="anggota[${anggotaIndex}][nik]" required maxlength="16">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-0 bg-white" name="anggota[${anggotaIndex}][nama_lengkap]" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select border-0 bg-white" name="anggota[${anggotaIndex}][jenis_kelamin]" required>
                                    <option value="">Pilih...</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Hubungan <span class="text-danger">*</span></label>
                                <select class="form-select border-0 bg-white" name="anggota[${anggotaIndex}][status_hubungan_dalam_keluarga]" required>
                                    <option value="">Pilih...</option>
                                    <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                                    <option value="ISTRI">ISTRI</option>
                                    <option value="ANAK">ANAK</option>
                                    <option value="MENANTU">MENANTU</option>
                                    <option value="ORANG TUA">ORANG TUA</option>
                                    <option value="MERTUA">MERTUA</option>
                                    <option value="FAMILI LAIN">FAMILI LAIN</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-0 bg-white" name="anggota[${anggotaIndex}][tempat_lahir]" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control border-0 bg-white tgl-lahir-input" name="anggota[${anggotaIndex}][tanggal_lahir]" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold text-secondary">Usia</label>
                                <input type="text" class="form-control border-0 bg-light usia-input" readonly placeholder="0 Thn">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-secondary">Pendidikan <span class="text-danger">*</span></label>
                                <select class="form-select border-0 bg-white" name="anggota[${anggotaIndex}][pendidikan_terakhir]" required>
                                    <option value="">Pilih...</option>
                                    <option value="SD">SD</option>
                                    <option value="SLTP">SLTP</option>
                                    <option value="SLTA">SLTA</option>
                                    <option value="S1">S1</option>
                                    <option value="TIDAK SEKOLAH">TIDAK SEKOLAH</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-0 bg-white" name="anggota[${anggotaIndex}][pekerjaan]" required>
                            </div>

                        </div>
                    </div>
                </div>
            `;
            
            $('#anggotaContainer').append(newRow);
            anggotaIndex++;
            initIcons();
            updateDeleteButtons();
        });

        $(document).on('click', '.hapus-anggota', function() {
            if ($('.anggota-row').length > 1) {
                $(this).closest('.anggota-row').remove();
                updateRowNumbers();
                updateDeleteButtons();
            }
        });

        function updateRowNumbers() {
            $('.anggota-row').each(function(index) {
                $(this).find('.badge').text(`Anggota ${index + 1}`);
            });
        }

        function updateDeleteButtons() {
            const count = $('.anggota-row').length;
            $('.hapus-anggota').prop('disabled', count === 1);
        }

        // Automatic Age Calculation
        $(document).on('change', '.tgl-lahir-input', function() {
            const dob = $(this).val();
            const usiaInput = $(this).closest('.row').find('.usia-input');
            
            if (dob) {
                const today = new Date();
                const birthDate = new Date(dob);
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                usiaInput.val(age + ' Tahun');
            } else {
                usiaInput.val('');
            }
        });
    });
</script>
@endsection
