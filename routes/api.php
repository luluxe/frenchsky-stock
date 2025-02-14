<?php

use App\Http\Controllers\Api\V1\OrderLimitController;
use App\Http\Controllers\Api\V1\OrderMarketController;
use App\Http\Controllers\Api\V1\StockController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Support\Facades\Route;

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

Route::prefix("v1")->middleware("auth.api")->group(function() {
    ///////
    /// STOCK
    ///////

    /*
     * Transaction
     */
    Route::prefix("transactions")->group(function() {
        Route::get("{uuid}", [TransactionController::class, "index"]);
    });

    /*
     * stock
     */
    Route::prefix("stock")->group(function() {
        Route::get("simple-stats", [StockController::class, "simpleStats"]);
        Route::get("stats", [StockController::class, "stats"]);
    });

    /*
     * order market
     */
    Route::prefix("order-market")->group(function() {
        Route::get("", [OrderMarketController::class, "info"]);
        Route::post("", [OrderMarketController::class, "create"]);
    });

    /*
     * order limits
     */
    Route::prefix("order-limits")->group(function() {
        Route::get("", [OrderLimitController::class, "index"]);
        Route::get("/player", [OrderLimitController::class, "player"]);
        Route::post("/create", [OrderLimitController::class, "create"]);
        Route::post("/{id}/destroy", [OrderLimitController::class, "destroy"]);
    });
});

