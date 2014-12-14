<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('login');
        }
    }
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth.basic', function () {
    $message = [
        "error" => [
            "code" => 401,
            "message" => "Invalid Credentials"
        ]
    ];

    $headers = ['WWW-Authenticate' => 'Basic'];

    $response = Auth::basic('username');

    if (!is_null($response)) {

        return Response::json($message, 401, $headers);
    }

});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {

    if (Auth::check()) return Response::json(array('error' => array('message' => 'You already Login')), 403);

});

/*
|--------------------------------------------------------------------------
| User Information Filter
|--------------------------------------------------------------------------
|
| Limit access to only auth user and with only data param
|
*/

Route::filter('userstatus', function ($route, $request, $response) {

    if ($response != "data") return Response::json(array('error' => array('message' => 'This service is Unavailable !',"status_code" =>'403')), 403);

});


/*
|--------------------------------------------------------------------------
| User Information Filter
|--------------------------------------------------------------------------
|
| Limit access to only auth user and with only data param
|
*/

Route::filter('favFilter', function ($route, $request, $response) {

    if ($response != "count") return Response::json(array('error' => array('message' => 'This service is Unavailable !',"status_code" =>'403')), 403);

});




/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});
