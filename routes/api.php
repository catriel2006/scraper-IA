<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScraperController;

Route::post('/scrape', [ScraperController::class, 'scrape']);
