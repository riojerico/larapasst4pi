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

Route::prefix(config("app.admin_path"))->group(function () {

    $this->post('manage-participant/edit-save/{id}', 'ManageParticipantController@postEditSave');
    $this->get("manage-participant/edit/{id}", "ManageParticipantController@getEdit");
    $this->get('manage-participant/delete/{id}', 'ManageParticipantController@getDelete');
    $this->get("manage-participant", "ManageParticipantController@getIndex");

    Route::get("oauth_clients", "OAuthClientsController@getIndex");

    Route::get("dashboard", "DashboardController@getIndex");
});

Route::prefix(config("app.admin_auth_path"))->group(function () {

    $this->get("register", "AuthController@getRegister");
    $this->post("register", "AuthController@postRegister");

    Route::get("logout", "AuthController@getLogout");
    Route::post("login", "AuthController@postLogin");
    Route::get("login", "AuthController@getLogin")->name("login");
});

Route::get('/', function () {
    return view('welcome');
});
