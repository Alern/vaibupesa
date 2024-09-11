<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\AgentMerchants\AgentMerchantsController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/customers/create', 'pages.customers.create');
Route::post('/customers/store', [\App\Http\Controllers\Customers\CustomersController::class, 'create'])->name('/customers/store');

Route::view('/agentmerchants/create', 'pages.agentmerchants.create');
Route::post('/agentmerchants/store', [\App\Http\Controllers\AgentMerchants\AgentMerchantsController::class, 'create'])->name('/agentmerchants/store');

Route::view('/transactiontypes/create', 'pages.transactiontypes.create');
Route::post('/transactiontypes/store', [\App\Http\Controllers\TransactionTypes\TransactionTypesController::class, 'create'])->name('/transactiontypes/store');

Route::view('/chargebands/create', 'pages.chargebands.create');
Route::post('/chargebands/store', [\App\Http\Controllers\ChargeBands\ChargeBandsController::class, 'create'])->name('/chargebands/store');

Route::view('/transactions/create', 'pages.transact.create');
Route::post('/transactions/store', [\App\Http\Controllers\Transactions\TransactionsController::class, 'create'])->name('/transactions/store');
Route::get('/transactions/view', [\App\Http\Controllers\Transactions\TransactionsController::class, 'showTransactions']);

Route::view('/transactions/hakikisha', 'pages.transact.hakikisha');
Route::post('/transactions/hakikisha/validate', [\App\Http\Controllers\Transactions\TransactionsController::class, 'validateInput'])->name('/transactions/hakikisha/validate');
Route::get('/transactions/hakikisha/cancel',[\App\Http\Controllers\Transactions\TransactionsController::class, 'hakikishaCancel'])->name('/transactions/hakikisha/cancel');

Route::get('/notifications/view', [\App\Http\Controllers\Transactions\TransactionsController::class, 'showNotifications']);
Route::get('/revenue/view', [\App\Http\Controllers\Transactions\TransactionsController::class, 'showRevenue']);

Route::view('/registeredcustomers/create', 'pages.registeredcustomers.createregistered');
Route::post('/registeredcustomers/store', [\App\Http\Controllers\RegisteredCustomers\RegisteredCustomersController::class, 'create'])->name('/registeredcustomers/store');

Route::view('/registered/transactions/hakikisha', 'pages.registeredcustomers.hakikisha');
Route::post('/rtransactions/hakikisha/validate', [\App\Http\Controllers\RegisteredCustomers\RegisteredCustomersController::class, 'sendRegistered'])->name('/rtransactions/hakikisha/validate');
Route::get('/registered/transactions/hakikisha/cancel',[\App\Http\Controllers\RegisteredCustomers\RegisteredCustomersController::class, 'hakikishaCancel'])->name('/registered/transactions/hakikisha/cancel');

Route::view('/registered/updatekyc', 'pages.registeredcustomers.updatekyc');
Route::post('/registered/updatekyc/store', [\App\Http\Controllers\RegisteredCustomers\RegisteredCustomersController::class, 'updateKyc'])->name('/registered/updatekyc/store');

Route::view('/registered/statements', 'pages.registeredcustomers.statements');
Route::post('/registered/statements/search', [\App\Http\Controllers\RegisteredCustomers\RegisteredCustomersController::class, 'statements'])->name('/registered/statements/search');

Auth::routes();
Route::post('/logintry',[\App\Http\Controllers\Auth\LoginController::class,'authenticate'])->name('/logintry');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
