<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\PartnerController;
use App\Http\Controllers\daily\Daily;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\expenses\CarsController;
use App\Http\Controllers\expenses\UsersController;
use App\Http\Controllers\FinanceialManagement\DashController;
use App\Http\Controllers\FinanceialManagement\RevenuesController;
use App\Http\Controllers\FinanceialManagement\SellAndBuyController;
use App\Http\Controllers\Run\CustomsController;
use App\Http\Controllers\Run\DatesController;
use App\Http\Controllers\Run\OfficeController;
use App\Http\Controllers\Run\ContanierController;
use App\Http\Controllers\Run\StorageContainerController;
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




Route::group(['prefix' => 'system', 'middleware' => ['auth', 'CheckUserRole']], function () {
    //expenses data
    Route::get('expenses/data/cars', [CarsController::class, 'index'])->name('expensesCarsData');
    Route::get('expenses/car/{id}', [CarsController::class, 'carsDaily'])->name('expensesCarDaily');
    Route::get('expenses/albancher/data', [UsersController::class, 'albancher'])->name('expensesSallaryAlbancher');
    Route::get('expenses/daily/albancher/{id}', [UsersController::class, 'albancherDaily'])->name('expensesAlbancherDaily');
    Route::get('expenses/employee/data', [UsersController::class, 'employee'])->name('expensesSallaryeEmployee');
    Route::get('expenses/daily/employee/{id}', [UsersController::class, 'employeeDaily'])->name('expensesEmployeeDaily');
    Route::get('expenses/tips/employee/{id}', [UsersController::class, 'employeeTips'])->name('expensesEmployeeTips');
    Route::get('expenses/others', [UsersController::class, 'others'])->name('expensesOthers');

    //add offices and get this offices
    Route::get('add/office/{role}', [OfficeController::class, 'index'])->name('addOffice');
    Route::post('post/office/{role}', [OfficeController::class, 'store'])->name('postOffice');
    Route::get('get/offices', [CustomsController::class, 'index'])->name('getOfices');
    Route::post('delete/office/{id}', [CustomsController::class, 'deleteOffices'])->name('deleteOffices');
    Route::get('get/office/container/data/{clientId}', [CustomsController::class, 'getOfficeContainerData'])->name('getOfficeContainerData');

    //add the customs id
    Route::post('post/customs/data/number/{clientId}', [CustomsController::class, 'store'])->name('postCustoms');
    Route::get('show/contanier/post/{customId}/{contNum}', [CustomsController::class, 'showContainerPost'])->name('showContanierPost');
    Route::get('show/contanier/{customId}/', [CustomsController::class, 'showContainer'])->name('showContainer');

    //add Container
    Route::post('add/contanier/post/{customs_id}', [ContanierController::class, 'store'])->name('addContainer');
    Route::post('edit/contanier/price/{id}', [RevenuesController::class, 'priceContainerEdit'])->name('editContainerPrice');

    //Dates and empty
    Route::get('offices/dates', [DatesController::class, 'index'])->name('dates');
    Route::get('update/container/{id}', [DatesController::class, 'ContainerRentStatus'])->name('ContainerRentStatus');
    Route::get('empty/contaniers', [DatesController::class, 'empty'])->name('empty');
    Route::post('update/container/status/{id}', [DatesController::class, 'update'])->name('updateContainer');
    Route::post('delete/container/{id}', [DatesController::class, 'deleteContainer'])->name('deleteContainer');
    Route::post('update/container/empty/{id}', [DatesController::class, 'updateEmpty'])->name('updateEmpty');

    //employee
    Route::get('get/employee/data', [EmployeeController::class, 'index'])->name('getEmployee');
    Route::post('post/employee/data', [EmployeeController::class, 'store'])->name('postEmployee');
    Route::post('post/edit/cars/data', [EmployeeController::class, 'storeCar'])->name('postCar');
    Route::get('get/employee/type/{name}/{userId}', [EmployeeController::class, 'type'])->name('getEmployeeType');
    Route::post('update/employee/{userId}', [EmployeeController::class, 'storeType'])->name('updateEmployeeData');

    //FinancialManagement
    Route::get('Financial/Management', [DashController::class, 'index'])->name('FinancialManagement');
    Route::get('Financial/Management/Revenues/client', [RevenuesController::class, 'index'])->name('getRevenuesClient');
    Route::get('account/statement/data/{clientId}', [RevenuesController::class, 'accountStatement'])->name('getAccountStatement');
    Route::get('account/years/data/{clientId}', [RevenuesController::class, 'accountYears'])->name('getAccountYears');
    Route::post('update/container/price', [RevenuesController::class, 'updateContainerPrice'])->name('updateContainerPrice');
    Route::post('update/container/only', [RevenuesController::class, 'updateContainerOnly'])->name('updateContainerOnly');
    Route::get('offices/rent/Management', [RevenuesController::class, 'rent'])->name('getOfficesRent');
    Route::get('rent/month/data/{clientId}', [RevenuesController::class, 'rentMonth'])->name('getrentMonth');
    Route::post('update/container/rent/price', [RevenuesController::class, 'updateRentContainerPrice'])->name('updateRentContainerPrice');

    //FinancialManagement
    Route::get('daily/Management/data', [Daily::class, 'index'])->name('dailyManagement');
    Route::post('post/daily/data', [Daily::class, 'store'])->name('postDailyData');
    Route::post('update/daily/data/{id}', [Daily::class, 'update'])->name('updateDailyData');
    Route::post('delete/daily/data/{id}', [Daily::class, 'delete'])->name('deleteDailyData');
    Route::post('add/statment/data', [Daily::class, 'addStatement'])->name('addOtherStateMent');
    Route::post('edit/contanier/tips', [Daily::class, 'editContanierTips'])->name('editContanierTips');
    Route::post('add/contanier/price/transfer', [Daily::class, 'addContanierPriceTransfer'])->name('addContanierPriceTransfer');

    //profile settings
    Route::get('user/profile/settnigs/{userId}', [AuthController::class, 'profile'])->name('profileSettings');
    Route::post('user/profile/update/{userId}', [AuthController::class, 'update'])->name('updateUser');

    //company 
    Route::get('company/home', [CompanyController::class, 'index'])->name('CompanyHome');
    Route::get('home', [CompanyController::class, 'index'])->name('home');
    Route::get('company/Detailes', [CompanyController::class, 'companyDetailes'])->name('companyDetailes');
    Route::get('company/Rev/Exp', [CompanyController::class, 'companyRevExp'])->name('companyRevExp');

    //partner
    Route::get('company/partner', [PartnerController::class, 'index'])->name('partnerHome');
    Route::post('store/partner', [PartnerController::class, 'store'])->name('partnerStore');
    Route::post('update/partner/status/{id}', [PartnerController::class, 'inActive'])->name('partnerinActive');
    Route::post('update/partner/data/{userid}', [PartnerController::class, 'update'])->name('partnerUpdate');
    Route::get('partner/Statement/{id}', [PartnerController::class, 'partnerStatement'])->name('partnerStatement');
    Route::get('partner/year/statement/{id}', [PartnerController::class, 'partnerYearStatement'])->name('partnerYearStatement');
    Route::post('partner/update/heade/money', [PartnerController::class, 'updateHeadMoney'])->name('updateHeadMoney');


    //sell and buy routes
    Route::get('sell/and/buy', [SellAndBuyController::class, 'index'])->name('sell.buy');
    Route::post('sell/buy/store', [SellAndBuyController::class, 'store'])->name('transactions.store');
    Route::put('sellAndBuy/update/{transactionId}', [SellAndBuyController::class, 'update'])->name('transactions.update');
    Route::delete('sellAndBuy/update/{transactionId}', [SellAndBuyController::class, 'destroy'])->name('transactions.destroy');

    //thanks God
    Route::get('thanks/god', [ContanierController::class, 'thanksGod'])->name('thanks.god');

    //this route storage container
    Route::post('container/storage/{id}', [StorageContainerController::class, 'storageContainer'])->name('container.storage');
});


