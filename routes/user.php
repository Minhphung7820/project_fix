<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);
