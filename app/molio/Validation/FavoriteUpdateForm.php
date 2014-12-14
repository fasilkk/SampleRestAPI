<?php
namespace molio\Validation;

use Laracasts\Validation\FormValidator;

Class FavoriteUpdateForm extends FormValidator {

    protected $rules = [

        'name' => 'required|min:2' ,


    ];


}