<?php

namespace molio\Validation;

use Laracasts\Validation\FormValidator;

class UpdateForm extends FormValidator
{
    protected $rules = [

            'fname'   => 'min:3|max:30',
            'lname'   => 'max:20',
            'address' => 'min:10',
            'email'   => 'email',
        ];
}
