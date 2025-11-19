<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::any('/allusers', [ProfileController::class, 'allUsers'])->name('profile.allusers');
    Route::any('/allevent', [ProfileController::class, 'allEventTranscation'])->name('profile.allevent');
    Route::any('/editevent/{id}', [ProfileController::class, 'eventEdit'])->name('event.edit');
    Route::any('/savecomment', [ProfileController::class, 'saveEventTransactionComment'])->name('event.savecomment');

    //
    Route::any('/getlearner', [ProfileController::class, 'getLearnerFromYuthHub'])->name('yuthhub.getlearner');
    Route::any('/alllearner', [ProfileController::class, 'allLearner'])->name('profile.alllearner');
    
    
    
    
    
});
Route::any('/bigquery', [ProfileController::class, 'allLearnerFromBigQueryList'])->name('bigquery.learner');
Route::get('/bigquery-count', [ProfileController::class, 'countLearners']);
require __DIR__.'/auth.php';
