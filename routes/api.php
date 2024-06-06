<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StaffAuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

    Route::post('register', [StaffAuthController::class, 'register']);
    Route::post('login', [StaffAuthController::class, 'login']);
    Route::post('logout', [StaffAuthController::class, 'logout']);
    Route::post('add-vendor', [AdminController::class, 'addVendor']);
    Route::post('add-ingredient', [AdminController::class, 'addIngredient']);
    Route::post('update-Ingredient', [AdminController::class, 'updateIngredient']);
    Route::post('add-recipe', [AdminController::class, 'addRecipe']);
    Route::post('recipe-Ingredient', [AdminController::class, 'recipeIngredients']);
    Route::post('add-stockmovement', [AdminController::class, 'addStockMovement']);
    Route::post('generate-report', [AdminController::class, 'generateStockReport']);

    Route::post('place-order', [AdminController::class, 'placeOrder']);
    Route::post('stack-report', [AdminController::class, 'viewStockReport']);
    