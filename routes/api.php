<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\FileController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\FolderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/folder/create', [FolderController::class, 'create'])->name('folder.create');

    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

    Route::controller(FileController::class)->group(function () {
        Route::get('/files/{folder?}', 'index')->name('files');
        Route::post('/files/store/{folder?}', 'store')->name('store');
        Route::post('/file/rename/{file}', 'rename')->name('rename');
        Route::post('/file/download/{file}', 'download')->name('download');
        Route::delete('/file/delete/{file}', 'delete')->name('delete');
    });
});

