<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Questionaire;
use App\Http\Controllers\QuestionnaireReportController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('questionnaire', Questionaire::class)
    ->middleware(['auth', 'verified'])
    ->name('questionnaire');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


Route::get('/questionnaire/{session}/report', [QuestionnaireReportController::class, 'download'])
    ->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
