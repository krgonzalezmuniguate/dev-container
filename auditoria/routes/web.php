<?php

use App\Http\Controllers\Supplies\RequestSupplyController;
use Illuminate\Support\Facades\Route;

Route::get('request_supplies_download/{id}', [RequestSupplyController::class, 'download']);


