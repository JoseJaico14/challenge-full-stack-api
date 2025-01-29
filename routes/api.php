<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
 
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::group([
    "middleware" => ["auth:api"]
], function ($router) {
    Route::get("list-reports",[ReportController::class,'list_reports']);
    Route::get("get-report/{report_id}",[ReportController::class,'get_report']);
    Route::post("generate-report",[ReportController::class,'generate_report']);
});