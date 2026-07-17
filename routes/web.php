<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\FeeCategoryController;
use App\Http\Controllers\Admin\FeeStructureController;
use App\Http\Controllers\Admin\PaymentReportController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\FeeController;
use App\Http\Controllers\Student\ReceiptController;
use App\Http\Controllers\Payment\RazorpayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Student Management
    Route::get('/students/import', [StudentController::class, 'importForm'])->name('students.import');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import.process');
    Route::resource('students', StudentController::class);

    // Class Management
    Route::resource('classes', ClassController::class)->except(['create', 'show', 'edit']);

    // Academic Year Management
    Route::resource('academic-years', AcademicYearController::class)->except(['create', 'show', 'edit']);

    // Fee Categories
    Route::resource('fee-categories', FeeCategoryController::class)->except(['create', 'show', 'edit']);

    // Fee Structures
    Route::resource('fee-structures', FeeStructureController::class)->except(['show']);

    // Payment Reports
    Route::get('/payments', [PaymentReportController::class, 'index'])->name('payments.index');
});

// Student Routes
Route::prefix('student')->middleware(['auth', 'student'])->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/fees', [FeeController::class, 'index'])->name('fees.index');
    Route::get('/receipts', [ReceiptController::class, 'index'])->name('receipts.index');
    Route::get('/receipts/{payment}/download', [ReceiptController::class, 'download'])->name('receipts.download');
});

// Payment Routes (authenticated)
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/create-order', [RazorpayController::class, 'createOrder'])->name('payment.create');
    Route::post('/payment/verify', [RazorpayController::class, 'verify'])->name('payment.verify');
});
