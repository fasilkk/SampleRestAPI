<?php
namespace molio\Validation;

use Laracasts\Validation\FormValidator;

Class LoginForm extends FormValidator {

    protected $rules = [

        'username' => 'required' ,
        'password' => 'required' ,
    ];


}