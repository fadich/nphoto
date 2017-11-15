<?php

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

Route::get("/", ["uses" => "HomeController@indexAction", "as" => "homepage"]);

/* Group '/admin' */
Route::get("/admin", ["uses" => "Admin\IndexController@indexAction", "as" => "admin.index"]);

Route::post("/admin/photos/create", ["uses" => "Admin\PhotosController@createAction", "as" => "admin.photos.create"]);
