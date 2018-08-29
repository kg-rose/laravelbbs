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

// 首页
Route::get('/', 'PagesController@root')->name('root');

// 用户认证脚手架路由
Auth::routes();

// 用户信息资源控制器
Route::resource('users', 'UsersController', ['only' => ['show', 'edit', 'update']]); //仅支持显示、编辑、和更新

// ajax 头像上传专用路由 => PhotosController@store
Route::resource('photos', 'PhotosController', ['only' => 'store']);
// simdior 图片上传路由
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

// 话题
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

// 分类
Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

// 回复
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

// 回复通知
Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);

// 无权限提醒
Route::get('permission-denied', 'PagesController@permissionDenied')->name('permission-denied');