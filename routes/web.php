<?php
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\ProjectController;
use App\Models\Project;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $projects = Project::latest()->get();

    return view('welcome', compact('projects'));

});

Route::get('/about', function () {
    return view('about');
});

Route::get('/project/{project}', [ProjectController::class, 'show']);

Route::middleware('auth')->group(function () {

    Route::get('/admin', [AdminProjectController::class, 'index'])->name('dashboard');

    Route::get('/admin/create', [AdminProjectController::class, 'create']);

    Route::post('/admin/create', [AdminProjectController::class, 'store']);

    Route::get('/admin/edit/{project}', [AdminProjectController::class, 'edit']);

    Route::post('/admin/update/{project}', [AdminProjectController::class, 'update']);

    Route::post('/admin/delete/{project}', [AdminProjectController::class, 'destroy']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';