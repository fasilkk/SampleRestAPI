<?php
namespace molio\Validation;

use Laracasts\Validation\FormValidator;

Class PasswordForm extends FormValidator {

    protected $rules = [
        'password'      => 'required|min:5' ,
        'passwordmatch' => 'required|same:password'

    ];


}