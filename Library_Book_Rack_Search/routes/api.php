<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookRackController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RecordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Book API
Route::get('/getAllBook',[BookController::class], 'getAllBook');
Route::get('/getBookByInput/{input}', [BookController::class, 'search']);
Route::post('/createBook',[BookController::class], 'createBook');
Route::put('/updateBook',[BookController::class], 'updateBook');
Route::delete('/deleteBook',[BookController::class], 'deleteBook');


//Book Rack API
Route::get('/getAllRack',[BookRackController::class], 'getAllRack');
Route::put('/updateRack',[BookRackController::class], 'updateRack');
Route::post('/createRack',[BookRackController::class], 'createRack');
Route::delete('/deleteRack',[BookRackController::class], 'deleteRack');


//Category API
Route::get('/getAllCategory',[CategoryController::class], 'getAllCategory');
Route::put('/updateCategory',[CategoryController::class], 'updateCategory');
Route::post('/createCategory',[CategoryController::class], 'createCategory');
Route::delete('/deleteCategory',[CategoryController::class], 'deleteCategory');


//Record API
Route::get('/getAllRecord',[RecordController::class], 'getAllRecord');
Route::put('/updateRecord',[RecordController::class], 'updateRecord');
Route::delete('/deleteRecord',[RecordController::class], 'deleteRecord');
