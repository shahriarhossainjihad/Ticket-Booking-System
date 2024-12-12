<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route::prefix('admin/vehicle')->group(function () {
//     Route::controller(ServiceCategoryController::class)->group(function () {
//         Route::get('/categories', 'index')->name('admin.service.categories');
//         Route::post('/category/store', 'store')->name('admin.service.category.store');
//         Route::get('/category/view', 'show')->name('category.view');
//         Route::get('/category/edit/{id}', 'edit')->name('category.edit');
//         Route::post('/category/update/{id}', 'update')->name('category.update');
//         Route::get('/category/delete/{id}', 'destroy')->name('category.delete');
//         Route::post('/category/status/{catId}', 'changeStatus')->name('admin.service.category.status');
//     });
//     Route::controller(serviceController::class)->group(function () {
//         Route::get('/', 'index')->name('admin.services');
//         Route::post('/store', 'store')->name('admin.service.store');
//         // Route::get('/category/view', 'show')->name('category.view');
//         // Route::get('/category/edit/{id}', 'edit')->name('category.edit');
//         // Route::post('/category/update/{id}', 'update')->name('category.update');
//         // Route::get('/category/delete/{id}', 'delete')->name('category.delete');
//     });
// });