<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\PaymentController;


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

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [RegisterController::class, 'showVerifyForm'])->name('auth.verify');
    Route::post('/email/resend', [RegisterController::class, 'resendVerificationEmail'])->name('auth.resend');
    Route::post('/search', [ItemController::class, 'search'])->name('products.search');
    Route::get('/clear', [ItemController::class, 'clearSession'])->name('clear.session');
    Route::get('/favorite/get', [FavoriteController::class, 'getFavorite'])->name('favorite.get');
    Route::post('/toggle',[FavoriteController::class, 'toggleFavorite'])->name('favorite.toggle');

    Route::get('/user', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/profiles/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/change', [UserController::class, 'editAddress'])->name('user.change');
    Route::post('/update/address', [UserController::class, 'updateAddress'])->name('user.address.update');
    Route::get('/comment/{id}', [CommentController::class, 'index'])->name('comment.index');
    Route::post('/comment/{id}/store', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comment/{id}/delete', [CommentController::class, 'destroy'])->name('comment.destroy');
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');

    Route::get('/order/details', [OrderController::class, 'showOrderDetails'])->name('order.details');
    Route::post('/order/store', [OrderController::class, 'storeTemporaryOrder'])->name('order.storeTemporary');
    Route::post('/order/submit', [OrderController::class, 'submitOrder'])->name('order.submit');
    Route::get('/purchase/success/{orderId}', [OrderController::class, 'success'])->name('purchase.success');
    Route::get('/orders/{order}/editPayment', [OrderController::class, 'edit'])->name('payment.edit');
    Route::put('/orders/{order}/updatePayment', [OrderController::class, 'update'])->name('payment.update');
    Route::get('/credit',[PaymentController::class, 'showRegistrationForm'])->name('credit.show');
    Route::post('/credit/save', [PaymentController::class, 'saveCreditCard'])->name('credit.save');
});
Route::get('/', [ItemController::class, 'index'])->name('home.index');
Route::get('/item/{id}', [ItemController::class, 'detail'])->name('item.detail');



Route::middleware(['auth.admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/comments/{user}', [AdminController::class, 'comment'])->name('admin.comment');
    Route::delete('/comments/{id}/remove', [AdminController::class, 'remove'])->name('comments.remove');
    Route::get('/email', [EmailController::class, 'show'])->name('email.show');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('user.destroy');
    Route::post('/send-mail', [EmailController::class, 'sendNotification'])->name('send.mail');
    Route::post('/send-all', [EmailController::class, 'sendAll'])->name('send.all');
});





Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::get('/admin/register', [AdminRegisterController::class, 'showRegisterForm'])->name('admin.register');
Route::post('/admin/register', [AdminRegisterController::class, 'register']);
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');



Auth::routes();

