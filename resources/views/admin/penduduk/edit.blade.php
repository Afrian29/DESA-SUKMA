@extends('layouts.admin')

@section('header-title', 'Edit Data Penduduk')
@section('header-subtitle', 'Perbarui data penduduk dan kartu keluarga.')

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
        .form-control, .form-select {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
        .form-label {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12">
            
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

            <form action="{{ route('penduduk.update', $penduduk->nik) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-header bg-primary text-white py-3 px-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                            <i data-lucide="edit-3" style="width: 20px;"></i> Edit Data Penduduk
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
                                    <input type="text" class="form-control bg-light border-0" name="no_kk" value="{{ old('no_kk', $penduduk->no_kk) }}" required maxlength="16" placeholder="16 digit Nomor KK" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Dusun <span class="text-danger">*</span></label>
                                    <select class="form-select bg-light border-0" name="dusun" required>
                                        <option value="" disabled>Pilih Dusun...</option>
                                        <option value="Dusun 1" {{ old('dusun', $penduduk->kartuKeluarga->dusun ?? '') == 'Dusun 1' ? 'selected' : '' }}>Dusun 1</option>
                                        <option value="Dusun 2" {{ old('dusun', $penduduk->kartuKeluarga->dusun ?? '') == 'Dusun 2' ? 'selected' : '' }}>Dusun 2</option>
                                        <option value="Dusun 3" {{ old('dusun', $penduduk->kartuKeluarga->dusun ?? '') == 'Dusun 3' ? 'selected' : '' }}>Dusun 3</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Keluarga Sejahtera</label>
                                    <select class="form-select bg-light border-0" name="status_kesejahteraan">
                                        <option value="" disabled>Pilih...</option>
                                        <option value="KS1" {{ old('status_kesejahteraan', $penduduk->kartuKeluarga->status_kesejahteraan ?? '') == 'KS1' ? 'selected' : '' }}>KS1</option>
                                        <option value="KS2" {{ old('status_kesejahteraan', $penduduk->kartuKeluarga->status_kesejahteraan ?? '') == 'KS2' ? 'selected' : '' }}>KS2</option>
                                        <option value="KS3" {{ old('status_kesejahteraan', $penduduk->kartuKeluarga->status_kesejahteraan ?? '') == 'KS3' ? 'selected' : '' }}>KS3</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Jenis Bangunan</label>
                                    <input type="text" class="form-control bg-light border-0" name="jenis_bangunan" value="{{ old('jenis_bangunan', $penduduk->kartuKeluarga->jenis_bangunan ?? '') }}" placeholder="Contoh: Permanen">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Pemakaian Air</label>
                                    <input type="text" class="form-control bg-light border-0" name="pemakaian_air" value="{{ old('pemakaian_air', $penduduk->kartuKeluarga->pemakaian_air ?? '') }}" placeholder="Contoh: PDAM">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Jenis Bantuan</label>
                                    <input type="text" class="form-control bg-light border-0" name="jenis_bantuan" value="{{ old('jenis_bantuan', $penduduk->kartuKeluarga->jenis_bantuan ?? '') }}" placeholder="Contoh: PKH, BPNT">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Data Pribadi -->
                        <div>
                            <h6 class="fw-bold text-primary text-uppercase mb-3 border-bottom pb-2 d-flex align-items-center gap-2">
                                <i data-lucide="user" style="width: 16px;"></i> Data Pribadi
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">NIK <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control border-0 bg-light" name="nik" value="{{ old('nik', $penduduk->nik) }}" required maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control border-0 bg-light" name="nama_lengkap" value="{{ old('nama_lengkap', $penduduk->nama_lengkap) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select border-0 bg-light" name="jenis_kelamin" required>
                                        <option value="">Pilih...</option>
                                        <option value="L" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Hubungan <span class="text-danger">*</span></label>
                                    <select class="form-select border-0 bg-light" name="status_hubungan_dalam_keluarga" required>
                                        <option value="">Pilih...</option>
                                        @foreach(['KEPALA KELUARGA', 'ISTRI', 'ANAK', 'MENANTU', 'ORANG TUA', 'MERTUA', 'FAMILI LAIN'] as $hub)
                                            <option value="{{ $hub }}" {{ old('status_hubungan_dalam_keluarga', $penduduk->status_hubungan_dalam_keluarga) == $hub ? 'selected' : '' }}>{{ $hub }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-secondary">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control border-0 bg-light" name="tempat_lahir" value="{{ old('tempat_lahir', $penduduk->tempat_lahir) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-secondary">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control border-0 bg-light tgl-lahir-input" name="tanggal_lahir" value="{{ old('tanggal_lahir', $penduduk->tanggal_lahir) }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold text-secondary">Usia</label>
                                    <input type="text" class="form-control border-0 bg-light usia-input" readonly placeholder="0 Thn">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Pendidikan <span class="text-danger">*</span></label>
                                    <select class="form-select border-0 bg-light" name="pendidikan_terakhir" required>
                                        <option value="">Pilih...</option>
                                        @foreach(['SD', 'SLTP', 'SLTA', 'S1', 'TIDAK SEKOLAH'] as $pendidikan)
                                            <option value="{{ $pendidikan }}" {{ old('pendidikan_terakhir', $penduduk->pendidikan_terakhir) == $pendidikan ? 'selected' : '' }}>{{ $pendidikan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Pekerjaan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control border-0 bg-light" name="pekerjaan" value="{{ old('pekerjaan', $penduduk->pekerjaan) }}" required>
                                </div>

                            </div>
                        </div>

                    </div>
                    
                    <div class="card-footer bg-white border-top border-light py-3 px-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('penduduk.index') }}" class="btn btn-light px-4 fw-bold text-secondary rounded-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-2 shadow-sm">
                            <i data-lucide="save" style="width: 18px;" class="me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // Automatic Age Calculation
        const tglLahirInput = document.querySelector('.tgl-lahir-input');
        const usiaInput = document.querySelector('.usia-input');

        function calculateAge() {
            const dob = tglLahirInput.value;
            if (dob) {
                const today = new Date();
                const birthDate = new Date(dob);
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                usiaInput.value = age + ' Tahun';
            } else {
                usiaInput.value = '';
            }
        }

        if (tglLahirInput) {
            tglLahirInput.addEventListener('change', calculateAge);
            // Calculate on load if value exists
            if (tglLahirInput.value) {
                calculateAge();
            }
        }
    });
</script>
@endsection
