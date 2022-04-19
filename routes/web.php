<?php

use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\HomeController;
use App\Models\Category;
use App\Models\Service;
use App\Models\Support;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\Environment\Console;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Carbon::now()->subDay();
//     // return ("2022-04-06 17:57:21" <= Carbon::now()->subDay());
//     if ("2022-04-04 17:57:21" <= Carbon::now()->subDay()) {
//         return 'true';
//     } else if ("2022-04-04 17:57:21" >= Carbon::now()->subDay()) {
//         return 'false';
//     }
//     // return view('welcome');
// });



Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');



    //categories listing method
    Route::get('/categories/listing', [HomeController::class, 'allCategories'])->name('categories');
    //category view
    Route::get('/categories', [HomeController::class, 'categoryIndexView'])->name('view.categories');
    //category add method
    Route::post('/add-category', [HomeController::class, 'addCategory'])->name('add-category');
    //category edit method
    Route::put('/edit-category', [HomeController::class, 'editCategory'])->name('edit-category');
    //category edit view and add view
    Route::get('/add-category/{id?}', [HomeController::class, 'addOrEditCategory'])->name('add-category-view');
    //category delete method
    Route::get('/delete-category/{id}', [HomeController::class, 'deleteCategory'])->name('delete-category');




    //users view
    Route::get('/users', [HomeController::class, 'usersIndexView'])->name('view.users');
    //users listing method
    Route::get('/users/listing', [HomeController::class, 'allUsers'])->name('users');
    //delete user method
    Route::get('/delete-user/{id}', [HomeController::class, 'deleteUser'])->name('delete-user');




    //all services method
    Route::get('/jobs/listing', [HomeController::class, 'allServices'])->name('jobs');
    //all services view
    Route::get('/jobs', [HomeController::class, 'allServicesView'])->name('view.jobs');
    //application view according to service
    Route::get('/applications/{id}', [HomeController::class, 'applicationsIndexView'])->name('view.applications');
    //application method according to service 
    Route::get('/applications/listing/{id}', [HomeController::class, 'applications'])->name('applications');



    //all disputes method
    Route::get('/disputes/listing', [HomeController::class, 'allDisputes'])->name('disputes');
    //all disoutes view
    Route::get('/disputes', [HomeController::class, 'allDisputesView'])->name('view.disputes');
    // view dispute 
    Route::get('/dispute/{id}', [HomeController::class, 'showDispute'])->name('view.dispute');




    //all services method
    Route::get('/queries/listing', [HomeController::class, 'allQueries'])->name('queries');
    //all services view
    Route::get('/queries', [HomeController::class, 'allQueriesIndexView'])->name('view.queries');



    Route::get('/payout/{id}', [HomeController::class, 'payout'])->name('payout');
    Route::get('/refund/{id}', [HomeController::class, 'payout'])->name('refund');




    Route::get('/logout', [HomeController::class, 'logout'])->name('logout.perform');
});






Route::get('/clear', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');

    return "Cleared!";
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
