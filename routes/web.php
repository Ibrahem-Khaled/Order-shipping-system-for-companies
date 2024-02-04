<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\daily\Daily;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\expenses\CarsController;
use App\Http\Controllers\FinanceialManagement\DashController;
use App\Http\Controllers\FinanceialManagement\RevenuesController;
use App\Http\Controllers\Run\CustomsController;
use App\Http\Controllers\Run\DatesController;
use App\Http\Controllers\Run\OfficeController;
use App\Http\Controllers\Run\ContanierController;
use App\Http\Controllers\SelectTypeController;
use GuzzleHttp\Middleware;
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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('custom-login', [AuthController::class, 'customLogin'])->name('login.custom');
Route::post('custom-registration', [AuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [AuthController::class, 'signOut'])->name('signout');


Route::group(['prefix' => 'system', 'middleware' => 'auth'], function () {
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

    //employee
    Route::get('get/employee/data', [EmployeeController::class, 'index'])->name('getEmployee');
    Route::post('post/employee/data', [EmployeeController::class, 'store'])->name('postEmployee');
    Route::post('post/cars/data', [EmployeeController::class, 'storeCar'])->name('postCar');
    Route::get('get/employee/type/{name}/{userId}', [EmployeeController::class, 'type'])->name('getEmployeeType');
    Route::post('update/employee/{userId}', [EmployeeController::class, 'storeType'])->name('updateEmployeeData');

    //FinancialManagement
    Route::get('Financial/Management', [DashController::class, 'index'])->name('FinancialManagement');
    Route::get('Financial/Management/Revenues/client', [RevenuesController::class, 'index'])->name('getRevenuesClient');
    Route::get('account/statement/data/{clientId}', [RevenuesController::class, 'accountStatement'])->name('getAccountStatement');
    Route::get('account/years/data/{clientId}', [RevenuesController::class, 'accountYears'])->name('getAccountYears');
    Route::post('update/container/price', [RevenuesController::class, 'updateContainerPrice'])->name('updateContainerPrice');

    //FinancialManagement
    Route::get('daily/Management/data', [Daily::class, 'index'])->name('dailyManagement');
    Route::post('post/daily/data', [Daily::class, 'store'])->name('postDailyData');
    Route::post('update/daily/data/{id}', [Daily::class, 'update'])->name('updateDailyData');
    Route::post('delete/daily/data/{id}', [Daily::class, 'delete'])->name('deleteDailyData');
    Route::post('add/statment/data', [Daily::class, 'addStatement'])->name('addOtherStateMent');

    //profile settings
    Route::get('user/profile/settnigs/{userId}', [AuthController::class, 'profile'])->name('profileSettings');
    Route::post('user/profile/update/{userId}', [AuthController::class, 'update'])->name('updateUser');

    //expenses data
    Route::get('expenses/data/cars', [CarsController::class, 'index'])->name('expensesCarsData');
    Route::get('expenses/car/{id}', [CarsController::class, 'carsDaily'])->name('expensesCarDaily');
    Route::get('expenses/sallary/data', [CarsController::class, 'sallary'])->name('expensesSallary');

});
