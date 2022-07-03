<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Tonod\LogInformation\Controllers\GetLogController;

Route::prefix('logs')->name('logs.')->group(function () {
    Route::prefix('informations')->name('informations.')->group(function () {
        Route::get('files', [GetLogController::class, 'index']);
    });
});
