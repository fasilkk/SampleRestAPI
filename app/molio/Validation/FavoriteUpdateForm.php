<?php

namespace molio\Validation;

use Laracasts\Validation\FormValidator;

class FavoriteUpdateForm extends FormValidator
{
    protected $rules = [

        'name' => 'required|min:2',

    ];
}
