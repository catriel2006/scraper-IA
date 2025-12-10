<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScraperController;

Route::post('/scrape-person', [scrapercontroller::class, 'scrape']);
