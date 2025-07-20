<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Customers\GetCustomersAppController;
use App\Http\Controllers\App\Personal\GetPersonalAppController;
use App\Http\Controllers\App\Customers\RegisterCustomerAppController;
use App\Http\Controllers\App\TrainingSessions\SendSessionInviteController;
use App\Http\Controllers\App\Personal\RegisterPersonalAppController;
use App\Http\Controllers\App\Personal\EditPersonalAppController;
use App\Http\Controllers\App\Personal\DeletePersonalAppController;
use App\Http\Controllers\App\Customers\EditCustomerAppController;
use App\Http\Controllers\App\Customers\DeleteCustomerAppController;
use App\Http\Controllers\App\General\LoginAppController;
use App\Http\Controllers\App\General\LogoutAppController;
use App\Http\Controllers\App\TrainingSessions\AcceptTrainingSessionController;
use App\Http\Controllers\App\TrainingSessions\DenyTrainingSessionController;
use App\Http\Controllers\App\TrainingSessions\ChangeTrainingSessionTimeController;
use App\Http\Controllers\App\TrainingSessions\SendLocationController;
use App\Http\Controllers\App\Personal\StorePersonalScheduleController;
use App\Http\Controllers\App\TrainingSessions\GetTrainingSessionsController;
use App\Http\Controllers\App\TrainingSessions\ConfirmTrainingSessionController;
use App\Http\Controllers\App\Personal\StorePersonalCredentialsController;
use App\Http\Controllers\App\Payments\ProcessPaymentController;
use App\Http\Controllers\App\Payments\ProcessPayoutController;
use App\Http\Controllers\App\Payments\ProcessRefundController;
use App\Http\Controllers\App\Reviews\StoreReviewController;
use App\Http\Controllers\App\Reviews\GetReviewController;
use App\Http\Controllers\App\Personal\DeletePersonalScheduleController;
use App\Http\Controllers\App\Personal\GetPersonalScheduleController;
use App\Http\Controllers\App\Personal\DeletePersonalCredentialsController;
use App\Http\Controllers\App\Personal\EditPersonalCredentialsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Rotas de login e cadastro
 Route::post('/login', [LoginAppController::class, 'sendCode'])->name('login.sendCode');
 Route::post('/verify_login', [LoginAppController::class, 'verifyCode'])->name('login.verifyCode');
 Route::prefix('customers')->name('customersApp.')->group(function () {
    Route::post('/register', [RegisterCustomerAppController::class, 'registerCustomer'])->name('registerCustomer');
 });
 Route::prefix('personal')->name('personalApp.')->group(function () {
    Route::post('/register', [RegisterPersonalAppController::class, 'registerPersonal'])->name('registerPersonal');
 });

// Rotas paypal, necessitam estar fora do middleware para consumo
Route::get('/paypal/success', [ProcessPaymentController::class, 'success'])->name('paypal.success');
Route::get('/paypal/cancel', [ProcessPaymentController::class, 'cancel'])->name('paypal.cancel');

Route::middleware(['auth.apitoken'])->group(function () {

    // Clientes
    Route::prefix('customers')->name('customersApp.')->group(function () {
        Route::get('/', [GetCustomersAppController::class, 'index'])->name('index');
        Route::get('/{id}', [GetCustomersAppController::class, 'show'])->name('show');
        Route::put('/{id}', [EditCustomerAppController::class, 'editCustomer'])->name('editCustomer');
        Route::delete('/{id}', [DeleteCustomerAppController::class, 'deleteCustomer'])->name('deleteCustomer'); 
    });

    //Interação de agendamento de treino
    Route::prefix('training')->name('trainingSessions.')->group(function () {
        Route::post('/invite', [SendSessionInviteController::class, 'invite'])->name('invite');
        Route::post('/review', [StoreReviewController::class, 'store'])->name('store');
         Route::get('/reviews', [GetReviewController::class, 'index'])->name('index');
        Route::get('/reviews/personal/{personalId}', [GetReviewController::class, 'getByPersonal'])->name('getPersonalReviews');
        Route::get('/reviews/customer/{customerId}', [GetReviewController::class, 'getByCustomer'])->name('getCustomerReview');
        Route::put('/{session_id}/accept', [AcceptTrainingSessionController::class, 'accept'])->name('accept'); 
        Route::put('/{session_id}/confirm_time', [ConfirmTrainingSessionController::class, 'confirm'])->name('confirm');
        Route::get('', [GetTrainingSessionsController::class, 'index'])->name('index');
        Route::get('/personal/{personal_id}', [GetTrainingSessionsController::class, 'getTrainingSessionsByPersonal'])->name('training.personal');
        Route::get('/customer/{customer_id}', [GetTrainingSessionsController::class, 'getTrainingSessionsByCustomer'])->name('training.customer');
        Route::put('/{session_id}/deny', [DenyTrainingSessionController::class, 'deny'])->name('deny'); 
        Route::post('/change-time', [ChangeTrainingSessionTimeController::class, 'changeTime']);
        Route::post('/send-location/customer', [SendLocationController::class, 'fromCustomer']);
        Route::post('/send-location/personal', [SendLocationController::class, 'fromPersonal']);
    });

    // Personais
    Route::prefix('personal')->name('personalApp.')->group(function () {
        Route::get('/', [GetPersonalAppController::class, 'index'])->name('index');
        Route::get('/{id}', [GetPersonalAppController::class, 'show'])->name('show');
        Route::put('/{id}', [EditPersonalAppController::class, 'editPersonal'])->name('editPersonal'); 
        Route::delete('/{id}', [DeletePersonalAppController::class, 'deletePersonal'])->name('deletePersonal');
        Route::post('/register/schedule', [StorePersonalScheduleController::class, 'store'])->name('store');
        Route::post('/register/credentials', [StorePersonalCredentialsController::class, 'store'])->name('store');
        Route::delete('/{personal_id}/schedule', [DeletePersonalScheduleController::class, 'delete'])->name('delete');
        Route::get('/{personal_id}/schedule', [GetPersonalScheduleController::class, 'show'])->name('show');
        Route::delete('/credentials/{personal_id}', [DeletePersonalCredentialsController::class, 'delete'])->name('personalApp.credentials.delete');
        Route::put('/credentials/{personal_id}', [EditPersonalCredentialsController::class, 'update'])->name('personalApp.credentials.update');

    });
   
    //Pagamento via paypal
    Route::post('/paypal/pay/{session_id}', [ProcessPaymentController::class, 'createPaymentOrder'])->name('paypal.pay.createPaymentOrder');
    Route::post('/payout/{personal_id}/process', [ProcessPayoutController::class, 'processPayout']);
    Route::get('/payout/status/{batch_id}', [ProcessPayoutController::class, 'checkPayoutStatus']);

    Route::post('/logout', [LogoutAppController::class, 'logoutUser'])->name('logout.logoutUser');

    Route::post('/broadcasting/auth', function (Request $request) {
        return Broadcast::auth($request);
    });
});