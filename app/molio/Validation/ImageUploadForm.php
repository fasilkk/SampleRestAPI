<?php
namespace molio\Validation;

use Laracasts\Validation\FormValidator;

Class ImageUploadForm extends FormValidator {

    protected $rules = [

        'image' => 'image|required'
    ];


}