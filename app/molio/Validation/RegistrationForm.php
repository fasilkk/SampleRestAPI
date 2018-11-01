<?php

namespace molio\Validation;

use Laracasts\Validation\FormValidator;

class RegistrationForm extends FormValidator
{
    protected $rules = [

            'username' => 'required|unique:users',
            'email'    => 'required|unique:users|email',
            'password' => 'required',
            'fname'    => 'required|min:3|max:30',
            'lname'    => 'required|max:20',
            'address'  => 'required|min:10',
        ];
}
