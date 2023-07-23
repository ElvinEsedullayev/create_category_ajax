<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('category/index',[CategoryController::class,'index'])->name('category.index');
Route::get('category/create',[CategoryController::class,'create'])->name('category.create');
Route::post('create-category',[CategoryController::class,'store'])->name('store.category');
Route::get('category/{id}/edit',[CategoryController::class,'edit'])->name('category.edit');
Route::get('category/destroy/{id}',[CategoryController::class,'delete'])->name('category.delete');