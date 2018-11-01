<?php

/** ------------------------------------------
 *  Route model binding
 *  ------------------------------------------.
 */
Route::model('favorites', 'Favorite');
Route::model('groups', 'Group');

/* ------------------------------------------
 *  Route constraint patterns
 *  ------------------------------------------
 */

Route::pattern('groups', '[0-9]+');
Route::pattern('userId', '[0-9]+');

/* ------------------------------------------
 *  Route api pages
 *  ------------------------------------------
 */

Route::group(['prefix' => 'api'], function () {

    //register
    Route::post('user/register', 'UserController@register');

    //login
    Route::post('user/login', 'UserController@login');

    //logout
    Route::get('user/logout', 'UserController@logout');

    //update user password
    Route::post('user/password/update', 'UserController@postUserPasswordUpdate');

    //password reset
    Route::controller('user/password', 'RemindersController');

    //show status
    Route::get('user/status', 'UserController@status');

    //view update delete
    Route::resource('user', 'UserController', ['only' => ['show', 'update', 'destroy']]);

    //upload user image
    Route::post('user/profile/image', 'UserController@postUserImage');

    //user phone number
    Route::put('user/mobilenumber/{userId}', 'UserController@mobileNumber');

    //favorite count
    Route::get('favorites/count', 'FavoriteController@count');

    //favorite
    Route::resource('favorites', 'FavoriteController', ['only'=>['index', 'store', 'update', 'destroy']]);

    //contact
    Route::get('/contacts', 'ContactController@contactIndex');

    //contacts route group
    Route::group(['prefix' => 'contacts'], function () {

        //groups
        Route::resource('groups', 'ContactController');

        //list contacts
        Route::get('groups/{groups}/links', 'ContactController@getContact');

        //create contact
        Route::post('groups/{groups}/links', 'ContactController@postContact');

        //delete contact
        Route::delete('groups/{groups}/links', 'ContactController@deleteContact');
    });
});
