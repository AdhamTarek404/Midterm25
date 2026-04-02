<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\BookController;
use App\Http\Controllers\Web\BorrowController;

// ==================== HOME ====================
Route::get('/', function () {
    return view('welcome');
});

// ==================== AUTHENTICATION ====================
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// ==================== PROFILE (Authenticated Users) ====================
Route::get('profile', [UsersController::class, 'profile'])
    ->name('profile')
    ->middleware('auth:web');

// ==================== MEMBERS LIST (Admin + Librarian) ====================
Route::get('members', [UsersController::class, 'members'])
    ->name('members')
    ->middleware(['auth:web', 'role:admin,librarian']);

// ==================== CREATE LIBRARIAN (Admin Only) ====================
Route::get('librarian/create', [UsersController::class, 'createLibrarian'])
    ->name('create_librarian')
    ->middleware(['auth:web', 'role:admin']);
Route::post('librarian/store', [UsersController::class, 'storeLibrarian'])
    ->name('store_librarian')
    ->middleware(['auth:web', 'role:admin']);

// ==================== ROLES PAGE (Admin Only) ====================
Route::get('roles', [UsersController::class, 'roles'])
    ->name('roles')
    ->middleware(['auth:web', 'role:admin']);

// ==================== BOOKS ====================
Route::get('books', [BookController::class, 'list'])->name('books_list');
Route::get('books/edit/{book?}', [BookController::class, 'edit'])
    ->name('books_edit')
    ->middleware(['auth:web', 'role:admin,librarian']);
Route::post('books/save/{book?}', [BookController::class, 'save'])
    ->name('books_save')
    ->middleware(['auth:web', 'role:admin,librarian']);
Route::get('books/delete/{book}', [BookController::class, 'delete'])
    ->name('books_delete')
    ->middleware(['auth:web', 'role:admin,librarian']);

// ==================== BORROW (Authenticated Users) ====================
Route::get('books/borrow/{book}', [BorrowController::class, 'borrow'])
    ->name('books_borrow')
    ->middleware('auth:web');
Route::get('borrows/history', [BorrowController::class, 'history'])
    ->name('borrows_history')
    ->middleware('auth:web');
