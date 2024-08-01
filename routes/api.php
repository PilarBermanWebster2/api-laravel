<?php

use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
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

Route::middleware("auth:sanctum")->get("/user/profile", function (
    Request $request
) {
    return $request->user();
});

Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);

Route::middleware(["auth:sanctum"])->group(function () {
    Route::post("logout", [AuthController::class, "logout"]); //untuk logout

    Route::get("kategori", [KategoriController::class, "index"]); //route kategori
    Route::post("kategori", [KategoriController::class, "store"]); //route kategori
    Route::get("kategori/{id}", [KategoriController::class, "show"]); //route kategori
    Route::put("kategori/{id}", [KategoriController::class, "update"]); //route kategori
    Route::delete("kategori/{id}", [KategoriController::class, "destroy"]); //route kategori

    Route::get("tag", [TagController::class, "index"]); //route tag
    Route::post("tag", [TagController::class, "store"]); //route tag
    Route::get("tag/{id}", [TagController::class, "show"]); //route tag
    Route::put("tag/{id}", [TagController::class, "update"]); //route tag
    Route::delete("tag/{id}", [TagController::class, "destroy"]); //route tag

    Route::get("user", [UserController::class, "index"]); //route user
    Route::post("user", [UserController::class, "store"]); //route user
    Route::get("user/{id}", [UserController::class, "show"]); //route user
    Route::put("user/{id}", [UserController::class, "update"]); //route user
    Route::delete("user/{id}", [UserController::class, "destroy"]); //route user

    Route::get("berita", [BeritaController::class, "index"]); //route berita
    Route::post("berita", [BeritaController::class, "store"]); //route berita
    Route::get("berita/{id}", [BeritaController::class, "show"]); //route berita
    Route::put("berita/{id}", [BeritaController::class, "update"]); //route berita
    Route::delete("berita/{id}", [BeritaController::class, "destroy"]); //route berita
});
