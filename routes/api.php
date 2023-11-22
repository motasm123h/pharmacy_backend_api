<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logic\CartController;
use App\Http\Controllers\Logic\DepotController;
use App\Http\Controllers\Logic\OrderController;
use App\Http\Controllers\Logic\SearchController;
use App\Http\Controllers\Auth\AuthenticationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/registerByPhone', [AuthenticationController::class, 'rigesterAsPharma']);
Route::post('/registerUsingEmail', [AuthenticationController::class, 'rigesterAsDepot']);
Route::post('/logInPharma', [AuthenticationController::class, 'logInPharma']);
Route::post('/logInDepot', [AuthenticationController::class, 'logInDepot']);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('updateInfo', [AuthenticationController::class, 'updateInfo']);
    Route::post('logout', [AuthenticationController::class, 'logout']);


    //this is for show the item in the cart
    Route::get("Depot/CartItems", [CartController::class, 'index']);
    //this is for add item to the cart
    Route::post("Depot/addToCart", [CartController::class, 'addItemToCart']);
    //this is for delete the items from the cart
    Route::post("Depot/deleteItemsFromCart", [CartController::class, 'deleteItemsFromCart']);
    //this is for delete one item from cart
    Route::post("Depot/deleteOneItemFromCart", [CartController::class, 'deleteOneItemFromCart']);



    /////////////////////this is the depots section
    Route::get("Depot/getCategory", [DepotController::class, 'index']);
    Route::get("Depot/getCategory/{category}", [DepotController::class, 'getDepotByCategpry']);
    Route::post("Depot/create", [DepotController::class, 'addDepot']);
    Route::post("Depot/update/{id}", [DepotController::class, 'updateDepot']);
    Route::post("Depot/delete/{id}", [DepotController::class, 'deleteDepot']);



    //this is for the admin to change the order situation
    Route::post("Depot/order/edit/{order_id}", [OrderController::class, 'updateOrder']);

    Route::post("Depot/order/paid/{order_id}", [OrderController::class, 'PaidOrder']);

    //this is for show product
    Route::get("Depot/getSippedOrder", [OrderController::class, 'getShippedOrder']);
    Route::get("Depot/getArrivedOrder", [OrderController::class, 'getArrivedOrder']);
    Route::get("Depot/getInStockOrder", [OrderController::class, 'getInStockOrder']);
    Route::get("Depot/allOrder", [OrderController::class, 'getAlOrder']);

    //this is for accept order
    Route::post('Depot/AcceptOrder/{id}', [OrderController::class, 'AcceptOrder']);
    //this is for reject order
    Route::post('Depot/RejectOrder/{id}', [OrderController::class, 'RejectOrder']);


    //this is for make the check out
    Route::post("Depot/checkOut", [OrderController::class, 'CheackOut']);
    //this is for show my previous order
    Route::get("Depot/getMyorder", [OrderController::class, 'getAuthOrder']);
    //this is for getProduct Stutas
    Route::get("Depot/getProductByHereStutas/{stutas}", [OrderController::class, 'getOrderWithStutas']);



    ////////////////////

    //this is for make product fav
    Route::post("category/section/MakeFavproduct/{product_id}", [FavoriteController::class, 'makeOrDeleteFavorites']);
    //this is for get my fav product
    Route::get("category/section/getFavproduct", [FavoriteController::class, 'getFavProduct']);



    Route::get("sreach/{req}", [SearchController::class, 'index']);

    Route::get('getNotifications', [NotificationController::class, 'getNotifications']);
    Route::post('markAsRead/{id}', [NotificationController::class, 'markNotificationsAsRead']);
    Route::post('deleteNotification/{id}', [NotificationController::class, 'deleteNotification']);
});
