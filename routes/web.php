<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

use App\Models\VillageProfile;
use App\Models\Official;
use App\Models\Institution;
use App\Models\Gallery;
use App\Models\Penduduk;

Route::get('/', function () {
    $profile = VillageProfile::first();
    $officials = Official::orderBy('order')->get();
    $institutions = Institution::all();
    $galleries = Gallery::latest()->get();
    $totalPenduduk = Penduduk::where('status_dasar', 'HIDUP')->count();

    return view('home', compact('profile', 'officials', 'institutions', 'galleries', 'totalPenduduk'));
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/api/kk/search', [\App\Http\Controllers\PendudukController::class, 'searchKK'])->name('api.kk.search');
    Route::get('/admin/api/penduduk/search', [\App\Http\Controllers\PendudukController::class, 'search'])->name('api.penduduk.search');
    Route::resource('/admin/penduduk', \App\Http\Controllers\PendudukController::class);

    // Mutasi Routes
    Route::get('/admin/mutasi', [\App\Http\Controllers\MutasiController::class, 'index'])->name('mutasi.index');
    Route::get('/admin/mutasi/create', [\App\Http\Controllers\MutasiController::class, 'create'])->name('mutasi.create');
    Route::post('/admin/mutasi/lahir', [\App\Http\Controllers\MutasiController::class, 'storeLahir'])->name('mutasi.store.lahir');
    Route::post('/admin/mutasi/datang', [\App\Http\Controllers\MutasiController::class, 'storeDatang'])->name('mutasi.store.datang');
    Route::post('/admin/mutasi/mati', [\App\Http\Controllers\MutasiController::class, 'storeMati'])->name('mutasi.store.mati');
    Route::post('/admin/mutasi/pindah', [\App\Http\Controllers\MutasiController::class, 'storePindah'])->name('mutasi.store.pindah');
    Route::put('/admin/mutasi/{id}', [\App\Http\Controllers\MutasiController::class, 'update'])->name('mutasi.update');
    Route::delete('/admin/mutasi/{id}', [\App\Http\Controllers\MutasiController::class, 'destroy'])->name('mutasi.destroy');

    // Village Profile Routes
    Route::get('/admin/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('admin.profile.index');
    Route::post('/admin/profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');
    
    Route::post('/admin/official', [\App\Http\Controllers\Admin\OfficialController::class, 'store'])->name('admin.official.store');
    Route::put('/admin/official/{id}', [\App\Http\Controllers\Admin\OfficialController::class, 'update'])->name('admin.official.update');
    Route::delete('/admin/official/{id}', [\App\Http\Controllers\Admin\OfficialController::class, 'destroy'])->name('admin.official.destroy');

    Route::post('/admin/institution', [\App\Http\Controllers\Admin\InstitutionController::class, 'store'])->name('admin.institution.store');
    Route::put('/admin/institution/{id}', [\App\Http\Controllers\Admin\InstitutionController::class, 'update'])->name('admin.institution.update');
    Route::delete('/admin/institution/{id}', [\App\Http\Controllers\Admin\InstitutionController::class, 'destroy'])->name('admin.institution.destroy');

    Route::post('/admin/gallery', [\App\Http\Controllers\Admin\GalleryController::class, 'store'])->name('admin.gallery.store');
    Route::put('/admin/gallery/{id}', [\App\Http\Controllers\Admin\GalleryController::class, 'update'])->name('admin.gallery.update');
    Route::delete('/admin/gallery/{id}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->name('admin.gallery.destroy');
});
