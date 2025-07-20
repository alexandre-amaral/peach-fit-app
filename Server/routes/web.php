<?php

use App\Http\Controllers\General\city\GetCitiesController;
use App\Http\Controllers\Panel\Customer\DeleteCustomerController;
use App\Http\Controllers\Panel\Customer\EditCustomerController;
use App\Http\Controllers\Panel\Customer\GetCustomersController;
use App\Http\Controllers\Panel\Customer\RegisterCustomerController;
use App\Http\Controllers\Panel\Customer\UpdateCustomerController;
use App\Http\Controllers\Panel\HelpDesk\TicketCommentController;
use App\Http\Controllers\Panel\HelpDesk\TicketController;
use App\Http\Controllers\Panel\PersonalTrainer\DeletePersonalTrainerController;
use App\Http\Controllers\Panel\PersonalTrainer\EditPersonalTrainerController;
use App\Http\Controllers\Panel\PersonalTrainer\GetPersonalTrainersController;
use App\Http\Controllers\Panel\PersonalTrainer\RegisterPersonalTrainerController;
use App\Http\Controllers\Panel\PersonalTrainer\UpdatePersonalTrainnerController;
use App\Http\Controllers\Payments\Paypal\PaypalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserLoginController;
use App\Http\Controllers\Panel\GetDashboardController;
use App\Http\Controllers\Panel\User\GetUsersController;
use App\Http\Controllers\Panel\Self\UserLogoutController;
use App\Http\Controllers\Panel\User\DeleteUserController;
use App\Http\Controllers\Panel\User\UpdateUserController;
use App\Http\Controllers\Panel\User\GetUserEditController;
use App\Http\Controllers\User\UserForgotPasswordController;
use App\Http\Controllers\Panel\User\RegisterUserAdminController;
use App\Http\Controllers\Panel\User\ResetUserPasswordController;



//Rotas Gerais
Route::prefix('/')->group(function () {
    Route::get('/', [UserLoginController::class, 'index'])->name('general.view.login');
    Route::post('/', [UserLoginController::class, 'doLogin'])->name('general.post.login');
    Route::get('/esqueci-a-senha', [UserForgotPasswordController::class, 'index'])->name('general.view.forgot');
    Route::post('/esqueci-a-senha', [UserForgotPasswordController::class, 'doForgotPassword'])->name('general.post.forgot');
    Route::post('/get-cities', [GetCitiesController::class,'getCitiesByState'])->name('get-cities-state');
});


Route::prefix('/admin')->group(function () {
    Route::get('/logout', [UserLogoutController::class, 'index'])->name('admin.view.logout');
    Route::get('/dashboard', [GetDashboardController::class, 'index'])->name('admin.view.dashboard');

    Route::prefix('/usuarios')->group(function () {
        Route::get('/', [GetUsersController::class, 'index'])->name('admin.view.users');
        Route::post('/', [RegisterUserAdminController::class, 'index'])->name('admin.register.user');
        Route::delete('/{userid}', [DeleteUserController::class, 'index'])->name('admin.delete.user');
        Route::get('/{userid}/resetar-senha', [ResetUserPasswordController::class, 'resetPassword'])->name('admin.reset.user');
        Route::get('/adicionar', [GetUsersController::class, 'addUserView'])->name('admin.view.add');
        Route::get('/editar/{userid}', [GetUserEditController::class, 'index'])->name('admin.view.edit');
        Route::patch('/editar/{userid}', [UpdateUserController::class, 'index'])->name('admin.update.user');
    });

    Route::prefix('personais')->group(function () {
        Route::get('/', [GetPersonalTrainersController::class,'index'])->name('admin.view.personalTrainers');
        Route::get('/adicionar', [GetPersonalTrainersController::class,'addPersonalView'])->name('admin.personal.add');
        Route::get('/editar/{personalId}', [EditPersonalTrainerController::class, 'index'])->name('admin.personal.edit');
        Route::patch('/editar/{personalId}', [UpdatePersonalTrainnerController::class, 'index'])->name('admin.update.personal');
        Route::post('/', [RegisterPersonalTrainerController::class, 'index'])->name('admin.register.personal');
        Route::delete('/{personalId}', [DeletePersonalTrainerController::class, 'index'])->name('admin.delete.personal');

    });

    Route::prefix('clientes')->group(function () {
        Route::get('/', [GetCustomersController::class,'index'])->name('admin.view.customers');
        Route::get('/adicionar', [GetCustomersController::class,'addCustomerView'])->name('admin.customer.add');
        Route::get('/editar/{customerId}', [EditCustomerController::class, 'index'])->name('admin.customer.edit');
        Route::post('/', [RegisterCustomerController::class, 'index'])->name('admin.register.customer');
        Route::delete('/{customerId}', [DeleteCustomerController::class, 'index'])->name('admin.delete.customer');
        Route::patch('/editar/{customerId}', [UpdateCustomerController::class, 'index'])->name('admin.update.customer');

    });


    Route::get('/paypal/pay', [PaypalController::class, 'pay'])->name('paypal.pay');
    Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
    Route::post('/paypal/payout', [PayPalController::class, 'processPayout'])->name('paypal.payout');
    Route::get('/paypal', function () {
        return view('paypal');
    });
    Route::get('/paypal/payout-form', function () {
        return view('payout-form');
    })->name('payout-form');

    Route::post('/paypal/check-payout-status', [PayPalController::class, 'checkPayoutStatus'])->name('paypal.check-status');

    Route::prefix('/support')->group(function () {

        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
        Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
        Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my');
        
        Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('ticket.comments.store');
    });

});
