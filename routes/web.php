<?php


use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Setting\RoleController;
use App\Http\Controllers\Setting\SystemConfigurationController;
use App\Http\Controllers\Setting\UserController;
use App\Http\Controllers\Accounting\AccountController;
use App\Http\Controllers\Accounting\AccountGroupController;
use App\Http\Controllers\Accounting\AccountingDashboardController;
use App\Http\Controllers\Accounting\JournalEntryController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('app');
});


//== Auth Route
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'loginSubmit'])->name('login.submit');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


//== Authenticated Routes
Route::group(['prefix' => 'app', 'middleware' => ['auth']], function () {
    //== Application Entry Route
    Route::get('', [AppController::class, 'app'])->name('app');

    //== User Routes
    Route::resource('users', UserController::class);
    Route::post('users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('profile', [UserController::class, 'profileSubmit'])->name('profile.submit');


    //== Role Routes
    Route::resource('roles', RoleController::class);
    Route::post('roles/datatable', [RoleController::class, 'datatable'])->name('roles.datatable');

    //== System Configuration Routes
    Route::get('system-configurations', [SystemConfigurationController::class, 'systemConfigurations'])->name('system.configurations');
    Route::post('system-configurations', [SystemConfigurationController::class, 'systemConfigurationsSubmit'])->name('system.configurations.submit');

    //== Accounting Dashboard
    Route::get('accounting-dashboard', [AccountingDashboardController::class, 'index'])->name('accounting.dashboard');

    //== Account Group Routes
    Route::get('account-groups/dropdown', [AccountGroupController::class, 'dropdown'])->name('account-groups.dropdown');
    Route::resource('account-groups', AccountGroupController::class);
    Route::post('account-groups/datatable', [AccountGroupController::class, 'datatable'])->name('account-groups.datatable');

    //== Accounts Routes
    Route::get('accounts/dropdown', [AccountController::class, 'dropdown'])->name('accounts.dropdown');
    Route::resource('accounts', AccountController::class);
    Route::post('accounts/datatable', [AccountController::class, 'datatable'])->name('accounts.datatable');

    //== Journal Entry Routes
    Route::post('journal-entries/datatable', [JournalEntryController::class, 'datatable'])->name('journal-entries.datatable');
    Route::resource('journal-entries', JournalEntryController::class);
});
