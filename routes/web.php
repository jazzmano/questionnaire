<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Questionaire;
use App\Http\Controllers\QuestionnaireReportController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('questionnaire', Questionaire::class)
    ->name('questionnaire');

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');
//
//     Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
//     Volt::route('settings/password', 'settings.password')->name('settings.password');
//     Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
// });

Route::get('/questionnaire/{uuid}/report', [QuestionnaireReportController::class, 'download']);

require __DIR__ . '/auth.php';
