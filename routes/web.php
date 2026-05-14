<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Admin\PastorController;
use App\Http\Controllers\Admin\ServiceContentController;
use App\Http\Controllers\Admin\NewsScheduleController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\DashboardFeatureController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersembahanController;

// ========== PUBLIC ROUTES ==========
Route::get('/', [ImageController::class, 'index'])->name('welcome');
Route::get('/history', [PageController::class, 'history'])->name('history');
Route::get('/vision', [PageController::class, 'vision'])->name('vision');
Route::get('/struktur', [PageController::class, 'struktur'])->name('struktur');
Route::get('/layanan', [PageController::class, 'layanan'])->name('layanan');
Route::get('/pengumuman', [PageController::class, 'pengumuman'])->name('pengumuman');
Route::get('/pastors', [PageController::class, 'pastors'])->name('pastors');
Route::get('/gallery', [ImageController::class, 'gallery'])->name('gallery');
Route::get('/image/{id}', [ImageController::class, 'show'])->name('image.detail');
Route::get('/image/{id}/download', [ImageController::class, 'download'])->name('image.download');

// ========== AUTH ROUTES ==========
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// ========== PROTECTED ROUTES ==========
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---- Absensi Jemaat ----
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/',             [AbsensiController::class, 'index'])->name('index');
        Route::post('/',            [AbsensiController::class, 'store'])->name('store');
        Route::delete('/{absensi}', [AbsensiController::class, 'destroy'])->name('destroy');
    });

    // ---- Admin Galeri ----
    Route::prefix('admin/galeri')->name('admin.galeri.')->group(function () {
        Route::get('/create',        [ImageController::class, 'create'])->name('create');
        Route::post('/store',        [ImageController::class, 'store'])->name('store');
        Route::get('/edit/{id}',     [ImageController::class, 'edit'])->name('edit');
        Route::put('/update/{id}',   [ImageController::class, 'update'])->name('update');
        Route::delete('/delete/{id}',[ImageController::class, 'destroy'])->name('delete');
    });

    // ---- Admin Routes ----
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        // Pastors
        Route::resource('pastors', PastorController::class)->except(['show']);

        // Services
        Route::resource('services', ServiceContentController::class)->except(['show']);

        // News (Berita)
        Route::get('news',                  [NewsScheduleController::class, 'indexNews'])->name('news.index');
        Route::get('news/create',           [NewsScheduleController::class, 'createNews'])->name('news.create');
        Route::post('news/store',           [NewsScheduleController::class, 'storeNews'])->name('news.store');
        Route::get('news/{news}/edit',      [NewsScheduleController::class, 'editNews'])->name('news.edit');
        Route::put('news/{news}',           [NewsScheduleController::class, 'updateNews'])->name('news.update');
        Route::delete('news/{news}',        [NewsScheduleController::class, 'destroyNews'])->name('news.destroy');

        // ---- Jadwal Ibadah (CRUD baru) ----
        Route::resource('schedules', ScheduleController::class);
        Route::patch('schedules/{schedule}/toggle', [ScheduleController::class, 'toggleActive'])->name('schedules.toggle');

        // ---- Manajemen Absensi ----
        Route::get('absensi',                           [AbsensiController::class, 'adminIndex'])->name('absensi.index');
        Route::patch('absensi/{absensi}/approve',        [AbsensiController::class, 'approve'])->name('absensi.approve');
        Route::patch('absensi/{absensi}/reject',         [AbsensiController::class, 'reject'])->name('absensi.reject');
        Route::post('absensi/bulk-approve',              [AbsensiController::class, 'bulkApprove'])->name('absensi.bulk-approve');
    });

    // ---- Dashboard Jemaat Read-only ----
    Route::prefix('dashboard/features')->name('dashboard.features.')->group(function () {
        Route::get('pastors',  [DashboardFeatureController::class, 'pastors'])->name('pastors');
        Route::get('services', [DashboardFeatureController::class, 'services'])->name('services');
        Route::get('events',   [DashboardFeatureController::class, 'events'])->name('events');
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Profile
    Route::get('profile/edit',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile',     [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile',    [ProfileController::class, 'destroy'])->name('profile.destroy');

    });

    // ── Persembahan Online (publik + login) ──
Route::get('/persembahan', [PersembahanController::class, 'index'])->name('persembahan.index');
Route::post('/persembahan', [PersembahanController::class, 'store'])->name('persembahan.store');
Route::get('/persembahan/finish', [PersembahanController::class, 'finish'])->name('persembahan.finish');

// ── Webhook Midtrans - WAJIB exclude dari CSRF ──
Route::post('/webhook/midtrans', [PersembahanController::class, 'webhook'])->name('webhook.midtrans');

// ── Admin persembahan ──
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('persembahan', [PersembahanController::class, 'adminIndex'])->name('persembahan.index');
});

use App\Http\Controllers\RegistrasiAcaraController;

// ── Acara publik + jemaat ──
Route::get('/acara/{news}', [RegistrasiAcaraController::class, 'show'])->name('acara.show');
Route::middleware('auth')->group(function () {
    Route::post('/acara/{news}/daftar',           [RegistrasiAcaraController::class, 'store'])->name('acara.daftar');
    Route::patch('/acara/registrasi/{registrasi}/cancel', [RegistrasiAcaraController::class, 'cancel'])->name('acara.cancel');
});

// ── Admin registrasi ── (masuk ke dalam group middleware admin yang sudah ada)
// Tambahkan di dalam Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')
Route::get('acara/{news}/registrasi',              [RegistrasiAcaraController::class, 'adminIndex'])->name('acara.registrasi');
Route::patch('acara/registrasi/{registrasi}/confirm', [RegistrasiAcaraController::class, 'confirm'])->name('acara.confirm');
Route::patch('acara/registrasi/{registrasi}/reject',  [RegistrasiAcaraController::class, 'reject'])->name('acara.reject');