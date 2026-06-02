<?php

use Illuminate\Support\Facades\Route;

// ប្រាប់ Laravel អោយចាប់យកគ្រប់ Link ទាំងអស់ រួចបញ្ជូនទៅអោយ Vue ជាអ្នកចាត់ចែង
Route::get('/{any}', function () {
    return view('welcome'); // ត្រូវប្រាកដថាឯកសារដើមរបស់អ្នកឈ្មោះ welcome.blade.php
})->where('any', '.*');
