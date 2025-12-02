<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Supplies\MeasurementUnitController;
use App\Http\Controllers\Supplies\ProviderController;
use App\Http\Controllers\Supplies\RequestSupplyController;
use App\Http\Controllers\Supplies\SupplyCategoryController;
use App\Http\Controllers\Supplies\SupplyController;
use App\Http\Controllers\Supplies\SupplyMovementController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\PersonalDetailsController;
use App\Http\Controllers\User\UserController;

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);
Route::get('me', [LoginController::class, 'me'])->middleware('auth:api');
Route::post('refresh', [LoginController::class, 'refresh']);
Route::post('change_password',[LoginController::class,'changePassword'])->middleware('auth:api');

//resource
Route::apiResource('menu',MenuController::class);
Route::apiResource('personal_details',PersonalDetailsController::class);
Route::apiResource('roles',RoleController::class);
Route::apiResource('request_supplies', RequestSupplyController::class);
Route::apiResource('supplies', SupplyController::class);
Route::apiResource('supplies_movement', SupplyMovementController::class);
Route::apiResource('supplies_category', SupplyCategoryController::class);
Route::apiResource('supplies_providers', ProviderController::class);
Route::apiResource('supplies_measures', MeasurementUnitController::class);

//post
Route::post('personal_details/{id}', [PersonalDetailsController::class, 'update']);
Route::post('supplies/{id}', [SupplyController::class, 'update']);
Route::post('admin/personal_details/{id}', [PersonalDetailsController::class, 'updateAdmin']);
Route::post('users/assign_role/{id}', [UserController::class, 'assignRole']);
Route::get('request_supplies/supervisor/{id}', [RequestSupplyController::class, 'show_to_chief']);
Route::get('request_supplies_manager', [RequestSupplyController::class, 'index_to_manager']);
Route::get('request_supplies_user/{id}', [RequestSupplyController::class, 'show_by_user']);
Route::post('request_supplies/approbe/{id}', [RequestSupplyController::class, 'approbe']);
Route::post('request_supplies/reject/{id}', [RequestSupplyController::class, 'reject']);

//report
Route::post('exportar-excel', [ReportController::class, 'exportExcel']);
Route::post('exportar-pdf', [ReportController::class, 'exportPDF']);

//TEST
Route::get('/test-email', [TestController::class, 'testEmail']);
Route::get('/test-solicitud/{id}', [TestController::class, 'aprobar']);

