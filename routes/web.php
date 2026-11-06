<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\HistoricalFactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('countries.index');
});

Route::get('/countries/check-name', [CountryController::class, 'checkName'])->name('countries.check-name');
Route::resource('countries', CountryController::class);
Route::resource('countries.historical-facts', HistoricalFactController::class)
    ->only(['edit', 'update', 'destroy'])
    ->scoped(['historical_fact' => 'id']);
