<?php

namespace molio\Validation;

use Laracasts\Validation\FormValidator;

class ContactGroupForm extends FormValidator
{
    protected $rules = [

        'name' => 'required|min:2',

    ];
}
