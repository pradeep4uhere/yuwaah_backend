<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleDriveController;
use App\Http\Controllers\LearnerImportController;


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


    Route::any('/alleventbylearner', [ProfileController::class, 'allEventTranscationByLearner'])->name('profile.alllearnerevent');
    

    //
    Route::any('/getlearner', [ProfileController::class, 'getLearnerFromYuthHub'])->name('yuthhub.getlearner');
    Route::any('/alllearner', [ProfileController::class, 'allLearner'])->name('profile.alllearner');
    Route::get('/get-event-categories', [ProfileController::class, 'getCategories']);
    Route::get('/export-event-transactions', [ProfileController::class, 'exportEventTransactions'])->name('export-event-transactions');
    Route::get('/get-categories', [ProfileController::class, 'getCategories'])
    ->name('get.categories');
    Route::get('/yuthhubapilist', [ProfileController::class, 'yuthHubApiList'])->name('profile.yuthhubapilist');
    Route::get('/uploadlearner', [ProfileController::class, 'uploadLearnerForm'])->name('profile.uploadlearner');
    Route::post('/importlearner', [ProfileController::class, 'importLearner'])->name('learners.import');
    Route::get('/importlearnertemplate', [ProfileController::class, 'importLearnerTemplate'])->name('learners.import.template');
    Route::get('/importlearnerhistory', [ProfileController::class, 'importLearnerTemplate'])->name('learners.import.history');
    
    
   
    Route::post('/learners/import/preview',[LearnerImportController::class, 'preview'])->name('learners.import.preview');
    Route::post('/learners/import/confirm',[LearnerImportController::class, 'confirm'])->name('learners.import.confirm');
    Route::post('/learners/import/cancel',[LearnerImportController::class, 'cancel'])->name('learners.import.cancel');

    Route::get(
        '/learners/import',
        [LearnerImportController::class, 'index']
    )->name('learners.import.form');
    
    Route::post(
        '/learners/import',
        [LearnerImportController::class, 'import']
    )->name('learners.import');
    
    Route::post(
        '/learners/import/preview',
        [LearnerImportController::class, 'preview']
    )->name('learners.import.preview');
    
    Route::post(
        '/learners/import/confirm',
        [LearnerImportController::class, 'confirm']
    )->name('learners.import.confirm');
    
    Route::post(
        '/learners/import/cancel',
        [LearnerImportController::class, 'cancel']
    )->name('learners.import.cancel');
    
    Route::get(
        '/learners/import/template',
        [LearnerImportController::class, 'template']
    )->name('learners.import.template');
    
    Route::get(
        '/learners/import/history',
        [LearnerImportController::class, 'history']
    )->name('learners.import.history');
    

    Route::get('/learners/import/history/{import}', [LearnerImportController::class, 'show'])
    ->name('learners.import.show');

    Route::get('/learners/import/history/{import}/failed', [LearnerImportController::class, 'downloadFailed'])
    ->name('learners.import.failed');


    
    
    
    
    
});
Route::get('/google/auth', [GoogleDriveController::class, 'redirectToGoogle'])->name('google.auth');
Route::get('/google/callback', [GoogleDriveController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/google/upload', [GoogleDriveController::class, 'upload']);


//Get All Learneres With Course Name and Course Completed
Route::any('/bigquery-coursename', [ProfileController::class, 'allLearnerwithCourseNameFromBigQueryList'])->name('bigquery.coursename.learner');
Route::get('/bigquery-coursename-count', [ProfileController::class, 'countCourseNameLearners']);




Route::any('/bigquery', [ProfileController::class, 'allLearnerFromBigQueryList'])->name('bigquery.learner');

Route::get('/bigquery-count', [ProfileController::class, 'countLearners']);
Route::get('/upload-google-drive', [GoogleDriveController::class, 'upload'])
    ->name('upload.google.drive');
require __DIR__.'/auth.php';
