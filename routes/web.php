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

Route::middleware(["api"])->prefix("oauth")->group(function () {
    $this->post("token","ApiAuthController@login");
});

Route::middleware(["api"])->prefix("api")->group(function () {
    $this->post("login","ApiAuthController@login");
});

Route::middleware(["auth:api"])->prefix("api")->group(function () {

    $this->post("tree/assign","ApiTreeController@postAssignTree");
    $this->get("tree/history-transaction","ApiTreeController@getHistoryTransaction");
    $this->get("tree/stock","ApiTreeController@getStock");
    $this->post("donor/update","ApiDonorController@postUpdate");
    $this->post("donor/create","ApiDonorController@postCreate");
    $this->get("donor/list", "ApiDonorController@getList");
});

Route::prefix(config("app.admin_path"))->group(function () {

    $this->get("blocked-request/delete/{id}", "BlockedRequestController@getDelete");
    $this->get("blocked-request", "BlockedRequestController@getIndex");

    $this->get("activity-log/detail/{id}", "ActivityLogController@getDetail");
    $this->get("activity-log", "ActivityLogController@getIndex");

    $this->get("clear-cache", "AdminUserController@getClearCache");
    $this->post('manage-user/add-save','AdminUserController@postAddSave');
    $this->get('manage-user/add','AdminUserController@getAdd');
    $this->post('manage-user/edit-save/{id}', 'AdminUserController@postEditSave');
    $this->get("manage-user/edit/{id}", "AdminUserController@getEdit");
    $this->get('manage-user/delete/{id}', 'AdminUserController@getDelete');
    $this->get("manage-user", "AdminUserController@getIndex");

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
