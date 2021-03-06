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

Route::any("/login", ["uses" => "Auth\LoginController@indexAction", "as" => "login"]);

/* Group '/admin' */
Route::get("/admin", ["uses" => "Admin\IndexController@indexAction", "as" => "admin.index"])->middleware('auth');

Route::get("/admin/photos/list", ["uses" => "Admin\PhotosController@listAction", "as" => "admin.photos.list"]);
Route::post("/admin/photos/create", [
    "uses"   => "Admin\PhotosController@createAction",
    "as"     => "admin.photos.create",
    "before" => "csrf",
])->middleware('auth');
Route::post("/admin/photos/{id}/update", [
    "uses"   => "Admin\PhotosController@updateAction",
    "as"     => "admin.photos.update",
//    "before" => "csrf",
]);
Route::post("/admin/photos/{id}/delete", [
    "uses"   => "Admin\PhotosController@deleteAction",
    "as"     => "admin.photos.delete",
//    "before" => "csrf",
])->middleware('auth');


// API

Route::get("/photos", ["uses" => "HomeController@getPhotosAction", "as" => "photos.list"]);

