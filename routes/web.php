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

// Untuk Auth controller
$router->group(['prefix' => 'auth', 'namespace'=>'Auth'], function () use ($router) {
	//Login
	$router->get('/login',  ['uses' => 'LoginController@showLoginForm']);
	$router->post('/login',  ['as'=>'login', 'uses' => 'LoginController@login']);
    $router->get('/logout',  ['uses' => 'LoginController@logout']);

	//Register
	$router->get('/register',  ['as' => 'register', 'uses' => 'LoginController@showRegisterForm']);
	$router->post('/register',  ['as'=>'register-siswa', 'uses' => 'LoginController@register']);

    // Email reset Password
    $router->get('/reset',  ['as'=>'show-reset','uses' => 'ForgotPasswordController@showLinkRequestForm']);
    $router->post('/email',  ['as'=>'password.email', 'uses' => 'ForgotPasswordController@sendResetLinkEmail']);
    $router->get('/reset/token/{token}',  ['as'=>'password.reset.token','uses' => 'ResetPasswordController@showResetForm']);
    $router->post('/reset',  ['as'=>'password.reset','uses' => 'ResetPasswordController@reset']);
});

// Untuk Home
$router->group(['prefix' => '/'], function () use ($router) {
	$router->get('/',  ['as'=>'home','uses' => 'HomeController@index']);
    $router->get('/home',  ['as'=>'home-url','uses' => 'HomeController@index']);
});

// Untuk User
$router->group(['prefix' => 'user'], function () use ($router) {
	$router->get('/',  ['as'=>'index-user','uses' => 'UserController@index']);
	$router->get('/siswa',  ['as'=>'index-siswa','uses' => 'UserController@indexSiswa']);
    $router->post('/get-detail',  ['as'=>'detail','uses' => 'UserController@show']);
    $router->post('/update',  ['as'=>'update-user','uses' => 'UserController@update']);
    $router->post('/store',  ['as'=>'store-user','uses' => 'UserController@store']);
	$router->get('/create',  ['as'=>'create-user','uses' => 'UserController@create']);
	$router->post('/store-siswa',  ['as'=>'store-siswa','uses' => 'UserController@storeSiswa']);
	$router->get('/create-siswa',  ['as'=>'create-siswa','uses' => 'UserController@createSiswa']);
    $router->post('/update-password',  ['as'=>'update-password-user','uses' => 'UserController@updatePassword']);
    $router->post('/delete',  ['as'=>'delete-user','uses' => 'UserController@delete']);
});

// Untuk Class
$router->group(['prefix' => 'student-class'], function () use ($router) {
	$router->get('/',  ['as'=>'student-class','uses' => 'StudentClassController@index']);
	$router->get('/create',  ['as'=>'create-student-class','uses' => 'StudentClassController@create']);
	$router->post('/store',  ['as'=>'store-student-class','uses' => 'StudentClassController@store']);
	$router->get('/get-user-teacher',  ['as'=>'get-user-teacher','uses' => 'StudentClassController@getUserTeacher']);
	$router->post('/delete',  ['as'=>'delete-student-class','uses' => 'StudentClassController@delete']);
	$router->post('/get-detail',  ['as'=>'detail-student','uses' => 'StudentClassController@show']);
	$router->post('/update',  ['as'=>'update-student','uses' => 'StudentClassController@update']);
	$router->get('/join', ['as'=>'join-class','uses' => 'StudentClassController@join']);
	$router->post('/join', ['as'=>'join-student-class','uses' => 'StudentClassController@joinClass']);

	// Class Feed
	$router->get('/{id_kelas}', ['as'=>'list-student-class','uses' => 'FeedController@showClass']);
	$router->post('/{id_kelas}', ['as'=>'keluar-class','uses' => 'FeedController@keluarClass']);
	$router->get('/{id_kelas}/rekap-tugas', ['as'=>'rekap-tugas','uses' => 'FeedController@rekapTugasClass']);
	$router->post('/{id_kelas}/upload',  ['as'=>'upload-feed','uses' => 'FeedController@uploadFeed']);
	$router->post('/{id_kelas}/upload-tugas',  ['as'=>'upload-tugas','uses' => 'FeedController@uploadTugas']);
	$router->post('/{id_kelas}/delete-feed',  ['as'=>'delete-feed','uses' => 'FeedController@deleteFeed']);
	$router->get('/{id_kelas}/class-data',  ['as'=>'class-data','uses' => 'FeedController@showClassData']);
	$router->get('/{id_kelas}/siswa-class',  ['as'=>'siswa-class','uses' => 'FeedController@showSiswaClass']);
	$router->get('/{id_kelas}/siswa-class/{siswa_id}', ['as'=>'tugas-siswa','uses' => 'FeedController@rekapTugasSiswa']);
	$router->post('/{id_kelas}/delete-siswa',  ['as'=>'delete-siswa','uses' => 'FeedController@deleteSiswaClass']);
	$router->get('/{id_kelas}/{id_feed}',  ['as'=>'class-feed','uses' => 'FeedController@showFeed']);
	$router->post('/{id_kelas}/{id_feed}',  ['as'=>'update-feed','uses' => 'FeedController@updateFeed']);
	$router->get('/{id_kelas}/{id_feed}/{siswa_id}',  ['as'=>'show-tugas','uses' => 'FeedController@showTugas']);
	$router->post('/{id_kelas}/{id_feed}/{siswa_id}/update-tugas',  ['as'=>'update-tugas','uses' => 'FeedController@updateTugas']);
});
Route::get('/delete/{id_kelas}/{id}','FeedController@deleteFeed');

// Untuk Siswa
$router->group(['prefix' => 'siswa'], function () use ($router) {
	$router->get('/',  ['as'=>'siswa','uses' => 'UserController@indexSiswa']);
});

// Untuk Role Dan Permission
$router->group(['prefix' => 'role'], function () use ($router) {
	$router->get('/',  ['as'=>'role','uses' => 'RoleController@index']);
	$router->get('/create',  ['as'=>'create-role','uses' => 'RoleController@create']);
	$router->get('/edit/{id}',  ['as'=>'update-role','uses' => 'RoleController@edit']);
	$router->post('/update/{id}',  ['as'=>'do-update-role','uses' => 'RoleController@update']);
	$router->post('/store',  ['as'=>'store-role','uses' => 'RoleController@store']);
	$router->post('/delete',  ['as'=>'delete-role','uses' => 'RoleController@delete']);
});

// Untuk Profile
$router->group(['prefix' => 'profile'], function () use ($router) {
	$router->get('/',  ['as'=>'profile','uses' => 'ProfileController@index']);
	$router->post('/update',  ['as'=>'update-profile','uses' => 'ProfileController@update']);
	$router->post('/update-password',  ['as'=>'update-password-profile','uses' => 'ProfileController@updatePassword']);
	$router->post('/delete-image',  ['as'=>'delete-image','uses' => 'ProfileController@deleteImage']);
});

// Untuk Action Log
$router->group(['prefix' => 'action-log'], function () use ($router) {
	$router->get('/',  ['as'=>'action-log','uses' => 'ActionLogController@index']);
	$router->post('/remove',  ['as'=>'action-log-remove','uses' => 'ActionLogController@destroy']);
});

// Untuk Notification
$router->group(['prefix' => 'notification'], function () use ($router) {
	$router->get('/',  ['as'=>'notification','uses' => 'NotificationController@index']);
	$router->post('/store',  ['as'=>'store-notification','uses' => 'NotificationController@store']);
	$router->post('/get-detail',  ['as'=>'notification-get-detail','uses' => 'NotificationController@getDetail']);
});