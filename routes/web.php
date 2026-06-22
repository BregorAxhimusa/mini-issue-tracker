<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/projects');

Route::get('dashboard', fn () => redirect()->route('projects.index'))
    ->middleware('auth')
    ->name('dashboard');

Route::resource('projects', ProjectController::class);
Route::resource('issues', IssueController::class);

Route::get('tags', [TagController::class, 'index'])->name('tags.index');
Route::post('tags', [TagController::class, 'store'])->name('tags.store');

Route::post('issues/{issue}/tags/{tag}', [TagController::class, 'attach'])->name('issues.tags.attach');
Route::delete('issues/{issue}/tags/{tag}', [TagController::class, 'detach'])->name('issues.tags.detach');

Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::post('issues/{issue}/users/{user}', [UserController::class, 'attach'])->name('issues.users.attach');
Route::delete('issues/{issue}/users/{user}', [UserController::class, 'detach'])->name('issues.users.detach');

Route::get('issues/{issue}/comments', [CommentController::class, 'index'])->name('issues.comments.index');
Route::post('issues/{issue}/comments', [CommentController::class, 'store'])->name('issues.comments.store');

require __DIR__.'/auth.php';
