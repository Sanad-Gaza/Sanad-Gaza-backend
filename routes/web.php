<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Sanad Backend API is running',
        'app' => 'Sanad Educational Platform',
    ]);
});
