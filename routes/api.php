<?php

use Illuminate\Support\Facades\Route;

Route::post('/images', 'ImageController@upload');
Route::get('/images','ImageController@getAll');
