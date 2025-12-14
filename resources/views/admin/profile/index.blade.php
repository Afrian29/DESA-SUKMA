@extends('layouts.admin')

@section('header-title', 'Profil Desa')
@section('header-subtitle', 'Kelola informasi profil desa, aparat, lembaga, dan galeri.')

@section('content')
<style>
    .institution-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .institution-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .hover-danger:hover {
        background-color: #fee2e2 !important;
        color: #ef4444 !important;
    }
</style>
<div class="container-fluid">


    <div class="profile-tabs" id="profileTabs" role="tablist">
        <button class="profile-tab-link active" id="kades-tab" data-bs-toggle="tab" data-bs-target="#kades" type="button" role="tab">
            <i data-lucide="user" style="width: 18px;"></i> Profil & Sambutan
        </button>
        <button class="profile-tab-link" id="visimisi-tab" data-bs-toggle="tab" data-bs-target="#visimisi" type="button" role="tab">
            <i data-lucide="target" style="width: 18px;"></i> Visi & Misi
        </button>
        <button class="profile-tab-link" id="aparat-tab" data-bs-toggle="tab" data-bs-target="#aparat" type="button" role="tab">
            <i data-lucide="users" style="width: 18px;"></i> Aparat Desa
        </button>
        <button class="profile-tab-link" id="lembaga-tab" data-bs-toggle="tab" data-bs-target="#lembaga" type="button" role="tab">
            <i data-lucide="building-2" style="width: 18px;"></i> Lembaga Desa
        </button>
        <button class="profile-tab-link" id="galeri-tab" data-bs-toggle="tab" data-bs-target="#galeri" type="button" role="tab">
            <i data-lucide="image" style="width: 18px;"></i> Galeri
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-2">
        <div class="card-body p-3 p-md-4">
            <div class="tab-content" id="profileTabsContent">
                
                <!-- KADES & SAMBUTAN TAB -->
                <div class="tab-pane fade show active" id="kades" role="tabpanel">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <label class="form-label fw-bold d-block">Foto Kepala Desa</label>
                                    <div class="position-relative d-inline-block w-100" style="max-width: 250px;">
                                        <img src="{{ optional($profile)->kades_photo ? asset($profile->kades_photo) : 'https://ui-avatars.com/api/?name=Kades&background=0B2F5E&color=fff&size=200' }}" 
                                             alt="Foto Kades" 
                                             id="kades-photo-preview"
                                             class="img-thumbnail rounded-2 shadow-sm object-fit-cover w-100" 
                                             style="height: 300px;">
                                    </div>
                                    <input type="file" name="kades_photo" id="kades-photo-input" class="form-control mt-3">
                                    <small class="text-muted">Format: JPG, PNG. Max: 2MB.</small>
                                </div>
                                <script>
                                    document.getElementById('kades-photo-input').addEventListener('change', function(event) {
                                        const file = event.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                document.getElementById('kades-photo-preview').src = e.target.result;
                                            }
                                            reader.readAsDataURL(file);
                                        }
                                    });
                                </script>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Kepala Desa</label>
                                    <input type="text" name="kades_name" class="form-control" value="{{ optional($profile)->kades_name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Judul Sambutan</label>
                                    <input type="text" name="sambutan_title" class="form-control" value="{{ optional($profile)->sambutan_title }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Isi Sambutan</label>
                                    <textarea name="sambutan_content" class="form-control" rows="8" required>{{ optional($profile)->sambutan_content }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Link Video Profil (YouTube Embed URL)</label>
                                    <input type="url" name="video_url" class="form-control" value="{{ optional($profile)->video_url }}" placeholder="https://www.youtube.com/embed/...">
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                                        <i data-lucide="save" class="me-2" style="width: 18px;"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="visimisi" role="tabpanel">
                    <div class="row g-4">
                        <!-- Visi Section (Tetap sama, form Visi sendiri) -->
                        <div class="col-md-12">
                            <form action="{{ route('admin.profile.update') }}" method="POST" class="mb-5">
                                @csrf
                                <h5 class="fw-bold text-primary mb-3">Visi Desa</h5>
                                <div class="mb-3">
                                    <textarea name="visi" class="form-control" rows="3" placeholder="Masukkan Visi Desa...">{{ optional($profile)->visi }}</textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                                        <i data-lucide="save" class="me-2" style="width: 18px;"></i> Simpan Visi
                                    </button>
                                </div>
                            </form>

                            <!-- Misi Section (CRUD List) -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-primary m-0">Misi Desa</h5>
                                <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addMissionModal">
                                    <i data-lucide="plus" style="width: 16px;"></i> Tambah Misi
                                </button>
                            </div>

                            <div class="row g-3">
                                @forelse($missions as $mission)
                                <div class="col-md-6">
                                    <div class="card h-100 border shadow-sm rounded-2 p-3 position-relative">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold text-dark mb-0 w-75">{{ $mission->title }}</h6>
                                            <div class="d-flex gap-1">
                                                <!-- Edit Button -->
                                                <button class="btn btn-light btn-sm p-1" data-bs-toggle="modal" data-bs-target="#editMissionModal{{ $mission->id }}">
                                                    <i data-lucide="edit-2" style="width: 14px;"></i>
                                                </button>
                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.mission.destroy', $mission->id) }}" method="POST" onsubmit="return confirm('Hapus misi ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm p-1">
                                                        <i data-lucide="trash-2" style="width: 14px;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-0">{{ Str::limit($mission->content, 120) }}</p>
                                    </div>
                                </div>

                                <!-- Edit Modal for this Mission -->
                                <div class="modal fade" id="editMissionModal{{ $mission->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.mission.update', $mission->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Misi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Judul Misi</label>
                                                        <input type="text" name="title" class="form-control" value="{{ $mission->title }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Isi / Deskripsi</label>
                                                        <textarea name="content" class="form-control" rows="4" required>{{ $mission->content }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-4 bg-light rounded-2 border border-dashed">
                                    <p class="text-muted mb-0">Belum ada misi yang ditambahkan.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- APARAT DESA TAB -->
                <div class="tab-pane fade" id="aparat" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-primary m-0">Daftar Aparat Desa</h5>
                        <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addOfficialModal">
                            <i data-lucide="plus" style="width: 16px;"></i> Tambah Aparat
                        </button>
                    </div>
                    
                    <div class="row g-4">
                        @foreach($officials as $official)
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm rounded-2 text-center p-3 position-relative">
                                <form action="{{ route('admin.official.destroy', $official->id) }}" method="POST" class="position-absolute top-0 end-0 m-2 z-1" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle p-1" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                        <i data-lucide="x" style="width: 14px;"></i>
                                    </button>
                                </form>
                                
                                <img src="{{ $official->photo ? (str_contains($official->photo, 'http') ? $official->photo : asset($official->photo)) : '' }}" 
                                     class="rounded-circle mx-auto mb-3 object-fit-cover border border-2 border-primary" 
                                     style="width: 80px; height: 80px;" alt="{{ $official->name }}">
                                
                                <h6 class="fw-bold text-primary mb-1">{{ $official->name }}</h6>
                                <p class="text-muted small mb-2">{{ $official->position }}</p>
                                
                                <button class="btn btn-light btn-sm w-100 mt-auto" data-bs-toggle="modal" data-bs-target="#editOfficialModal{{ $official->id }}">
                                    Edit
                                </button>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editOfficialModal{{ $official->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.official.update', $official->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Aparat</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" name="name" class="form-control" value="{{ $official->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jabatan</label>
                                                <input type="text" name="position" class="form-control" value="{{ $official->position }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Foto (Opsional)</label>
                                                <input type="file" name="photo" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Urutan</label>
                                                <input type="number" name="order" class="form-control" value="{{ $official->order }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- LEMBAGA DESA TAB -->
                <div class="tab-pane fade" id="lembaga" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-primary m-0">Daftar Lembaga Desa</h5>
                        <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addInstitutionModal">
                            <i data-lucide="plus" style="width: 16px;"></i> Tambah Lembaga
                        </button>
                    </div>

                    <div class="row g-4">
                        @foreach($institutions as $institution)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border shadow-sm rounded-4 overflow-hidden position-relative institution-card">
                                <form action="{{ route('admin.institution.destroy', $institution->id) }}" method="POST" class="position-absolute top-0 end-0 m-3 z-1" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle p-0 text-muted hover-danger shadow-sm" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <i data-lucide="trash-2" style="width: 16px;"></i>
                                    </button>
                                </form>

                                <div class="card-body p-5 text-center d-flex flex-column align-items-center">
                                    {{-- Icon Removed --}}

                                    <h4 class="fw-bold text-dark mb-1" style="color: #0B2F5E !important;">{{ $institution->abbr }}</h4>
                                    <h6 class="text-secondary fw-bold text-uppercase mb-3" style="font-size: 0.8rem; letter-spacing: 0.5px;">{{ $institution->name }}</h6>
                                    
                                    <p class="text-muted small mb-4" style="line-height: 1.6;">{{ Str::limit($institution->description, 100) }}</p>
                                    
                                    <button class="btn btn-outline-primary px-4 rounded-pill fw-bold mt-auto" data-bs-toggle="modal" data-bs-target="#editInstitutionModal{{ $institution->id }}">
                                        Edit Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editInstitutionModal{{ $institution->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.institution.update', $institution->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Lembaga</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label">Singkatan (Abbr)</label>
                                                <input type="text" name="abbr" class="form-control" value="{{ $institution->abbr }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" name="name" class="form-control" value="{{ $institution->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea name="description" class="form-control" rows="3" required>{{ $institution->description }}</textarea>
                                            </div>
                                            {{-- Icon Input Removed --}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- GALERI TAB -->
                <div class="tab-pane fade" id="galeri" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-primary m-0">Galeri Kegiatan</h5>
                        <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addGalleryModal">
                            <i data-lucide="plus" style="width: 16px;"></i> Tambah Foto
                        </button>
                    </div>

                    <div class="row g-4">
                        @foreach($galleries as $gallery)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm rounded-2 overflow-hidden position-relative group-hover-card">
                                <form action="{{ route('admin.gallery.destroy', $gallery->id) }}" method="POST" class="position-absolute top-0 end-0 m-2 z-2" onsubmit="return confirm('Hapus foto ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle p-1" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                        <i data-lucide="trash-2" style="width: 16px;"></i>
                                    </button>
                                </form>

                                <div class="position-relative" style="height: 200px;">
                                    <img src="{{ asset($gallery->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $gallery->title }}">
                                    <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-white">
                                        <small class="d-block text-warning fw-bold mb-1">{{ $gallery->date->format('d M Y') }}</small>
                                        <h6 class="fw-bold mb-0 text-truncate">{{ $gallery->title }}</h6>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <p class="text-muted small mb-3 lh-sm">{{ Str::limit($gallery->description, 80) }}</p>
                                    <button class="btn btn-light btn-sm w-100" data-bs-toggle="modal" data-bs-target="#editGalleryModal{{ $gallery->id }}">
                                        Edit Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editGalleryModal{{ $gallery->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Galeri</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label">Judul Kegiatan</label>
                                                <input type="text" name="title" class="form-control" value="{{ $gallery->title }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal</label>
                                                <input type="date" name="date" class="form-control" value="{{ $gallery->date->format('Y-m-d') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea name="description" class="form-control" rows="3">{{ $gallery->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ganti Foto (Opsional)</label>
                                                <input type="file" name="image" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Add Modals -->
<!-- Add Mission Modal -->
<div class="modal fade" id="addMissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.mission.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Misi Desa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Misi</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Tata Kelola Transparan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Isi / Deskripsi</label>
                        <textarea name="content" class="form-control" rows="4" placeholder="Deskripsi singkat misi..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Official Modal -->
<div class="modal fade" id="addOfficialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.official.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Aparat Desa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="position" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto (Opsional)</label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Institution Modal -->
<div class="modal fade" id="addInstitutionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.institution.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Lembaga Desa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Singkatan (Abbr)</label>
                        <input type="text" name="abbr" class="form-control" placeholder="Contoh: BPD" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Badan Permusyawaratan Desa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    {{-- Icon Input Removed --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Gallery Modal -->
<div class="modal fade" id="addGalleryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Foto Galeri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Kegiatan</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if(session('active_tab'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var activeTab = "{{ session('active_tab') }}";
            var tabTrigger = document.querySelector('#' + activeTab + '-tab');
            if (tabTrigger) {
                var tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        });
    </script>
@endif
@endsection
