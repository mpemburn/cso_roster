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

Route::get('/', function () {
    return view('home');
});

Route::get('main', function () {
    return view('react.main');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Password Reset Routes...
Route::post('password/email', [
    'as' => 'auth.password.email',
    'uses' => 'Auth\PasswordController@sendResetLinkEmail'
]);

// All Auth protected routes
Route::group(['middlewareGroups' => 'web'], function () {

    Route::get('logout', [
        'middleware' => ['auth'],
        'uses' => 'Auth\LoginController@logout'
    ]);

    Route::get('profile/password', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@resetProfilePassword'
    ]);

    Route::post('profile/reset', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@setNewPassword'
    ]);

    Route::get('member', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@index'
    ]);
    Route::get('/member/details', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@details'
    ]);
    Route::get('/member/details/{id}', [
        'middleware' => ['auth'],
        'as' => 'member.details',
        'uses' => 'MembersController@details'
    ]);
    Route::get('/member/contacts/{id}', [
        'middleware' => ['auth'],
        'as' => 'member.contacts',
        'uses' => 'MembersController@retrieveContacts'
    ]);
    Route::get('/member/dues/{id}', [
        'middleware' => ['auth'],
        'as' => 'member.dues',
        'uses' => 'MembersController@retrieveDues'
    ]);
    Route::post('member/update/{id}', [
        'middleware' => ['auth'],
        'as' => 'member.update',
        'uses' => 'MembersController@update'
    ]);
    Route::post('member/store/{id}', [
        'middleware' => ['auth'],
        'as' => 'member.store',
        'uses' => 'MembersController@store'
    ]);

    Route::get('/contact/show/{id}', [
        'middleware' => ['auth'],
        'as' => 'contact.show',
        'uses' => 'ContactsController@show'
    ]);
    Route::post('/contact/update/{id}', [
        'middleware' => ['auth'],
        'as' => 'contact.update',
        'uses' => 'ContactsController@update'
    ]);

    Route::get('/dues/show/{id}', [
        'middleware' => ['auth'],
        'as' => 'dues.show',
        'uses' => 'DuesController@show'
    ]);
    Route::post('/dues/update/{id}', [
        'middleware' => ['auth'],
        'as' => 'dues.update',
        'uses' => 'DuesController@update'
    ]);

//    Route::get('/member/missing', [
//        'middleware' => ['auth'],
//        'uses' => 'MembersController@missingDetails'
//    ]);
//    Route::get('/member/search', [
//        'middleware' => ['auth'],
//        'uses' => 'MembersController@memberSearch'
//    ]);
//    /* Roles, Permissions, and Users */
//    Route::group(['prefix' => 'admin'], function () {
//        Route::controller('roles', 'RolesController');
//        Route::controller('permissions', 'PermissionsController');
//        Route::controller('users', 'UsersController');
//    });
//
//    /* TODO: Create RBAC admin interface
//    Route::get('acl', [
//        'middleware' => ['auth'],
//        'uses' => 'RbacController@index'
//    ]);
//    Route::get('rbac/set_leaders', [
//        'middleware' => ['auth'],
//        'uses' => 'RbacController@setLeadershipRoles'
//    ]);
//    Route::get('rbac/set_perms', [
//        'middleware' => ['auth'],
//        'uses' => 'RbacController@setRolePermissions'
//    ]);
//    */
//
});