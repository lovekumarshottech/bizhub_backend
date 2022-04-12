<?php

use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\HomeController;
use App\Models\Category;
use App\Models\Service;
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
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/jobs', [HomeController::class, 'allServices'])->name('jobs');
    Route::get('/categories', [HomeController::class, 'allCategories'])->name('categories');
    Route::get('/users', [HomeController::class, 'allUsers'])->name('users');
    Route::get('/disputes', [HomeController::class, 'allDisputes'])->name('disputes');
    Route::get('/logout', [HomeController::class, 'logout'])->name('logout.perform');


    //add and edit category view
    Route::get('/add-category/{id?}', [HomeController::class, 'addOrEditCategory'])->name('add-category-view');

    Route::post('/add-category', [HomeController::class, 'addCategory'])->name('add-category');

    Route::put('/edit-category', [HomeController::class, 'editCategory'])->name('edit-category');

    Route::get('/delete-category/{id}', function ($id) {
        $category = Category::find($id)->delete();

        if ($category) {
            return redirect()->route('categories')->with('success', 'Category deleted successfully');
        }
    })->name('delete-category');
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
