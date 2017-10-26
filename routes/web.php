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

Route::get('refresh-csrf', function(){
    return csrf_token();
});

Route::get('register/success', [
    'uses' => 'Auth\RegisterController@success'
]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Password Reset Routes...
Route::get('password/request', [
    'as' => 'password.request',
    'uses' => 'Auth\ForgotPasswordController@showSendResetRequestForm'
]);

Route::get('password/email', [
    'as' => 'password.email',
    'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
]);

Route::get('password/token/{hash}', [
    'as' => 'password.token',
    'uses' => 'Auth\ForgotPasswordController@showPasswordResetForm'
]);

Route::post('password/submit', [
    'as' => 'password.submit',
    'uses' => 'Auth\ForgotPasswordController@submitNewPassword'
]);

Route::get('password/sent_success/{hash}', [
    'as' => 'password.sent_success',
    'uses' => 'Auth\ForgotPasswordController@emailSuccess'
]);

// All Auth protected routes
Route::group(['middlewareGroups' => 'web'], function () {

    Route::get('logout', [
        'middleware' => ['auth'],
        'uses' => 'Auth\LoginController@logout'
    ]);

    // Shows for to allow user to reset their password while logged in
    Route::get('profile/password', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@resetProfilePassword'
    ]);

    // Performs the password reset action for logged in user
    Route::post('profile/reset', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@setNewPassword'
    ]);

    // Confirms password reset action was successful
    Route::get('profile/success', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@passwordResetSuccess'
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
    Route::get('/member/roles/{id}', [
        'middleware' => ['auth'],
        'as' => 'member.roles',
        'uses' => 'MembersController@retrieveRoles'
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
    Route::get('/contact/delete/{id}', [
        'middleware' => ['auth'],
        'as' => 'contact.delete',
        'uses' => 'ContactsController@destroy'
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
    Route::get('/dues/delete/{id}', [
        'middleware' => ['auth'],
        'as' => 'dues.delete',
        'uses' => 'DuesController@destroy'
    ]);

    Route::get('/role/show/{id}', [
        'middleware' => ['auth'],
        'as' => 'role.show',
        'uses' => 'BoardRolesController@show'
    ]);
    Route::post('/role/update/{id}', [
        'middleware' => ['auth'],
        'as' => 'role.update',
        'uses' => 'BoardRolesController@update'
    ]);
    Route::get('/role/delete/{id}', [
        'middleware' => ['auth'],
        'as' => 'role.delete',
        'uses' => 'BoardRolesController@destroy'
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