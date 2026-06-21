<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('/auctions/{auction}', [MainController::class, 'detail'])->name('detail');
Route::get('/auctions/{auction}/bid', [MainController::class, 'bidForm'])->name('bid.create');
Route::post('/auctions/{auction}/bid', [MainController::class, 'submitBid'])->name('bid.store');

// Auth
Auth::routes(['register' => false]);
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// User dashboard
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home/profile', [HomeController::class, 'dataEditor'])->name('home.data.editor');
    Route::patch('/home/profile', [HomeController::class, 'dataSave'])->name('home.data.save');
});

// Admin panel
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/auctions/create', [AdminController::class, 'auctionCreate'])->name('auction.create');
    Route::post('/auctions', [AuctionController::class, 'store'])->name('auction.store');
    Route::get('/auctions/{auction}/edit', [AdminController::class, 'auctionEdit'])->name('auction.edit');
    Route::patch('/auctions/{auction}', [AuctionController::class, 'update'])->name('auction.update');
    Route::patch('/auctions/{auction}/end', [AuctionController::class, 'end'])->name('auction.end');

    Route::get('/bids', [AdminController::class, 'bids'])->name('bids.index');
    Route::get('/bids/{bid}', [AdminController::class, 'bidShow'])->name('bids.show');

    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::patch('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
    Route::patch('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');
});
