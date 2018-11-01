<?php

namespace molio\Validation;

use Laracasts\Validation\FormValidator;

class ImageUploadForm extends FormValidator
{
    protected $rules = [

        'image' => 'image|required',
    ];
}
