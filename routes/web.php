<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\User\BookController as UserBookController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\Admin\LibraryVisitLogController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\UserSettingsController;

// Landing Page Route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

// Logout Route
Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::resource('books', AdminBookController::class);
        Route::resource('loans', AdminLoanController::class);
        Route::post('loans/{loan}/return', [AdminLoanController::class, 'returnBook'])->name('loans.return');
        Route::post('loans/{loan}/approve', [AdminLoanController::class, 'approve'])->name('loans.approve');
        Route::post('loans/{loan}/decline', [AdminLoanController::class, 'decline'])->name('loans.decline');
        Route::post('loans/scan-qr', [AdminLoanController::class, 'scanQr'])->name('loans.scanQr');

        // Feedback Analytics
        Route::get('/feedback', [FeedbackController::class, 'indexAdmin'])->name('feedback.index');
        Route::get('/feedback/analytics', [FeedbackController::class, 'analytics'])->name('feedback.analytics');
        Route::get('/feedback/ajax-list', [FeedbackController::class, 'ajaxList'])->name('feedback.ajax-list');

        // Library Visit Log
        Route::get('library-visits/log', [LibraryVisitLogController::class, 'index'])->name('library-visits.log');
        Route::post('library-visits/mark-entry', [LibraryVisitLogController::class, 'markEntry'])->name('library-visits.markEntry');
        Route::post('library-visits/mark-exit', [LibraryVisitLogController::class, 'markExit'])->name('library-visits.markExit');
        Route::get('library-visits/analytics', [LibraryVisitLogController::class, 'analytics'])->name('library-visits.analytics');
        Route::post('library-visits/scan', [LibraryVisitLogController::class, 'scanQr'])->name('library-visits.scan');

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    });

    // User Routes (for both students and non-students)
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/book-search', [UserBookController::class, 'search'])->name('book-search');
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');
        Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
        Route::get('/qr-code', [QrCodeController::class, 'show'])->name('qr-code');
        Route::get('/qr-code/download', [QrCodeController::class, 'download'])->name('qr-code.download');
        Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::put('/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
        Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    });
});

// Analytics Subpages
Route::prefix('admin/analytics')->name('admin.analytics.')->group(function () {
    Route::get('/visitor-statistics', [AdminDashboardController::class, 'analytics'])->name('visitor-statistics');
    Route::get('/visitor-flow', [AdminDashboardController::class, 'analytics'])->name('visitor-flow');
    Route::get('/library-inout-tracking', [\App\Http\Controllers\Admin\AnalyticsController::class, 'libraryInOutTracking'])->name('library-inout-tracking');
    Route::get('/export-reports', function() {
        return view('admin.analytics.export-reports');
    })->name('export-reports');
    Route::get('/export-users', [\App\Http\Controllers\Admin\AnalyticsController::class, 'exportUsers'])->name('export-users');
    Route::get('/export-visitors', [\App\Http\Controllers\Admin\AnalyticsController::class, 'exportVisitors'])->name('export-visitors');
    Route::get('/export-circulation', [\App\Http\Controllers\Admin\AnalyticsController::class, 'exportCirculation'])->name('export-circulation');
    Route::get('/export-inout', [\App\Http\Controllers\Admin\AnalyticsController::class, 'exportInOut'])->name('export-inout');
});
