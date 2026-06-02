<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ហៅ Controllers ទាំងអស់មកប្រើប្រាស់
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\DailyTaskController;
use App\Http\Controllers\API\InventoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ========================================================
// តំបន់សេរី (Public Routes) - មិនតម្រូវអោយមាន Token ទេ
// ========================================================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); // បានប្តូរវាមកទីនេះវិញ!

// ========================================================
// តំបន់ការពារ (Protected Routes) - ទាមទារអោយមាន Token ទើបអាចប្រើបាន
// ========================================================
Route::middleware('auth:sanctum')->group(function () {

    // ១. ទាញយកព័ត៌មានបុគ្គលិកដែលកំពុង Login
    Route::get('/user', function (Request $request) {
        return $request->user()->load('branch');
    });

    // ២. API សម្រាប់ Logout (ចាកចេញ)
    Route::post('/logout', [AuthController::class, 'logout']);

    // ៣. API សម្រាប់ការកុម្ម៉ង់ម្ហូប និងផ្ទះបាយ
    Route::post('/orders/place', [OrderController::class, 'placeOrder']);
    Route::get('/orders/cooking', [OrderController::class, 'getCookingOrders']);

    // ៤. API សម្រាប់ការគិតប្រាក់
    Route::post('/payments/process', [PaymentController::class, 'processPayment']);

    // ៥. API សម្រាប់កិច្ចការប្រចាំថ្ងៃ (អនាម័យ)
    Route::get('/tasks/pending', [DailyTaskController::class, 'getPendingTasks']);
    Route::post('/tasks/{id}/complete', [DailyTaskController::class, 'completeTask']);

    // ៦. API សម្រាប់គ្រប់គ្រងស្តុកគ្រឿងផ្សំ
    Route::get('/inventory', [InventoryController::class, 'getInventory']);
    Route::post('/inventory/add', [InventoryController::class, 'addStock']);
});
