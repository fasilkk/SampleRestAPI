<?php

class RemindersController extends ApiController
{
    /**
     * Handle a POST request to remind a user of their password.
     *
     * @return Response
     */
    public function postRemind()
    {
        switch ($response = Password::remind(Input::only('email'))) {
            case Password::INVALID_USER:
                return $this->responseForbidden('associated user not found !');

            case Password::REMINDER_SENT:
                return $this->responseSuccess('password token sent successfully to your email !');
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param string $token
     *
     * @return Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            App::abort(404);
        }

        return $this->response(['token' => $token]);
    }

    /**
     * Handle a POST request to reset a user's password.
     *
     * @return Response
     */
    public function postReset()
    {
        $credentials = Input::only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;

            $user->save();
        });

        switch ($response) {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return $this->responseInternalError('Somthin went wrong please try again later :(');

            case Password::PASSWORD_RESET:
                return $this->responseSuccess('Password Reset Succesfully !');
        }
    }
}
