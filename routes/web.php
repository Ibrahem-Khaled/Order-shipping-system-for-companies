<?php

use App\Http\Controllers\Run\CustomsController;
use App\Http\Controllers\Run\DatesController;
use App\Http\Controllers\Run\OfficeController;
use App\Http\Controllers\Run\ContanierController;
use App\Http\Controllers\SelectTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//select is run or money
Route::get('select/type', [SelectTypeController::class, 'index'])->name('home');
Route::get('select/type/dashboard', [SelectTypeController::class, 'runDash'])->name('runDash');

//add offices and get this offices
Route::get('add/office', [OfficeController::class, 'index'])->name('addOffice');
Route::post('post/office', [OfficeController::class, 'store'])->name('postOffice');
Route::get('get/offices', [CustomsController::class, 'index'])->name('getOfices');

//add the customs id
Route::post('post/customs/data/number/{clientId}', [CustomsController::class, 'store'])->name('postCustoms');
Route::get('show/contanier/post/{customId}/{contNum}', [CustomsController::class, 'showContainerPost'])->name('showContanierPost');

//add Container
Route::post('add/contanier/post/{customs_id}', [ContanierController::class, 'store'])->name('addContainer');

//Dates
Route::get('offices/dates', [DatesController::class, 'index'])->name('dates');
Route::post('update/container/status/{id}', [DatesController::class, 'update'])->name('updateContainer');
