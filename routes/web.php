<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // ROLES & PERMISSIONS
    Route::prefix('roles')
        ->name('roles.')
        ->middleware(['permission:role.view'])
        ->group(function () {

            Route::livewire('/', 'pages::role.index')
                ->name('index');

            Route::livewire('/create', 'pages::role.form')
                ->middleware('permission:role.create')
                ->name('create');

            Route::livewire('/{role}/edit', 'pages::role.form')
                ->middleware('permission:role.update')
                ->name('edit');

        });

    // USER MANAGEMENT
    Route::prefix('users')
        ->name('users.')
        ->middleware(['permission:user.view'])
        ->group(function () {

            Route::livewire('/', 'pages::user.index')
                ->name('index');

            Route::livewire('/create', 'pages::user.form')
                ->middleware('permission:user.create')
                ->name('create');

            Route::livewire('/{user}/edit', 'pages::user.form')
                ->middleware('permission:user.update')
                ->name('edit');
        });
});

require __DIR__.'/settings.php';
