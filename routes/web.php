<?php

use App\Http\Controllers\Accounting\AccountGroupController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Setting\RoleController;
use App\Http\Controllers\Setting\SystemConfigurationController;
use App\Http\Controllers\Setting\UserController;
use App\Models\Setting\AccountGroup;
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

    //== System Configuration Route
    Route::get('system-configurations', [SystemConfigurationController::class, 'systemConfigurations'])->name('system.configurations');
    Route::post('system-configurations', [SystemConfigurationController::class, 'systemConfigurationsSubmit'])->name('system.configurations.submit');


    //== Account Group Route
    Route::get('account-groups/dropdown', [AccountGroupController::class, 'dropdown'])->name('account-groups.dropdown');
    Route::resource('account-groups', AccountGroupController::class);
    Route::post('account-groups/datatable', [AccountGroupController::class, 'datatable'])->name('account-groups.datatable');
});
