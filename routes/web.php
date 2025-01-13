<?php

use App\Http\Controllers\BusController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\MapController;

Route::get('/', [MainController::class, 'index']);

Route::get('/station', [StationController::class, 'index']);

Route::get('/info', [BusController::class, 'index']);

Route::get('/station_info', [StationController::class, 'info']);

Route::get('/station/ax_search', [StationController::class, 'ax_search']);

Route::get('/bus/search', [StationController::class, 'search']);

Route::get('/bus/ax_search_info', [BusController::class, 'ax_search_info']);

Route::get('/bus/ax_search_map', [BusController::class, 'ax_search_map']);

Route::get('/bus/ax_search_keyword', [BusController::class, 'ax_search_keyword']);

Route::get('/map', [MapController::class, 'index']);
