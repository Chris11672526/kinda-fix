<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// ── Public landing page ────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome-gym', [
        'plans'     => DB::table('membership_plans')->where('is_active', 1)->get(),
        'equipment' => DB::table('equipment')->limit(8)->get(),
        'trainers'  => DB::table('trainers')->where('status', 'Active')->get(),
    ]);
})->name('home');

// ── Guest-only routes ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/customer/login',     [CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/customer/login',    [CustomerAuthController::class, 'login']);
    Route::get('/customer/register',  [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/customer/register', [CustomerAuthController::class, 'register']);
    Route::get('/admin/login',        [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login',       [AdminAuthController::class, 'login']);
});

// Laravel requires a named 'login' route for unauthenticated redirects
Route::get('/login', fn () => redirect()->route('customer.login'))->name('login');

// ── Logout ─────────────────────────────────────────────────────────────────
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home');
})->middleware('auth')->name('logout');

// ── Dashboard redirect — NO Eloquent ──────────────────────────────────────
Route::get('/dashboard', function () {
    $roleName = DB::table('roles')
        ->join('users', 'users.role_id', '=', 'roles.id')
        ->where('users.id', Auth::id())
        ->value('roles.name');

    return in_array($roleName, ['admin', 'staff'])
        ? redirect()->route('admin.dashboard')
        : redirect()->route('customer.dashboard');
})->middleware('auth')->name('dashboard');

// ── Customer routes ────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:member'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard',                          [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/payments',                           [CustomerDashboardController::class, 'payments'])->name('payments');
    Route::post('/payments/{payment}/pay',            [CustomerDashboardController::class, 'payPending'])->name('payments.pay');
    Route::post('/membership/cancel',                 [CustomerDashboardController::class, 'cancelMembership'])->name('membership.cancel');
    Route::get('/equipment',                          [CustomerDashboardController::class, 'equipment'])->name('equipment');
    Route::get('/trainers',                           [CustomerDashboardController::class, 'trainers'])->name('trainers');
    Route::post('/trainers/apply',                    [CustomerDashboardController::class, 'applyTrainer'])->name('trainers.apply');
    Route::post('/trainers/{assignment}/cancel',      [CustomerDashboardController::class, 'cancelTrainer'])->name('trainers.cancel');
});

// ── Admin routes ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin,staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                          [AdminDashboardController::class, 'index'])->name('dashboard');

    // Members CRUD
    Route::get('/members',                            [AdminDashboardController::class, 'members'])->name('members');
    Route::post('/members/store',                     [AdminDashboardController::class, 'storeMember'])->name('members.store');
    Route::post('/members/{id}/update',               [AdminDashboardController::class, 'updateMember'])->name('members.update');
    Route::post('/members/{id}/delete',               [AdminDashboardController::class, 'deleteMember'])->name('members.delete');

    // Payments
    Route::get('/payments',                           [AdminDashboardController::class, 'payments'])->name('payments');
    Route::post('/payments/{id}/update',              [AdminDashboardController::class, 'updatePayment'])->name('payments.update');

    // Trainers & applications
    Route::get('/trainers',                           [AdminDashboardController::class, 'trainers'])->name('trainers');
    Route::post('/trainers/{id}/update',              [AdminDashboardController::class, 'approveTrainer'])->name('trainers.update');

    // Other views
    Route::get('/sessions',                           [AdminDashboardController::class, 'sessions'])->name('sessions');
    Route::get('/equipment',                          [AdminDashboardController::class, 'equipment'])->name('equipment');
    Route::post('/equipment/{id}/update',             [AdminDashboardController::class, 'updateEquipment'])->name('equipment.update');
    Route::get('/reports',                            [AdminDashboardController::class, 'reports'])->name('reports');
});