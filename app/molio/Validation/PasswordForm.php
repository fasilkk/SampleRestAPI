<?php

namespace molio\Validation;

use Laracasts\Validation\FormValidator;

class PasswordForm extends FormValidator
{
    protected $rules = [
        'password'      => 'required|min:5',
        'passwordmatch' => 'required|same:password',

    ];
}
