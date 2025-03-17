<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::get('/pets/form', [PetController::class, 'form'])->name('pets.form');
Route::get('/', [PetController::class, 'index'])->name('pets.index');
Route::get('/pets/{petId}', [PetController::class, 'show'])->name('pets.show');
Route::post('/pets/store', [PetController::class, 'store'])->name('pets.store');
Route::get('/pets/{petId}/edit', [PetController::class, 'edit'])->name('pets.edit');
Route::put('/pets/{petId}', [PetController::class, 'update'])->name('pets.update');
Route::post('/pets/{petId}/upload-image', [PetController::class, 'uploadImage'])->name('pets.uploadImage');
Route::delete('/pets/{petId}', [PetController::class, 'destroy'])->name('pets.destroy');
